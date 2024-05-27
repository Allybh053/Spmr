<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\DomainMinistryAgencies;
use App\Models\RoleServer;
use App\Models\Server;
use App\Models\smtp_relay_server;
use App\Models\SmtpRelayReqForm;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Mail;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Validator;

class DashboardController extends Controller
{
    public function index(Request $request,$limit = null)
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
       // Fetch server IDs associated with the user's roles
        $serverIds = RoleServer::whereIn('role_id', $user->roles->pluck('id')->all())
        ->pluck('server_id');

        $defaultServerId = $serverIds->min();

        $serverconfig =  Server::whereIn('id', $serverIds)
            ->where('status', 1)->orderBy('id', 'asc')
            ->select('id', 'hostname', 'ipaddress')
            ->get();

        $serverList = [];
        $listServer = Server::whereIn('id', $serverIds)->where('status', 1)->orderBy('id', 'asc')->get();
        foreach ($listServer as $server) {
            $serverList[] = [
                'hostname' => $server->hostname
            ];
        }

        $limit = $request->input('limit', $limit);

        $collectedDataMinistry = $this->generateDataMinistry();
        $filteredDataMinistry = collect($collectedDataMinistry)->filter(function ($fdm) use ($serverList) {
            return in_array($fdm['postmasters'], array_column($serverList, 'hostname'));
        });

        $collectedDataDept = $this->generateDataDept();
        $filteredDataDept = collect($collectedDataDept)->filter(function ($fdm) use ($serverList) {
            return in_array($fdm['postmasters'], array_column($serverList, 'hostname'));
        });

        if ($limit === 'all') {
            $departmentCounts = $filteredDataMinistry; // No limit specified for "All"
            $departmentCounts2 = $filteredDataDept;
        } else {
            $departmentCounts = $filteredDataMinistry;
            $departmentCounts2 = $filteredDataDept;
        }

        $filteredMinistries = $departmentCounts->filter(function ($department) use ($serverList) {
            return in_array($department['postmasters'], array_column($serverList, 'hostname'));
        })->pluck('ministry_name')->toArray();

        $filteredDept = $departmentCounts2->filter(function ($department2) use ($serverList) {
            return in_array($department2['postmasters'], array_column($serverList, 'hostname'));
        })->pluck('department_name')->toArray();

            $days = range(1, 10);

            $totalCountDashboard = $this->countTotalDashboard();
            //dd($totalCountDashboard);

        if ($request->expectsJson()) {
            $responseData = [
                'departmentCounts' => $departmentCounts,
                'departmentCounts2' => $departmentCounts2,
                'days' => $days
            ];

            return response()->json($responseData);
        }

