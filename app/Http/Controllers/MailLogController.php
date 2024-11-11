<?php

namespace App\Http\Controllers;

use App\Models\RoleServer;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\ExportStatisticData;
use App\Models\ActivityLog;

class MailLogController extends Controller
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
        $serversArray = Server::leftJoin('role_servers as rs','servers.master_id','rs.server_id')
        ->where('rs.role_id',$user->roles->pluck('id')->first())
        ->whereIn('servers.status_cluster',[1,0])
        ->orderBy('servers.master_id', 'asc')
        ->get();

        $serverIds = RoleServer::whereIn('role_servers.role_id', $user->roles->pluck('id')->all())
        ->leftJoin('servers','servers.master_id','role_servers.server_id')
        ->whereIn('servers.status_cluster',[1,0])
        ->pluck('server_id');

        $defaultServerId = $serverIds->min();


        $serverIp = Server::find($defaultServerId);

        // Filter servers based on the fetched IDs
        $serverconfig = Server::whereIn('master_id', $serverIds)
            ->orderBy('status_cluster', 'desc')
            ->pluck('subhostname', 'id');

            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity_type' => 'View MailLog',
                'action' => $request->path(),
                'url' => url()->full(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'input' => json_encode([
                    'maillog' => 'Retrieve related data for viewing maillog page',
                    'request' => $request->except(['password']),
                ]),
                'user_agent' => $request->userAgent(),
            ]);

        return view('mailLog.index',compact( 'user', 'role', 'serversArray', 'serverIds', 'defaultServerId', 'serverconfig','serverIp'));
    }
}
