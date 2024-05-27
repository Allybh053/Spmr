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

class MailLogController extends Controller
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
        $serversArray = Server::leftJoin('role_servers as rs','servers.id','rs.server_id')
        ->where('rs.role_id',$user->roles->pluck('id')->first())
        ->where('servers.status',1)
        ->orderBy('servers.id', 'asc')
        ->get();
        $serverIds = RoleServer::whereIn('role_id', $user->roles->pluck('id')->all())
            ->pluck('server_id');

        $defaultServerId = $serverIds->min();

        $serverIp = Server::find($defaultServerId);

        // Filter servers based on the fetched IDs
        $serverconfig = Server::whereIn('id', $serverIds)
            ->where('status', 1)
            ->pluck('hostname', 'id');

        return view('mailLog.index',compact( 'user', 'role', 'serversArray', 'serverIds', 'defaultServerId', 'serverconfig','serverIp'));
    }
}
