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
use OwenIt\Auditing\Models\Audit;

class ManageServiceController extends Controller
{
    public function index(Request $request, $server_id = null, $server_type = null)
    {
        $user = Auth::user();

        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        $serverIds = RoleServer::whereIn('role_servers.role_id', $user->roles->pluck('id')->all())
        ->leftJoin('servers','servers.id','role_servers.server_id')
        ->where('servers.status',1)
        ->pluck('server_id');

        $defaultServerId = $serverIds->min();

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



    public function start(Request $request){
        $serverId = Server::where('hostname', $request->input('server'))->first();
        $oldValues = [];
        $oldValuesJson = json_encode($oldValues);
        $newValues=[
            'server' => $request->input('server'),
            'service_type' => $request->input('type'),
            'process' => 'start'
        ];

        $newValuesJson = json_encode($newValues);

        $audit = new Audit();
        $audit->auditable_type = Server::class;
        $audit->auditable_id = $serverId->id;
        $audit->old_values = $oldValuesJson; // Empty for creation
        $audit->new_values = $newValuesJson;
        $audit->user_id = Auth::user()->id;
        $audit->event = 'start server';
        $audit->url = request()->fullUrl();
        $audit->ip_address = request()->ip();
        $audit->user_agent = request()->userAgent();
        $audit->tags = json_encode(['manual-audit']); // Ensure tags are in JSON format
        $audit->save();
    }

    public function stop(Request $request){
        $serverId = Server::where('hostname', $request->input('server'))->first();
        $oldValues = [];
        $oldValuesJson = json_encode($oldValues);
        $newValues=[
            'server' => $request->input('server'),
            'service_type' => $request->input('type'),
            'process' => 'stop'
        ];
        $newValuesJson = json_encode($newValues);

        $audit = new Audit();
        $audit->auditable_type = Server::class;
        $audit->auditable_id = $serverId->id;
        $audit->old_values = $oldValuesJson; // Empty for creation
        $audit->new_values = $newValuesJson;
        $audit->user_id = Auth::user()->id;
        $audit->event = 'stop server';
        $audit->url = request()->fullUrl();
        $audit->ip_address = request()->ip();
        $audit->user_agent = request()->userAgent();
        $audit->tags = json_encode(['manual-audit']); // Ensure tags are in JSON format
        $audit->save();
    }

    public function restart(Request $request){
        $serverId = Server::where('hostname', $request->input('server'))->first();
        $oldValues = [];
        $oldValuesJson = json_encode($oldValues);
        $newValues=[
            'server' => $request->input('server'),
            'service_type' => $request->input('type'),
            'process' => 'restart'
        ];
        $newValuesJson = json_encode($newValues);

        $audit = new Audit();
        $audit->auditable_type = Server::class;
        $audit->auditable_id = $serverId->id;
        $audit->old_values = $oldValuesJson; // Empty for creation
        $audit->new_values = $newValuesJson;
        $audit->user_id = Auth::user()->id;
        $audit->event = 'restart server';
        $audit->url = request()->fullUrl();
        $audit->ip_address = request()->ip();
        $audit->user_agent = request()->userAgent();
        $audit->tags = json_encode(['manual-audit']); // Ensure tags are in JSON format
        $audit->save();
    }

}
