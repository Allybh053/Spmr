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
use OwenIt\Auditing\Models\Audit;
use App\Models\ActivityLog;

class LoopBackController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $serverIds = RoleServer::whereIn('role_servers.role_id', $user->roles->pluck('id')->all())
        ->leftJoin('servers','servers.id','role_servers.server_id')
        ->where('servers.status',1)
        ->pluck('server_id');

        $defaultServerId = $serverIds->min();

        // Filter servers based on the fetched IDs
        $serverName = Server::whereIn('id', $serverIds)
        ->where('status', 1)
        ->pluck('hostname', 'id');

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'View LoopBack',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'loopback' => 'Retrieve related data for viewing loopback page',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        return view('validationVerification.loopback.index',compact( 'serverName'));
    }

    public function api(Request $request)
    {
        // Retrieve serverId from the request
        $serverId = $request->serverId;

        return $serverId;
    }

    public function smtpTest(Request $request) {
        $serverIds = $request->input('serverName');
        $mailForm = $request->input('mail_from');
        $recTo = $request->input('rec_to');

        $serverMailRelay = Server::find($serverIds);
        $serverName = $serverMailRelay->hostname;
        $serverIp= $serverMailRelay->ipaddress;

          // Prepare old and new values for auditing
        $oldValues = []; // Assuming there are no old values to track for this action
        $newValues = [
            'server' => $serverName,
            'ipaddress' => $serverIp,
            'mail_from' => $mailForm,
            'rec_to' => $recTo,
        ];

        // Perform the audit logging
        $audit = new Audit();
        $audit->auditable_type = Server::class;
        $audit->auditable_id = $serverMailRelay->id;
        $audit->old_values = json_encode($oldValues); // Empty for this operation
        $audit->new_values = json_encode($newValues);
        $audit->user_id = Auth::user()->id; // Ensure the user is authenticated
        $audit->event = 'Loopback SMTP Test'; // Specify the event
        $audit->url = $request->fullUrl();
        $audit->ip_address = $request->ip();
        $audit->user_agent = $request->userAgent();
        $audit->tags = json_encode(['manual-audit']);
        $audit->save();

        return response()->json([
            'data' => $serverName,
            'MailFrom' => $mailForm,
            'RecepientTo' => $recTo,
            'ipaddress' => $serverIp
        ]);

    }
 }
