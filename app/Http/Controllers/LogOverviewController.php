<?php

namespace App\Http\Controllers;

use App\Models\RoleServer;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogOverviewController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        $serverIds = RoleServer::whereIn('role_id', $user->roles->pluck('id')->all())
            ->pluck('server_id');

        $defaultServerId = 1;

        // Filter servers based on the fetched IDs
        $serverName = Server::whereIn('id', $serverIds)
            ->where('status', 1)->orderBy('id', 'asc')
            ->pluck('hostname', 'id');

        $serverData = [
            "serverName" => "--",
            "ipAddress" => "--",
            "serverStatus" => "--",
            "serviceStatus" => "--",
            "sasl" => "--",
            "smtpdLost" => "--",
            "smtpdTimeOut" => "--",
            "bounced" => "--",
        ];

        return view('log.index',
            compact( 'user', 'role', 'serverName', 'defaultServerId', 'serverData'));
    }


    public function getNewServerData(Request $request)
    {

        $serverName = Server::find($request->serverName);

        return response()->json($serverName);

    }

    private function dummyData($serverName)
    {
        $nameServer = Server::where('id', $serverName)->pluck('hostname');
        $ipAddrServer = Server::where('id', $serverName)->pluck('ipaddress');

        //dd($nameServer);

        if ($serverName != null) {
            return [
                "serverName" => $nameServer,
                "ipAddress" => $ipAddrServer,
            ];
        } else {
            // Handle other server names if needed
            return [];
        }
    }


}