            // Pass the data to the view
            return view('dashboard.index', compact(
                'user', 'role', 'serverconfig', 'serverList', 'filteredMinistries',
                'filteredDept', 'departmentCounts', 'departmentCounts2', 'limit', 'days','defaultServerId',
                'totalCountDashboard'));
        }

    private function generateDataMinistry($limit = null)
    {
        $dataSmtpForm = SmtpRelayReqForm::where('status', 1)->get()->toArray();
        $ids = [];
        foreach ($dataSmtpForm as $item) {
            $ids[] = $item['id'];
        }

        $arraySmtpForm = SmtpRelayReqForm::find($ids);
        $totalCounts = []; // Initialize an array to store total counts for each form_id
        $totalCountsAgencies = [];
        $totalPostmaster = [];
        $totalDataPostmaster = [];

        foreach ($arraySmtpForm as $val) {
            $totalDataServer = SmtpRelayReqForm::where('smtp_relay_server', $val['smtp_relay_server'])->get();
            $totalDataPostmaster[$val['smtp_relay_server']] = $totalDataServer;

            $totalServer = SmtpRelayReqForm::where('smtp_relay_server', $val['smtp_relay_server'])->count();
            $totalPostmaster[$val['smtp_relay_server']] = $totalServer;

            $totalAgencies = SmtpRelayReqForm::where('agencies', $val['agencies'])->where('smtp_relay_server', $val['smtp_relay_server'])->count();
            $totalCountsAgencies[$val['agencies']][$val['smtp_relay_server']] = $totalAgencies;

            $count = smtp_relay_server::where('form_id', $val['form_id'])->count();
            $totalCounts[$val['form_id']] = $count;
        }

        // Generate department counts
        $departmentCounts = [];

        // Generating data if the limit is specified
        if ($limit) {
            for ($i = 0; $i < $limit; $i++) {
                foreach ($totalCountsAgencies as $agenciesName => $smtpRelayServers) {
                    foreach ($smtpRelayServers as $smtpRelayServer => $count) {
                        $departmentCounts[] = [
                            'ministry_name' => $agenciesName,
                            'postmasters' => $smtpRelayServer,
                            'count' => $count
                        ];
                    }
                }
            }
        } else {
            $numDepartments = 1;
            for ($i = 0; $i < $numDepartments; $i++) {
                foreach ($totalCountsAgencies as $agenciesName => $smtpRelayServers) {
                    foreach ($smtpRelayServers as $smtpRelayServer => $count) {
                        $departmentCounts[] = [
                            'ministry_name' => $agenciesName,
                            'postmasters' => $smtpRelayServer,
                            'count' => $count
                        ];
                    }
                }
            }
        }

        // Get all unique servers from the departmentCounts array
        $allServers = collect($departmentCounts)->pluck('postmasters')->unique();

        // Group the departmentCounts array by the postmaster/server
        $groupedDepartmentCounts = collect($departmentCounts)->groupBy('postmasters');

        // Initialize an empty array to store the limited data
        $limitedDepartmentCounts = [];

        // Iterate over each unique server
        foreach ($allServers as $server) {
            // If the server exists in the grouped data, retrieve its data
            // Otherwise, set an empty array for this server
            $serverData = $groupedDepartmentCounts[$server] ?? [];

            // Sort the server data by count in descending order
            $sortedServerData = collect($serverData)->sortByDesc('count');

            // Take the first 5 elements for this server
            $limitedServerData = $sortedServerData->take(5);

            // Add the limited server data to the result array
            $limitedDepartmentCounts = array_merge($limitedDepartmentCounts, $limitedServerData->toArray());
        }

        // Convert the limited array to a collection
        $limitedCollection = collect($limitedDepartmentCounts);

        return $limitedCollection;
    }

    private function generateDataDept($limit = null)
    {

        $dataSmtpForm = SmtpRelayReqForm::where('status', 1)->get()->toArray();
        $ids = [];
        foreach ($dataSmtpForm as $item) {
            $ids[] = $item['id'];
        }

        $arraySmtpForm = SmtpRelayReqForm::find($ids);
        $totalCounts = [];
        $totalCountsDpt = [];
        $totalPostmaster = [];
        $totalDataPostmaster = [];

        foreach ($arraySmtpForm as $val) {
            $totalDataServer = SmtpRelayReqForm::where('smtp_relay_server', $val['smtp_relay_server'])->get();
            $totalDataPostmaster[$val['smtp_relay_server']] = $totalDataServer;

            $totalServer = SmtpRelayReqForm::where('smtp_relay_server', $val['smtp_relay_server'])->count();
            $totalPostmaster[$val['smtp_relay_server']] = $totalServer;

            $totalDpt = SmtpRelayReqForm::where('dept_div_unit', $val['dept_div_unit'])->where('smtp_relay_server', $val['smtp_relay_server'])->count();
            $totalCountsDpt[$val['dept_div_unit']][$val['smtp_relay_server']] = $totalDpt;

            $count = smtp_relay_server::where('form_id', $val['form_id'])->count();
            $totalCounts[$val['form_id']] = $count;
        }

        $departmentCounts2 = [];

        // Generating data if the limit is specified
        if ($limit) {
            for ($i = 0; $i < $limit; $i++) {
                foreach ($totalCountsDpt as $dptName => $smtpRelayServers) {
                    foreach ($smtpRelayServers as $smtpRelayServer => $count) {
                        $departmentCounts2[] = [
                            'department_name' => $dptName,
                            'postmasters' => $smtpRelayServer,
                            'count' => $count
                        ];
                    }
                }
            }
        } else {
            $numDepartments = 1;
            for ($i = 0; $i < $numDepartments; $i++) {
                foreach ($totalCountsDpt as $dptName => $smtpRelayServers) {
                    foreach ($smtpRelayServers as $smtpRelayServer => $count) {
                        $departmentCounts2[] = [
                            'department_name' => $dptName,
                            'postmasters' => $smtpRelayServer,
                            'count' => $count
                        ];
                    }
                }
            }
        }

        // Sort the generated data by count in descending order
        usort($departmentCounts2, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        // Get all unique servers from the departmentCounts array
        $allServers2 = collect($departmentCounts2)->pluck('postmasters')->unique();

        // Group the departmentCounts array by the postmaster/server
        $groupedDepartmentCounts2 = collect($departmentCounts2)->groupBy('postmasters');

        // Initialize an empty array to store the limited data
        $limitedDepartmentCounts2 = [];

        // Iterate over each unique server
        foreach ($allServers2 as $server2) {
            // If the server exists in the grouped data, retrieve its data
            // Otherwise, set an empty array for this server
            $serverData2 = $groupedDepartmentCounts2[$server2] ?? [];

            // Sort the server data by count in descending order
            $sortedServerData2 = collect($serverData2)->sortByDesc('count');

            // Take the first 5 elements for this server
            $limitedServerData2 = $sortedServerData2->take(5);

            // Add the limited server data to the result array
            $limitedDepartmentCounts2 = array_merge($limitedDepartmentCounts2, $limitedServerData2->toArray());
        }

        // Convert the limited array to a collection
        $limitedCollection2 = collect($limitedDepartmentCounts2);

        return $limitedCollection2;
    }

    private function countTotalDashboard($limit = null){
        $user = Auth::user();
        $serverIds = RoleServer::whereIn('role_id', $user->roles->pluck('id')->all())
            ->pluck('server_id');

        $dataSmtpFormReq = [];
        $listServer = Server::whereIn('id', $serverIds)->where('status', 1)->orderBy('id', 'asc')->get();

        $serverSum=[];
        foreach ($listServer as $server) {
            $dataSmtpForm = DB::table('smtp_relay_req_form')
                ->join('smtp_relay_server', 'smtp_relay_req_form.id', '=', 'smtp_relay_server.smtpid')
                ->join('servers', 'servers.hostname', '=', 'smtp_relay_req_form.smtp_relay_server')
                ->where('smtp_relay_req_form.status', 1)
                ->where('smtp_relay_req_form.smtp_relay_server', $server->hostname)
                ->select('smtp_relay_req_form.id AS form_id', 'servers.hostname AS server_hostname',
                    'smtp_relay_server.ip_addr_server AS server_ip')->get()->count();

            $dataDomain = DB::table('mailrouting')
                ->where('mailrouting.server', $server->hostname)->get()->count();

            $dataSasl = DB::table('smtp_relay_req_form')
                ->where('smtp_relay_req_form.smtp_relay_server', $server->hostname)
                ->where('smtp_relay_req_form.smtp_relay_auth_method', 2)->get()->count();

            $dataIpBased = DB::table('smtp_relay_req_form')
                ->where('smtp_relay_req_form.smtp_relay_server', $server->hostname)
                ->where('smtp_relay_req_form.smtp_relay_auth_method', 1)->get()->count();

            $serverSum=[
                'hostname'=>  $server->hostname,
                'ipAddress' => $server->ipaddress,
                'totalApplicationRegistered' => $dataSmtpForm,
                'totalDomain' => $dataDomain,
                'totalSasl' => $dataSasl,
                'totalIpBased' => $dataIpBased
            ];

            $dataSmtpFormReq[] = $serverSum;
        }

        return $dataSmtpFormReq;
    }
}
