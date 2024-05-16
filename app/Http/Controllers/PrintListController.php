<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\RoleServer;
use App\Models\Server;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\ExportStatisticData;
use Illuminate\Support\Facades\Auth;

class PrintListController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        $serverIds = RoleServer::whereIn('role_id', $user->roles->pluck('id')->all())
        ->pluck('server_id');

        $defaultServerId = 1;

        // Filter servers based on the fetched IDs
        $serverName = Server::whereIn('id', $serverIds)
        ->where('status', 1)
        ->pluck('hostname', 'id');

        return view('validationVerification.printList.index',compact( 'serverName'));
    }

    public function validation(Request $request)
    {
        $serverName = $request->serverName;

        // Initialize an empty array to store data
        $dataReports = [];

        // Generate 20 or more sets of dummy data
        for ($i = 0; $i < 20; $i++) {
            $dataReport = [
                "No" => $i + 1,
                "Action" => '<button class="btn btn-primary">Manage</button>',
            ];

            // Add the generated data to the array
            $dataReports[] = $dataReport;
        }

        // Return the data as a JSON response
        return new JsonResponse(["data" => $dataReports]);
    }

}
