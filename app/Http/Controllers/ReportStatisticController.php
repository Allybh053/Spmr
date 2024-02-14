<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\ExportStatisticData;

class ReportStatisticController extends Controller
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

        return view('reports.trafficSummary.index',compact( 'serverName'));
    }

    public function validation(Request $request)
    {
        $serverName = $request->serverName;
    
        // Generate dummy data based on server name
        switch ($serverName) {
            case 'Server A':
                $dailyMailRange = [50, 200];
                $monthlyMailRange = [500, 2000];
                $yearlyMailRange = [5000, 20000];
                break;
            case 'Server B':
                $dailyMailRange = [100, 300];
                $monthlyMailRange = [600, 2500];
                $yearlyMailRange = [6000, 22000];
                break;
            default:
                $dailyMailRange = [50, 200];
                $monthlyMailRange = [500, 2000];
                $yearlyMailRange = [5000, 20000];
                break;
        }
    
        // Generate dummy data for daily history
        $dailyHistory = [];
        $currentMonthDays = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
        for ($i = 1; $i <= $currentMonthDays; $i++) {
            $dailyHistory[] = [
                "day" => sprintf("%02d", $i), // Pad single digits with leading zero
                "mail" => rand($dailyMailRange[0], $dailyMailRange[1]), // Random number of mails
                "size" => rand(1, 10) . "MB", // Random size
            ];
        }
    
        // Generate dummy data for monthly history
        $monthlyHistory = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyHistory[] = [
                "month" => date('M', mktime(0, 0, 0, $i, 1)),
                "mail" => rand($monthlyMailRange[0], $monthlyMailRange[1]),
                "size" => rand(10, 100) . "MB",
            ];
        }
    
        // Generate dummy data for yearly history
        $currentYear = date('Y');
        $yearlyHistory = [];
        for ($i = $currentYear - 4; $i <= $currentYear; $i++) {
            $yearlyHistory[] = [
                "year" => $i,
                "mail" => rand($yearlyMailRange[0], $yearlyMailRange[1]),
                "size" => rand(100, 500) . "MB",
            ];
        }
    
        // Build the data report array
        $dataReport = [
            "serverName" => $serverName,
            "reportPeriod" => "Month " . date('M Y'),
            "first" => "01 " . date('M Y') . " - 01:02",
            "last" => date('d') . " " . date('M Y') . " - " . date('H:i'),
            "mailSuccessSend" => [
                "mails" => rand(100, 500),
                "size" => rand(1, 20) . "MB",
            ],
            "mailFailed" => [
                "mails" => rand(1000, 5000),
                "size" => "0",
            ],
            "dailyHistory" => $dailyHistory,
            "monthlyHistory" => $monthlyHistory,
            "yearlyHistory" => $yearlyHistory,
        ];
    
        // Return the data as a JSON response
        return new JsonResponse($dataReport);
    }
}
