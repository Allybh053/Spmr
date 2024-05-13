<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
//use Faker\Factory as Faker;

use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\ExportStatisticData;

class ReportDetailSenderController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $serverName = [
            "serverLocal" => "Server Local",
            "serverFirst" => "Server First",
            "serverSecond" => "Server Second"
        ];

        return view('reports.detailSender.index',compact( 'serverName'));
    }

    /*public function validation(Request $request)
    {
        $serverName = $request->serverName;

        // Generate dummy data based on server name
        switch ($serverName) {
            case 'Server A':
                $senderRange = [50, 200];
                $receiverRange = [500, 2000];
                break;
            case 'Server B':
                $senderRange = [100, 300];
                $receiverRange = [600, 2500];
                break;
            default:
                $senderRange = [50, 200];
                $receiverRange = [500, 2000];
                break;
        }

        // Create a Faker instance
        $faker = Faker::create();

        // Initialize an empty array to store data
        $dataReports = [];

        // Generate 20 or more sets of dummy data
        for ($i = 0; $i < 20; $i++) {
            $dataReport = [
                "serverName" => $serverName,
                "sender" => [
                    "localemail" => $faker->safeEmail,
                    "senderIcon" => $faker->randomElement(['->', '<-']),
                    "externalemail" => $faker->safeEmail,
                    "mail" => $faker->numberBetween($senderRange[0], $senderRange[1]) . "MB",
                    "size" => $faker->numberBetween($senderRange[0], $senderRange[1]) . "MB",
                    "lastDate" => $faker->date('d-m-Y'),
                ],
                "receiver" => [
                    "localemail" => $faker->safeEmail,
                    "senderIcon" => $faker->randomElement(['->', '<-']),
                    "externalemail" => $faker->safeEmail,
                    "mail" => $faker->numberBetween($receiverRange[0], $receiverRange[1]) . "MB",
                    "size" => $faker->numberBetween($receiverRange[0], $receiverRange[1]) . "MB",
                    "lastDate" => $faker->date('d-m-Y'),
                ]
            ];

            // Add the generated data to the array
            $dataReports[] = $dataReport;
        }

        // Return the data as a JSON response
        return new JsonResponse(["data" => $dataReports]);
    }*/
}
