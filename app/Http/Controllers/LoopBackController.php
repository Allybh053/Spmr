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

class LoopBackController extends Controller
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

        return view('validationVerification.loopback.index',compact( 'serverName'));
    }

}
