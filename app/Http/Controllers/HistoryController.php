<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Faker\Factory as Faker;

class HistoryController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function indexBlacklist()
    {

        $serverName = [
            "serverLocal" => "Server Local",
            "serverFirst" => "Server First",
            "serverSecond" => "Server Second"
        ];

        return view('history.blacklist.index',compact('serverName'));
    }

    /*public function datatableBlacklist()
    {
        $faker = Faker::create();

        // Dummy data array
        $application = [];

        // Define the number of rows you want to generate
        $rowCount = 10;

        for ($i = 0; $i < $rowCount; $i++) {
            $application[] = [
                'id' => $i + 1,
                'ticket_no' => 'SR-' . $faker->unique()->randomNumber(6),
                'types_application' => $faker->randomElement(['New Application', 'Upgrade', 'Maintenance']),
                'project_name' => $faker->sentence(2),
                'server_name' => $faker->randomElement(['Server Local', 'Server First', 'Server Second']),  // Use the selected server name
                'ip_address' => $faker->ipv4,
                'fqdn_system' => $faker->domainName,
                'date_created' => $faker->date(),
                'date_updated' => $faker->date(),
                'status' => $faker->randomElement(['Pending', 'In Progress', 'Done Created']),
            ];
        }


                return datatables()->of($application)
                ->addIndexColumn()
                ->addColumn('ticket_number', function ($row) {
                    return $row['ticket_no'];
                })
                ->addColumn('types_application', function ($row) {
                    return $row['types_application'];
                })
                ->addColumn('project_name', function ($row) {
                    return $row['project_name'];
                })
                ->addColumn('server_name', function ($row) {
                    return $row['server_name'];
                })
                ->addColumn('ip_address_server', function ($row) {
                    return $row['ip_address'];
                })
                ->addColumn('fqdn_system', function ($row) {
                    return $row['fqdn_system'];
                })
                ->addColumn('date_created', function ($row) {
                    return $row['date_created'];
                })
                ->addColumn('date_updated', function ($row) {
                    return $row['date_updated'];
                })
                ->addColumn('status', function ($row) {
                    return $row['status'];
                })
                ->make(true);
    }*/
    public function searching(Request $request)
    {
        $serverName = $request->serverName;

        return response()->json($serverName);
    }

    /*public function datatableSearching(Request $request)
    {

        $serverName = $request->serverName;
        $titleCaseString = ucwords(preg_replace('/(?<!\ )[A-Z]/', ' $0', $serverName));
        // Initialize Faker
        $faker = Faker::create();

        // Dummy data array
        $application = [];

        // Define the number of rows you want to generate
        $rowCount = 10;

        for ($i = 0; $i < $rowCount; $i++) {
            $application[] = [
                'id' => $i + 1,
                'ticket_no' => 'SR-' . $faker->unique()->randomNumber(6),
                'types_application' => $faker->randomElement(['New Application', 'Upgrade', 'Maintenance']),
                'project_name' => $faker->sentence(2),
                'server_name' => $titleCaseString,  // Use the selected server name
                'ip_address' => $faker->ipv4,
                'fqdn_system' => $faker->domainName,
                'date_created' => $faker->date(),
                'date_updated' => $faker->date(),
                'status' => $faker->randomElement(['Pending', 'In Progress', 'Done Created']),
            ];
        }


        return datatables()->of($application)
        ->addIndexColumn()
        ->addColumn('ticket_number', function ($row) {
            return $row['ticket_no'];
        })
        ->addColumn('types_application', function ($row) {
            return $row['types_application'];
        })
        ->addColumn('project_name', function ($row) {
            return $row['project_name'];
        })
        ->addColumn('server_name', function ($row) {
            return $row['server_name'];
        })
        ->addColumn('ip_address_server', function ($row) {
            return $row['ip_address'];
        })
        ->addColumn('fqdn_system', function ($row) {
            return $row['fqdn_system'];
        })
        ->addColumn('date_created', function ($row) {
            return $row['date_created'];
        })
        ->addColumn('date_updated', function ($row) {
            return $row['date_updated'];
        })
        ->addColumn('status', function ($row) {
            return $row['status'];
        })
        ->make(true);
    }*/



    public function indexWhitelist()
    {

        $serverName = [
            "serverLocal" => "Server Local",
            "serverFirst" => "Server First",
            "serverSecond" => "Server Second"
        ];

        return view('history.whitelist.index',compact('serverName'));
    }

    public function datatableWhitelist()
    {
                // Dummy data array
                $application = [
                    [
                        'id' => 1,
                        'ticket_no' => 'SR-123456',
                        'types_application' => 'New Application',
                        'project_name' => 'MyLAND PROD',
                        'server_name' => 'JKPTG_ApacheWeb1_P',
                        'ip_address' => '10.10.10.101',
                        'fqdn_system' => 'myland.gov.my',
                        'date_created' => '20-06-2024',
                        'date_updated' => '20-06-2024',
                        'status' => 'Done Created',
                    ],
                    // Add more rows as needed
                ];

                return datatables()->of($application)
                ->addIndexColumn()
                ->addColumn('ticket_number', function ($row) {
                    return $row['ticket_no'];
                })
                ->addColumn('types_application', function ($row) {
                    return $row['types_application'];
                })
                ->addColumn('project_name', function ($row) {
                    return $row['project_name'];
                })
                ->addColumn('server_name', function ($row) {
                    return $row['server_name'];
                })
                ->addColumn('ip_address_server', function ($row) {
                    return $row['ip_address'];
                })
                ->addColumn('fqdn_system', function ($row) {
                    return $row['fqdn_system'];
                })
                ->addColumn('date_created', function ($row) {
                    return $row['date_created'];
                })
                ->addColumn('date_updated', function ($row) {
                    return $row['date_updated'];
                })
                ->addColumn('status', function ($row) {
                    return $row['status'];
                })
                ->make(true);
    }


    public function indexFail()
    {
        $serverName = [
            "serverLocal" => "Server Local",
            "serverFirst" => "Server First",
            "serverSecond" => "Server Second"
        ];

        return view('history.fail2ban.index',compact('serverName'));
    }

    public function datatableFail()
    {
                // Dummy data array
                $application = [
                    [
                        'id' => 1,
                        'ticket_no' => 'SR-123456',
                        'types_application' => 'New Application',
                        'project_name' => 'MyLAND PROD',
                        'server_name' => 'JKPTG_ApacheWeb1_P',
                        'ip_address' => '10.10.10.101',
                        'fqdn_system' => 'myland.gov.my',
                        'date_created' => '20-06-2024',
                        'date_updated' => '20-06-2024',
                        'status' => 'Done Created',
                    ],
                    // Add more rows as needed
                ];

                return datatables()->of($application)
                ->addIndexColumn()
                ->addColumn('ticket_number', function ($row) {
                    return $row['ticket_no'];
                })
                ->addColumn('types_application', function ($row) {
                    return $row['types_application'];
                })
                ->addColumn('project_name', function ($row) {
                    return $row['project_name'];
                })
                ->addColumn('server_name', function ($row) {
                    return $row['server_name'];
                })
                ->addColumn('ip_address_server', function ($row) {
                    return $row['ip_address'];
                })
                ->addColumn('fqdn_system', function ($row) {
                    return $row['fqdn_system'];
                })
                ->addColumn('date_created', function ($row) {
                    return $row['date_created'];
                })
                ->addColumn('date_updated', function ($row) {
                    return $row['date_updated'];
                })
                ->addColumn('status', function ($row) {
                    return $row['status'];
                })
                ->make(true);
    }

}
