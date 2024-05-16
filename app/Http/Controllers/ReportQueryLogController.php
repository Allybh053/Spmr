<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Faker\Factory as FakerFactory;


use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\ExportStatisticData;

class ReportQueryLogController extends Controller
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

        return view('reports.queryLog.index',compact( 'serverName'));
    }

    public function validation(Request $request)
    {
        $serverName = $request->serverName;

        // Define ranges based on server names
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
        $faker = FakerFactory::create();

        // Initialize an empty array to store data
        $dataReports = [];

        // Generate 20 or more sets of dummy data
        for ($i = 0; $i < 20; $i++) {
            $dataReport = [
                "Date" => $faker->date('Y-m-d'),
                "Sender Ip" => $faker->ipv4,
                "From Address" => $faker->safeEmail,
                "To Address" => $faker->safeEmail,
                "Email Subject" => $faker->sentence,
                "MTA Outbound ID/IP" => $faker->ipv4,
                "Status" => $faker->randomElement(['Delivered', 'Failed', 'Pending']),
            ];

            // Add the generated data to the array
            $dataReports[] = $dataReport;
        }

        // Return the data as a JSON response
        return new JsonResponse(["data" => $dataReports]);
    }
}
