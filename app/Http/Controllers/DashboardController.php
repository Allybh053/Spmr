<?php

namespace App\Http\Controllers;

use App\Models\SmtpRelayReqForm;
use App\Models\smtp_relay_server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Mail;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Server;
use App\Models\RoleServer;
use App\Models\DomainMinistryAgencies;
use Illuminate\Http\JsonResponse;
use Faker\Factory as Faker;

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
            ->where('status', 1)
            ->select('id', 'hostname', 'ipaddress')
            ->get();

        $limit = $request->input('limit', $limit);

        if ($limit === 'all') {
            $departmentCounts = $this->generateFakerData(); // No limit specified for "All"
            $departmentCounts2 = $this->generateFakerData2();
        } else {
            $departmentCounts = $this->generateFakerData($limit);
            $departmentCounts2 = $this->generateFakerData2($limit);
        }


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

            $mailqData = $this->generateFakerMailQueue(50); // Generate data for 50 entries

            // Extract timestamps and mail queue counts
            $timestamps = collect($mailqData)->pluck('created_at')->map(function ($timestamp) {
                return $timestamp->format('Y-m-d H:i:s'); // Format timestamp as needed
            });
            $mailCounts = collect($mailqData)->pluck('mail_count');


            // Pass the data to the view
            return view('dashboard.index', compact('user', 'role', 'serverconfig', 'departmentCounts', 'departmentCounts2', 'limit', 'days', 'p1Data', 'p2Data', 'p3Data','timestamps', 'mailCounts','defaultServerId'));
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

        $departmentCounts = $this->generateFakerData($limit);
        $departmentCounts2 = $this->generateFakerData2($limit);


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


    private function generateFakerData($limit = null)
    {
        // Create an instance of Faker
        //$faker = Faker::create();

        $dataSmtpForm = SmtpRelayReqForm::where('status', 0)->get()->toArray();
        $ids = [];
        foreach ($dataSmtpForm as $item) {
            $ids[] = $item['id'];
        }

        $arraySmtpForm = SmtpRelayReqForm::find($ids);
        $totalCounts = []; // Initialize an array to store total counts for each form_id
        $totalCountsAgencies = [];

        foreach ($arraySmtpForm as $val) {
            $totalAgencies = SmtpRelayReqForm::where('agencies', $val['agencies'])->count();
            $totalCountsAgencies[$val['agencies']] = $totalAgencies;
            $count = smtp_relay_server::where('form_id', $val['form_id'])->count();
            $totalCounts[$val['form_id']] = $count;
        }
        //dd($totalCountsAgencies);

        // Generate fake department counts
        $departmentCounts = [];

        // Generating fake data if the limit is specified
        if ($limit) {
            for ($i = 0; $i < $limit; $i++) {
                /*$departmentCounts[] = [
                    'ministry_name' => $faker->word, // Generate fake ministry name
                    'count' => $faker->numberBetween(1, 100) // Generate a random count
                ];*/
                foreach ($totalCountsAgencies as $agenciesName => $count) {
                    $departmentCounts[] = [
                        'ministry_name' => $agenciesName,
                        'count' => $count
                    ];
                }
            }
        } else {
            // If limit is not specified, generate a random number of departments
            //$numDepartments = $faker->numberBetween(5, 20); // Generate a random number of departments between 5 and 20
            $numDepartments = 1;
            for ($i = 0; $i < $numDepartments; $i++) {
                /*$departmentCounts[] = [
                    'ministry_name' => $faker->word, // Generate fake ministry name
                    'count' => $faker->numberBetween(1, 100) // Generate a random count
                ];*/
                foreach ($totalCountsAgencies as $agenciesName => $count) {
                    $departmentCounts[] = [
                        'ministry_name' => $agenciesName,
                        'count' => $count
                    ];
                }
            }
        }

        // Sort the generated data by count in descending order
        usort($departmentCounts, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        //dd($departmentCounts);
        return collect($departmentCounts);
    }

    private function generateFakerData2($limit = null)
    {

        $dataSmtpForm = SmtpRelayReqForm::where('status', 0)->get()->toArray();
        $ids = [];
        foreach ($dataSmtpForm as $item) {
            $ids[] = $item['id'];
        }

        $arraySmtpForm = SmtpRelayReqForm::find($ids);
        $totalCounts = [];
        $totalCountsDpt = [];

        foreach ($arraySmtpForm as $val) {
            $totalDpt = SmtpRelayReqForm::where('dept_div_unit', $val['dept_div_unit'])->count();
            $totalCountsDpt[$val['dept_div_unit']] = $totalDpt;
            $count = smtp_relay_server::where('form_id', $val['form_id'])->count();
            $totalCounts[$val['form_id']] = $count;
        }

        $departmentCounts2 = [];

        // Generating fake data if the limit is specified
        if ($limit) {
            for ($i = 0; $i < $limit; $i++) {
                foreach ($totalCountsDpt as $dptName => $count) {
                    $departmentCounts2[] = [
                        'department_name' => $dptName,
                        'count' => $count
                    ];
                }
            }
        } else {
            // If limit is not specified, generate a random number of departments
            $numDepartments = 1;
            for ($i = 0; $i < $numDepartments; $i++) {
                foreach ($totalCountsDpt as $dptName => $count) {
                    $departmentCounts2[] = [
                        'department_name' => $dptName,
                        'count' => $count
                    ];
                }
            }
        }

        // Sort the generated data by count in descending order
        usort($departmentCounts2, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        return collect($departmentCounts2);
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
