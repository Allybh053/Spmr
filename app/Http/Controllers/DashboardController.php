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
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index(Request $request,$limit = null)
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        // Fetch server IDs associated with the user's roles
        $serverIds = RoleServer::whereIn('role_servers.role_id', $user->roles->pluck('id')->all())
            ->leftJoin('servers','servers.id','role_servers.server_id')
            ->where('servers.status',1)
            ->pluck('server_id');

        $defaultServerId = $serverIds->min();

        $serverconfig =  Server::whereIn('master_id', $serverIds)
            ->where('status_cluster', 1)->orderBy('id', 'asc')
            ->select('id', 'hostname', 'ipaddress','subhostname')
            ->get();

        $serverList = [];
        $listServer = Server::whereIn('id', $serverIds)->where('status', 1)->orderBy('id', 'asc')->get();
        foreach ($listServer as $server) {
            $subServer = Server::where('master_id',$server->id)->where('status_cluster',1)->orderBy('id', 'asc')->get();
            $subServer2 = Server::where('master_id',$server->id)->where('status_cluster',1)->orderBy('id', 'asc')->get();

            $subs2=[];
            foreach($subServer2 as $subServers2){
                $subs2[] = [
                    'subsid2' => $subServers2->id,
                    'subhostname2' => $subServers2->subhostname,
                    'subIpaddress2' => $subServers2 ->ipaddress
                ];
            }
            $subs=[];
            foreach($subServer as $subServers){

                // Calculate statistics for each subserver
                $subDataSmtpForm = DB::table('smtp_relay_req_form')
                    ->leftJoin('smtp_relay_server', 'smtp_relay_req_form.form_id', '=', 'smtp_relay_server.form_id')
                    ->where('smtp_relay_req_form.status', 1)
                    ->where('smtp_relay_req_form.sub_smtp_relay_server', $subServers->subhostname)
                    ->count();

                $subDataDomain = DB::table('mailrouting')
                    ->where('mailrouting.server', $subServers->hostname)
                    ->count();

                $subDataSasl = DB::table('smtp_relay_req_form')
                    ->where('smtp_relay_req_form.sub_smtp_relay_server', $subServers->subhostname)
                    ->where('smtp_relay_req_form.smtp_relay_auth_method', 2)
                    ->where('smtp_relay_req_form.status', 1)
                    ->count();

                $subDataIpBased = DB::table('smtp_relay_req_form')
                    ->where('smtp_relay_req_form.sub_smtp_relay_server', $subServers->subhostname)
                    ->where('smtp_relay_req_form.smtp_relay_auth_method', 1)
                    ->where('smtp_relay_req_form.status', 1)
                    ->count();

                $subs[] = [
                    'subsid' => $subServers->id,
                    'subhostname' => $subServers->subhostname,
                    'subIpaddress' => $subServers -> ipaddress,
                    'subTotalApplicationRegistered' => $subDataSmtpForm,
                    'subTotalDomain' => $subDataDomain,
                    'subTotalSasl' => $subDataSasl,
                    'subTotalIpBased' => $subDataIpBased
                ];
            }

            $serverList[] = [
                'id' => $server->id,
                'hostname' => $server->hostname,
                'ipaddress' => $server ->ipaddress,
                'subsDataHostname' => $subs,
                'subsDataHostname2' =>$subs2
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
            $departmentCounts = $filteredDataMinistry;
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

        if ($request->expectsJson()) {
            $responseData = [
                'departmentCounts' => $departmentCounts,
                'departmentCounts2' => $departmentCounts2,
                'days' => $days
            ];

            return response()->json($responseData);
        }

        $serverListAgencies = [];
        $listServerAgencies = Server::whereIn('id', $serverIds)->where('status', 1)->orderBy('id', 'asc')->get();
        foreach ($listServerAgencies as $serverAgencies) {
            $dataSmtpFormAgencies = SmtpRelayReqForm::select(
                DB::raw('COALESCE(agencies.code_name::text, ministrys.code_name) as code_name'),
                DB::raw('COALESCE(agencies.name::text, ministrys.name) as name'),
                DB::raw('COUNT(COALESCE(smtp_relay_req_form.agencies::bigint, ministrys.id)) as total_agencies')
            )
                ->leftJoin('smtp_relay_server as srs', 'srs.form_id', '=', 'smtp_relay_req_form.form_id')
                ->leftJoin('agencies', 'agencies.id',  DB::raw('CAST(smtp_relay_req_form.agencies AS bigint)'))
                ->leftJoin('ministrys', 'ministrys.id',   DB::raw('CAST(smtp_relay_req_form.ministry AS bigint)'))
                ->where('smtp_relay_req_form.smtp_relay_server', $serverAgencies->hostname)
                ->where('smtp_relay_req_form.status', 1)
                ->groupBy('agencies.code_name', 'ministrys.code_name', 'agencies.name', 'ministrys.name')
                ->orderByDesc('total_agencies')
                ->limit(5)
                ->get();
            $agenciesData = [];
            foreach ($dataSmtpFormAgencies as $data) {
                $agenciesData[] = [
                    'agency' => $data->code_name,
                    'agencies_name' => $data->name,
                    'totalAgencies' => $data->total_agencies
                ];
            }

            $serverListAgencies[] = [
                'hostname' => $serverAgencies->hostname,
                'ipAddress' => $serverAgencies->ipaddress,
                'agencies' => $agenciesData
            ];
        }

        $serverListMinistry = [];
        $listServerMinistry = Server::whereIn('id', $serverIds)->where('status', 1)->orderBy('id', 'asc')->get();
        foreach ($listServerMinistry as $serverMinistry) {
            $dataSmtpFormMinistry = SmtpRelayReqForm::select('smtp_relay_req_form.smtp_relay_server','smtp_relay_req_form.ministry',DB::raw('COUNT(smtp_relay_req_form.ministry) as total_ministry'))
                ->leftJoin('smtp_relay_server as srs','srs.form_id','smtp_relay_req_form.form_id')
                ->where('smtp_relay_req_form.smtp_relay_server',$serverMinistry->hostname)
                ->where('smtp_relay_req_form.status', 1)
                ->groupBy('smtp_relay_req_form.smtp_relay_server','smtp_relay_req_form.ministry')
                ->orderByDesc('total_ministry')
                ->limit(5)
                ->get();

            $ministryData = [];
            foreach ($dataSmtpFormMinistry as $dataMinistry) {
                $ministryData[] = [
                    'ministry' => $dataMinistry->ministry,
                    'totalMinistry' => $dataMinistry->total_ministry
                ];
            }

            $serverListMinistry[] = [
                'hostname' => $serverMinistry->hostname,
                'ipAddress' => $serverMinistry->ipaddress,
                'ministry' => $ministryData
            ];
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Dashboard',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'dashboard' => 'Retrieve Data From Server',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);
        return view('dashboard.index', compact(
            'user', 'role', 'serverconfig', 'serverList', 'filteredMinistries',
            'filteredDept', 'departmentCounts', 'departmentCounts2', 'limit', 'days','defaultServerId',
            'totalCountDashboard','serverListAgencies','serverListMinistry'));
    }
    private function generateDataMinistry($limit = null)
    {
        $dataSmtpForm = SmtpRelayReqForm::where('status', 1)->get()->toArray();
        $totalCountsAgencies = [];
        $departmentCounts = [];
        $allServers = [];
        foreach ($dataSmtpForm as $val) {
            $totalAgencies = SmtpRelayReqForm::where('agencies', $val['agencies'])
                ->where('smtp_relay_server', $val['smtp_relay_server'])->count();

            $totalCountsAgencies[$val['agencies']][$val['smtp_relay_server']] = $totalAgencies;
        }
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
        $allServers = collect($departmentCounts)->pluck('postmasters')->unique();
        $groupedDepartmentCounts = collect($departmentCounts)->groupBy('postmasters');
        $limitedDepartmentCounts = [];
        foreach ($allServers as $server) {
            $serverData = $groupedDepartmentCounts[$server] ?? [];
            $sortedServerData = collect($serverData)->sortByDesc('count');
            $limitedServerData = $sortedServerData->take(5);
            $limitedDepartmentCounts = array_merge($limitedDepartmentCounts, $limitedServerData->toArray());
        }
        $limitedCollection = collect($limitedDepartmentCounts);

        return $limitedCollection;
    }
    private function generateDataDept($limit = null)
    {
        $dataSmtpForm = SmtpRelayReqForm::where('status', 1)
            ->select('id', 'smtp_relay_server', 'dept_div_unit', 'form_id')
            ->get();
        $totalCountsDpt = [];
        $departmentCounts2 = [];
        foreach ($dataSmtpForm as $val) {
            $totalDpt = SmtpRelayReqForm::where('dept_div_unit', $val->dept_div_unit)
                ->where('smtp_relay_server', $val->smtp_relay_server)
                ->count();
            $totalCountsDpt[$val->dept_div_unit][$val->smtp_relay_server] = $totalDpt;
        }
        foreach ($totalCountsDpt as $dptName => $smtpRelayServers) {
            foreach ($smtpRelayServers as $smtpRelayServer => $count) {
                $departmentCounts2[] = [
                    'department_name' => $dptName,
                    'postmasters' => $smtpRelayServer,
                    'count' => $count
                ];
            }
        }
        if ($limit) {
            $departmentCounts2 = array_merge($departmentCounts2, array_fill(0, $limit - 1, $departmentCounts2));
        }
        usort($departmentCounts2, function ($a, $b) {
            return $b['count'] - $a['count'];
        });
        $groupedDepartmentCounts2 = collect($departmentCounts2)->groupBy('postmasters');
        $limitedDepartmentCounts2 = [];
        foreach ($groupedDepartmentCounts2 as $serverData2) {
            $sortedServerData2 = collect($serverData2)->sortByDesc('count');
            $limitedServerData2 = $sortedServerData2->take(5);
            $limitedDepartmentCounts2 = array_merge($limitedDepartmentCounts2, $limitedServerData2->toArray());
        }
        return collect($limitedDepartmentCounts2);
    }
    private function countTotalDashboard($limit = null)
    {
        $user = Auth::user();
        $serverIds = RoleServer::whereIn('role_id', $user->roles->pluck('id')->all())
            ->pluck('server_id');
        $dataSmtpFormReq = [];
        $listServer = Server::whereIn('id', $serverIds)->where('status', 1)->orderBy('id', 'asc')->get();
        $serverSum = [];

        foreach ($listServer as $server) {
            $dataSmtpForm = DB::table('smtp_relay_req_form')
                ->leftJoin('smtp_relay_server', 'smtp_relay_req_form.form_id', '=', 'smtp_relay_server.form_id')
                ->where('smtp_relay_req_form.status', 1)
                ->where('smtp_relay_req_form.smtp_relay_server', $server->hostname)
                ->count();

            $dataDomain = DB::table('mailrouting')
                ->where('mailrouting.server', $server->hostname)
                ->count();
            $dataSasl = DB::table('smtp_relay_req_form')
                ->where('smtp_relay_req_form.smtp_relay_server', $server->hostname)
                ->where('smtp_relay_req_form.smtp_relay_auth_method', 2)
                ->where('smtp_relay_req_form.status', 1)
                ->count();
            $dataIpBased = DB::table('smtp_relay_req_form')
                ->where('smtp_relay_req_form.smtp_relay_server', $server->hostname)
                ->where('smtp_relay_req_form.smtp_relay_auth_method', 1)
                ->where('smtp_relay_req_form.status', 1)
                ->count();

            $subServer = Server::where('master_id', $server->id)
                ->orderBy('status_cluster', 'desc')
                ->get();

            $serverSum[] = [
                'id' => $server->id,
                'hostname' => $server->hostname,
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
