<?php

namespace App\Http\Controllers;

use App\Models\RoleServer;
use App\Models\Server;
use App\Models\SmtpRelayReqForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;


class SearchIpController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $server_id = null, $server_type = null)
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
//        $smtpRequests = SmtpRelayReqForm::all(); // Replace with your actual model and query logic
//        $serverName = Server::pluck('name', 'id'); // Replace with actual logic for server names
//        $defaultServerId = config('app.default_server_id', null); // Example config for default server

        // Get server IDs based on user's roles
        $serverIds = RoleServer::whereIn('role_id', $user->roles->pluck('id')->all())
            ->pluck('server_id');

        $servers = Server::whereIn('id', $serverIds)
            ->where('status_cluster', 1)
            ->orderBy('id', 'asc')
            ->pluck('hostname', 'id');

        $serverconfig = Server::whereIn('master_id', $serverIds)
            ->where('status_cluster', 1)
            ->orderBy('id', 'asc')
            ->select('id', 'hostname', 'ipaddress', 'subhostname')
            ->get();

        $defaultServerId = $serverIds->min();

        $serverQuery = Server::whereIn('status_cluster', [1]);
        if ($server_id && $server_id !== 'all') {
            $serverQuery->where('id', $server_id);
        }

        $serverName = $serverQuery
            ->orderBy('id', 'asc')
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

        $smtpRequest = SmtpRelayReqForm::all();
        $form_id = $request->input('id');
        $types_req = $request->input('type');

        // Get SMTP request form data
        $datasmtp = SmtpRelayReqForm::where('form_id', $form_id)
            ->where('types_req', $types_req)
            ->first();

        $details = collect(); // Initialize empty collection
        if ($datasmtp) {
            // Retrieve details from the database
            $details = DB::table('smtp_relay_server') // Adjust table name if incorrect
            ->where('form_id', $datasmtp->form_id)
                ->where('types_req', $datasmtp->types_req)
                ->get();
        }

        // Log the activity
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
            'serverData',
            'serverconfig',
            'datasmtp',
            'details', // Pass details to the view
            'smtpRequest',
            'serverName',
            'defaultServerId'
        ));
    }

    public function getNewServerData(Request $request)
    {
        $serverName = $request->serverName;

        if ($serverName === 'all') {
            $servers = Server::all();
        } else {
            $servers = Server::find($serverName);
        }

        return response()->json($servers);
    }
    public function getSmtpRelayData(Request $request)
    {
        // Get the form_id and types_req from the request
        $form_id = $request->input('form_id');
        $types_req = $request->input('types_req');

        // Query to get data from smtp_relay_req_form or smtp_relay_server
        $datasmtp = SmtpRelayReqForm::where('form_id', $form_id)
            ->where('types_req', $types_req)
            ->first();

        $details = collect(); // Initialize empty collection
        if ($datasmtp) {
            // Retrieve details from smtp_relay_server
            $details = DB::table('smtp_relay_server')
                ->where('form_id', $datasmtp->form_id)
                ->where('types_req', $datasmtp->types_req)
                ->get();
        }

        // Return the details as JSON
        return response()->json($details);
    }

}
