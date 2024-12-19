<?php

namespace App\Http\Controllers;

use App\Models\RoleServer;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class SearchIpController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
//    public function index(Request $request)
    public function index(Request $request, $server_id = null, $server_type = null)
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

        return view('searchIp.index', compact(
            'user',
            'role',
            'serverName',
            'defaultServerId',
            'serverData'
        ));
    }

    public function getNewServerData(Request $request)
    {
        $serverName = Server::find($request->serverName);
        return response()->json($serverName);
    }

    /*public function showServerConfig()
    {
        // Example server configuration array
        $serverconfig = [
            ['subhostname' => 'postmstr1'],
            ['subhostname' => 'postmstr2'],
            ['subhostname' => 'postmstr3'],
        ];
        return view('server-config', compact('serverconfig'));
    }*/

    /* public function api(Request $request)
     {
         $serverId = $request->serverName;

         return new JsonResponse($serverId);
     }*/

    /*public function checkIP(Request $request)
    {
        $ips = $request->input('ips');
        $serverId = $request->input('server');

        if (!$ips || !is_array($ips)) {
            return response()->json(['error' => 'Invalid IP list provided.'], 400);
        }

        // Query database to check IPs
        $results = DB::table('ip_addresses') // Replace with your table name
        ->select('ip', 'server_id')     // Adjust columns as needed
        ->whereIn('ip', $ips)
            ->when($serverId !== 'All', function ($query) use ($serverId) {
                $query->where('server_id', $serverId);
            })
            ->get();

        // Map results
        $output = [];
        foreach ($ips as $ip) {
            $output[] = [
                'ip' => $ip,
                'exists' => $results->contains('ip', $ip),
                'server' => $results->firstWhere('ip', $ip)->server_id ?? null
            ];
        }
        return response()->json($results);
    }*/
}
