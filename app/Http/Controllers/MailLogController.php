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
        $serversArray = Server::leftJoin('role_servers as rs','servers.id','rs.server_id')
        ->where('rs.role_id',$user->roles->pluck('id')->first())
        ->where('servers.status',1)
        ->orderBy('servers.id', 'asc')
        ->get();
        $serverIds = RoleServer::whereIn('role_servers.role_id', $user->roles->pluck('id')->all())
        ->leftJoin('servers','servers.id','role_servers.server_id')
        ->where('servers.status',1)
        ->pluck('server_id');

        $defaultServerId = $serverIds->min();

        $serverIp = Server::find($defaultServerId);

        // Filter servers based on the fetched IDs
        $serverconfig = Server::whereIn('id', $serverIds)
            ->where('status', 1)
            ->pluck('hostname', 'id');

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
