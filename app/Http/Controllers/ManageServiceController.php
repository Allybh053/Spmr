<?php

namespace App\Http\Controllers;

use App\Models\RoleServer;
use App\Models\Server;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use phpseclib3\Net\SSH2;

class ManageServiceController extends Controller
{
    public function index(Request $request, $server_id = null, $server_type = null)
    {
        $user = Auth::user();

        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        $serverIds = RoleServer::whereIn('role_id', $user->roles->pluck('id')->all())->pluck('server_id');

        $defaultServerId = 1;

        // Filter servers based on the fetched IDs
        $serverconfig =  Server::whereIn('id', $serverIds)
            ->where('status', 1)->orderBy('id', 'asc')
            ->select('id', 'hostname', 'ipaddress')
            ->get();

        $serverList = [];
        $listServer = Server::whereIn('id', $serverIds)->where('status', 1)->orderBy('id', 'asc')->get();
        foreach ($listServer as $server) {
            $serverList[] = [
                'hostname' => $server->hostname
            ];
        }

        //return view('manageService.index');
        return view('manageService.index',
            compact('user', 'role', 'serverconfig', 'serverList','defaultServerId'));
    }

}
