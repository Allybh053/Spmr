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
}
