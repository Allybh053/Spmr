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
use Faker\Factory as Faker;
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

        $defaultServerId = 1;

        // Filter servers based on the fetched IDs
        /*$serverconfig = Server::whereIn('id', $serverIds)
        ->where('status', 1)->orderBy('id', 'asc')
        ->pluck('hostname', 'id');*/
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
            //$departmentCounts = $this->generateDataMinistry(); // No limit specified for "All"
            $departmentCounts = $filteredDataMinistry; // No limit specified for "All"
            //$departmentCounts2 = $this->generateDataDept();
            $departmentCounts2 = $filteredDataDept;
        } else {
            //$departmentCounts = $this->generateDataMinistry($limit);
            $departmentCounts = $filteredDataMinistry;
            //$departmentCounts2 = $this->generateDataDept($limit);
            $departmentCounts2 = $filteredDataDept;
        }

        $filteredMinistries = $departmentCounts->filter(function ($department) use ($serverList) {
            return in_array($department['postmasters'], array_column($serverList, 'hostname'));
        })->pluck('ministry_name')->toArray();

        $filteredDept = $departmentCounts2->filter(function ($department2) use ($serverList) {
            return in_array($department2['postmasters'], array_column($serverList, 'hostname'));
        })->pluck('department_name')->toArray();

            $faker = Faker::create();
            $days = range(1, 10);


            /*if ($request->expectsJson()) {
                return response()->json(['departmentCounts' => $departmentCounts, 'days' => $days]);
            }*/

        if ($request->expectsJson()) {
            $responseData = [
                'departmentCounts' => $departmentCounts,
                'departmentCounts2' => $departmentCounts2,
                'days' => $days
            ];

            return response()->json($responseData);
        }

            $p1Data = [];
            $p2Data = [];
            $p3Data = [];
            for ($i = 0; $i < 10; $i++) { // Assuming 10 data points for each P1, P2, P3 for example
                $p1Data[] = $faker->numberBetween(1, 100);
                $p2Data[] = $faker->numberBetween(1, 100);
                $p3Data[] = $faker->numberBetween(1, 100);
            }

            // Pass the data to the view
            return view('dashboard.index', compact(
                'user', 'role', 'serverconfig', 'serverList', 'filteredMinistries',
                'filteredDept', 'departmentCounts', 'departmentCounts2', 'limit', 'days', 'p1Data',
                'p2Data', 'p3Data','defaultServerId'));
        }

    public function api(Request $request, $limit=null)
    {
        $faker = Faker::create();

        $serverId = $request->serverId;
        $ipAddress = $faker->ipv4;
        // Generate fake data for total applications, domains, and SASL
        $totalApplications = $faker->numberBetween(100, 1000);
        $totalDomains = $faker->numberBetween(50, 500);
        $totalSASL = $faker->numberBetween(20, 200);

        // Generate fake data for the table
        $tableData = [];
        for ($i = 0; $i < 5; $i++) {
            $tableData[] = [
                'columnA' => $faker->word,
                'columnB' => $faker->word,
                'columnC' => $faker->word,
                'columnD' => $faker->word,
            ];
        }

        // Generate fake status for each service
        $services = [
            'Postfix' => $faker->randomElement(['Online', 'Offline']),
            'Fail-To-Ban' => $faker->randomElement(['Online', 'Offline']),
            'Apache' => $faker->randomElement(['Online', 'Offline']),
            'Webmin' => $faker->randomElement(['Online', 'Offline']),
            'Replicate Mail Relay' => $faker->randomElement(['Online', 'Offline']),
            'Policy Server' => $faker->randomElement(['Online', 'Offline']),
            'Keep Alive' => $faker->randomElement(['Online', 'Offline']),
        ];

        $departmentCounts = $this->generateDataMinistry($limit);
        $departmentCounts2 = $this->generateDataDept($limit);


        $mailqData = $this->generateFakerMailQueue(50); // Generate data for 50 entries

        // Extract timestamps and mail queue counts
        $timestamps = collect($mailqData)->pluck('created_at')->map(function ($timestamp) {
            return $timestamp->format('Y-m-d H:i:s'); // Format timestamp as needed
        });
        $mailCounts = collect($mailqData)->pluck('mail_count');

        $faker = Faker::create();
        $days = range(1, 10);

        $p1Data = [];
        $p2Data = [];
        $p3Data = [];
        for ($i = 0; $i < 10; $i++) { // Assuming 10 data points for each P1, P2, P3 for example
            $p1Data[] = $faker->numberBetween(1, 100);
            $p2Data[] = $faker->numberBetween(1, 100);
            $p3Data[] = $faker->numberBetween(1, 100);
        }

        return new JsonResponse([
            'totalApplications' => $totalApplications,
            'totalDomains' => $totalDomains,
            'totalSASL' => $totalSASL,
            'data' => $tableData,
            'status' => $faker->randomElement(['Online', 'Offline']),
            'services' => $services,
            'ipAddress' => $ipAddress,
            'departmentCounts' => $departmentCounts,
            'departmentCounts2' => $departmentCounts2,
            'timestamps' => $timestamps,
            'mailCounts' => $mailCounts,
            'days' => $days,
            'p1Data'=> $p1Data,
            'p2Data'=> $p2Data,
            'p3Data'=> $p3Data,
        ]);
    }


    private function generateDataMinistry($limit = null)
    {
        $dataSmtpForm = SmtpRelayReqForm::where('status', 0)->get()->toArray();
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

        $dataSmtpForm = SmtpRelayReqForm::where('status', 0)->get()->toArray();
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

    private function generateFakerMailQueue($numEntries)
    {
        $faker = Faker::create();
        $mailQueueData = [];

        // Generate Faker data for mail queue
        for ($i = 0; $i < $numEntries; $i++) {
            $timestamp = $faker->dateTimeBetween('-30 days', 'now'); // Random timestamp within the last 30 days
            $mailCount = $faker->numberBetween(0, 100); // Random number of emails in the queue

            $mailQueueData[] = [
                'created_at' => $timestamp,
                'mail_count' => $mailCount
            ];
        }

        return $mailQueueData;
    }


}
