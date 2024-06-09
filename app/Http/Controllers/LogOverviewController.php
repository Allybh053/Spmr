<?php

namespace App\Http\Controllers;

use App\Models\RoleServer;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class LogOverviewController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        $serverIds = RoleServer::whereIn('role_servers.role_id', $user->roles->pluck('id')->all())
        ->leftJoin('servers','servers.id','role_servers.server_id')
        ->whereNotNull('servers.status_cluster')
        ->whereIn('servers.status_cluster',[1,0])
        ->pluck('server_id');

        $defaultServerId = $serverIds->min();

        // Filter servers based on the fetched IDs
        $serverName = Server::whereIn('status_cluster', [1,0])
            ->orderBy('id', 'asc')
            ->pluck('subhostname', 'id');

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

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'View Server Overview',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'activity' => 'View Server Overview',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        return view('log.index', compact( 'user', 'role', 'serverName', 'defaultServerId', 'serverData'));
    }


    public function getNewServerData(Request $request)
    {

        $serverName = Server::find($request->serverName);

        return response()->json($serverName);

    }

    private function dummyData($serverName)
    {
        $nameServer = Server::where('id', $serverName)->pluck('subhostname');
        $ipAddrServer = Server::where('master_id', $serverName)->pluck('ipaddress');

        dd($nameServer);

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
