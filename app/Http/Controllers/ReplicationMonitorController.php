<?php

namespace App\Http\Controllers;

use App\Models\RoleServer;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class ReplicationMonitorController extends Controller
{
    /**
     * Show the replication monitoring dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;

        $serverIds = RoleServer::whereIn('role_servers.role_id', $user->roles->pluck('id')->all())
            ->leftJoin('servers','servers.id','role_servers.server_id')
            ->where('servers.status', [1, 0])
            ->pluck('server_id');
        //dd($serverIds);
        $defaultServerId = $serverIds->min();

        // Get server names
        $serverName = Server::whereIn('status_cluster', [1, 0])
            ->orderBy('id', 'asc')
            ->pluck('subhostname', 'id')
            ->map(function ($subhostname) {
                return explode('.', $subhostname)[0];
            });

        //dd($serverName);

        $serverconfig = Server::whereIn('master_id', $serverIds)
            ->orderBy('status_cluster', 'desc')
            ->pluck('subhostname', 'id');
        //dd($serverconfig);

        $dataservers  = Server::whereIn('status', [1, 0])
            // ->whereHas('subServers', function ($query) {
            //     $query->whereNotNull('master_id');
            // })
            ->with(['subServers' => function ($query) {
                $query->select('id', 'hostname', 'ipaddress', 'status_cluster', 'master_id', 'server_type', 'subhostname');
            }])
            ->orderBy('id')
            ->get();
        //dd($dataservers);

        $replicationData = [
            "serverName" => "--",
            "ipAddress" => "--",
            "replicationStatus" => "--",
            "syncStatus" => "--",
            "lag" => "--",
            "lastUpdated" => "--",
        ];
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Replication Monitoring',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'activity' => 'Replication Monitoring',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        // Query the Server table to get the hostname based on the selected value
        $allServer = Server::whereNull('deleted_at')->get();

        return view('replicationMonitoring.index', compact('user', 'role', 'serverName', 'defaultServerId', 'replicationData', 'serverconfig', 'dataservers', 'allServer'));
    }

    /**
     * Fetch and return server data for replication monitoring.
     */
    public function searchServerName(Request $request)
    {
        $serverName = $request->serverName;

        // Query the Server table to get the hostname based on the selected value
        $allServer = Server::whereNull('deleted_at')
            //->select('hostname', $serverName)
            ->select('hostname')
            ->get();

        $SubServer = Server::where('hostname',$serverName)
            ->whereNull('deleted_at')
            //->whereNotNull('subhostname')
            ->select('id', 'subhostname')
            //->pluck('hostname');
            ->get();
//        \Log::info("Searching for sub-servers of: " . $serverName);
//        \Log::info("Sub-servers found: " . json_encode($SubServer));

        return response()->json([
            'servers' => $allServer,
            'subServer' => $SubServer
        ]);
        //return response()->json(['servers' => $servers]);
    }
//    public function searchServerName(Request $request)
//    {
//        $serverName = $request->serverName;
//
//        $allServer = Server::whereNull('deleted_at')
//            ->select('hostname')
//            ->get();
//
//        $SubServer = Server::where('hostname', $serverName)
//            ->whereNull('deleted_at')
//            ->select('id', 'subhostname')
//            ->get();
//
//        $replicationData = Replication::where('hostname', $serverName)
//            ->select('hostname', 'node_type', 'replication_duration', 'last_replication', 'checksum', 'database_name')
//            ->get();
//        $servers = [];
//        foreach ($replicationData as $row) {
//            $hostname = $row['hostname'];
//
//            if (!isset($servers[$hostname])) {
//                $servers[$hostname] = [
//                    'hostname' => $hostname,
//                    'node_type' => $row['node_type'] ?? 'N/A',
//                    'replication_duration' => $row['replication_duration'] ?? 'N/A',
//                    'last_replication' => $row['last_replication'] ?? 'N/A',
//                    'checksums' => []
//                ];
//            }
//            if (!empty($row['checksum'])) {
//                $servers[$hostname]['checksums'][] = $row['checksum'] . '  ' . $row['database_name'];
//            }
//        }
//
//        $formattedServers = array_values($servers);
//
//        return response()->json([
//            'servers' => $allServer,
//            'subServer' => $SubServer,
//            'replicationData' => $formattedServers
//        ]);
//    }

}
