<?php

namespace App\Http\Controllers;

use App\Models\Mailrouting;
use App\Models\smtp_relay_server;
use App\Models\SmtpRelayReqForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use phpseclib3\Net\SSH2;
use phpseclib\Net\SSH2;
use Illuminate\Support\Facades\Mail;
use App\Mail\mailApproved;
use App\Mail\mailReject;
use App\Models\User;
use App\Models\Server;
use App\Models\DomainServer;
use App\Models\RoleServer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\HistoryCopyAttachment;
use App\Models\ActivityLog;


class MainRelayController extends Controller
{
    public function index(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        $smtpMailServers = DB::table('smtp_relay_req_form')
        ->orderBy('status', 'desc')
        ->get();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'View Mail Relay',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'mail relay' => 'Retrieve related data for viewing mail relay page',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);


        return view('mailrelay.index', compact('user','smtpMailServers'));
    }
    public function checkIpAddress(Request $request)
    {
        // Get the IP address from the request
        $ipAddress = $request->input('ipAddress');
        $mailrelay = $request->input('mailrelay');
        $type_req = $request->input('type_req');

        $result = DB::select("
            SELECT srs.* FROM smtp_relay_req_form srrf
            JOIN smtp_relay_server srs ON srrf.id = srs.smtpid
            WHERE srrf.smtp_relay_server = ?
              AND srrf.status = 0
              AND srs.ip_addr_server = ?
              AND srs.types_req = ?
        ", [$mailrelay, $ipAddress, $type_req]);


        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Checking Ip Address',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'mail relay' => [
                    'ip_address' => $request->input('ipAddress'),
                    'mail_relay' => $request->input('mailrelay'),
                    'type_req' => $request->input('type_req'),
                    'output' => $result
                ],
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        if (count($result) > 0) {
            $form_id = $result[0]->form_id;
            return response()->json(['exists' => true, 'message' => 'IP address found in Pending Listing Request Form, Serial Number '.$form_id]);
        } else {
            return response()->json(['exists' => false, 'message' => 'IP address not found in the database']);
        }
    }


    public function show()
    {
        // Register mail relay that is pending
        $user = Auth::user();
        $smtpMailServers = DB::table('smtp_relay_req_form')->where('status',0)
        ->groupBy('form_id','id')
        ->orderBy('created_at', 'asc')
        ->get();

        return view('mailrelay.register', compact('user','smtpMailServers'));
    }

    public function view(string $id, $type)
    {
        $user = Auth::user();

        $smtpreq = SmtpRelayReqForm::find($id);
        $mailhostname = Server::where('hostname',$smtpreq->smtp_relay_server)->first();
        /*$subhostname = Server::where('master_id',$mailhostname->id)
                    ->where('status_cluster',1)
                    ->first();*/

        // Retrieve all servers with the given master_id and status_cluster equal to 1
        $servers = Server::where('master_id', $mailhostname->id)->where('status_cluster', 1)->get();

        // Initialize subhostname variable
        $subhostname = null;

        // Loop through the retrieved servers to find the one with status_cluster equal to 1
        foreach ($servers as $server) {
            if ($server->status_cluster == 1) {
                // If status_cluster is 1, set the subhostname and break the loop
                $subhostname = $server->subhostname;
                break;
            }
        }

        $smtpMailServers = smtp_relay_server::where('form_id',$smtpreq->form_id)
            ->where('types_req',$smtpreq->types_req)
            ->get();


        return view('mailrelay.edit', compact('user','smtpMailServers','smtpreq','subhostname'));
    }

    public function papar(Request $request,$id, $type){

        $datasmtp = SmtpRelayReqForm::select('smtp_relay_req_form.*','ministrys.name as ministry_name','agencies.name as agencies_name')
        ->leftJoin('ministrys','ministrys.id',DB::raw('CAST(smtp_relay_req_form.ministry AS bigint)'))
        ->leftJoin('agencies','agencies.id',DB::raw('CAST(smtp_relay_req_form.agencies AS bigint)'))
        ->where('smtp_relay_req_form.id', $id)
        ->where('smtp_relay_req_form.types_req', $type)
        ->first();

        $details = smtp_relay_server::where('form_id', $datasmtp->form_id)
                    ->where('types_req', $datasmtp->types_req)
                    ->get();
        $attachment = HistoryCopyAttachment::select('sm.upload_copy_attachment','history_copy_attachment.attachment_path');


        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'View Smtp Mail Relay',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'mail relay' => 'Retrieve related data for viewing smtp mail relay page',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        return view('smtp_relay.view',compact('datasmtp','details','attachment'));
    }

    public function submitrejForm(Request $request){

        $comment = $request->input("comment");
        $smtp_username = $request->input("smtp_username");
        $smtp_password = $request->input("smtp_password");
        $user = Auth::user();

        $update = SmtpRelayReqForm::where('id', $request->input('smtpid'))->update(['comments' => $comment,'status'=>2,'implementation_name'=>$user->username,'smtp_password'=>$smtp_password,'smtp_username'=>$smtp_username]);
        //return response()->json(['message' => $update]);
        if ($update) {
             // Retrieve admin emails
             //$adminEmails = User::where('role_id', 11)->pluck('email')->toArray();
             $adminEmails = User::where('role_id', [11, 9, 17])->where('enabled', 1)->pluck('email')->toArray();

            // Check if there are admin emails before sending the email
             if (!empty($adminEmails)) {
                 Mail::to($adminEmails)
                     //->cc($request->input('owner_email_addr'))
                     ->send(new mailReject($request, $user));
                 //dd($adminEmails);
             }
//             else {
                 // If no admin emails, send to emailadd from the request
                 /*Mail::to($request->input('owner_email_addr'))
                     ->send(new mailReject($request));*/
//             }
        // $adminEmails = User::where('role_id', 11)->pluck('email')->toArray(); //role admin will receive the email and cc to the helpdesk
        // Mail::to($adminEmails)
        // ->cc($request->input('owner_email_addr'))
        // ->send(new mailReject($request));

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Submit Reject Form',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'submit form' => 'Submit Reject Form',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        $successMessage = 'Form updated!';
        return response()->json(['message' => $successMessage]);
        }else{
        $Message = 'Error!';
        return response()->json(['message' => $Message]);
        }


    }

    public function submitappForm(Request $request)
    {
        $formData = $request->all();
        $smtp_relay_server = $request->input("smtp_relay_server");
        $access_list = $request->input("access_list");
        $types_req = $request->input("types_req");
        $form_id = $request->input("form_id");


        $smtp_username = $request->input("smtp_username");
        $smtp_password = $request->input("smtp_password");
        $smtp_port = $request->input("smtp_port");
        $comment = $request->input("comment");
        $user = Auth::user();
        $update = SmtpRelayReqForm::where('id', $request->input('smtpid'))->update(['comments' => $comment,'status'=>1,'implementation_name'=>$user->username,'smtp_password'=>$smtp_password,'smtp_username'=>$smtp_username]);

        // $update = SmtpRelayReqForm::where('id', $request->input('smtpid'))->update(['comments' => $comment,'status'=>1,'implementation_name'=>$user->username]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Submit Application Form',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'mail relay' => 'Submit Application Form',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        if ($update) {
            //$adminEmails = User::where('role_id', 11)->pluck('email')->toArray();
            $adminEmails = User::whereIn('role_id', [11, 9, 17])->where('enabled', 1)->pluck('email')->toArray();

             // Check if there are admin emails before sending the email
             if (!empty($adminEmails)) {
                 Mail::to($adminEmails)
                     //->cc($request->input('owner_email_addr'))
                     ->send(new mailApproved($request, $user));
             }
            //dd($adminEmails);
//             else {
                 // If no admin emails, send to emailadd from the request
                 /*Mail::to($request->input('owner_email_addr'))
                     ->send(new mailApproved($request));*/
//             }

            // $adminEmails = User::where('role_id', 11)->pluck('email')->toArray(); //role admin will receive the email and cc to the helpdesk
            // Mail::to($adminEmails)
            // ->cc($request->input('owner_email_addr'))
            // ->send(new mailApproved($request));

        $successMessage = 'Form updated';

        return response()->json(['message' => $successMessage, 'serverhost'=> $smtp_relay_server]);
        }else{
        $Message = 'Error 404!';
        return response()->json(['message' => $Message]);
        }
    }

    public function mailupstream(Request $request)
    {
        $ff = $request->serverconfig;

        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;

        $serverIds = RoleServer::whereIn('role_servers.role_id', $user->roles->pluck('id')->all())
        ->leftJoin('servers','servers.id','role_servers.server_id')
        ->whereNull('servers.subhostname')
        ->pluck('server_id');

        //$serverconfig = Server::where('status', 1)->get();

        //$serverdetail = DomainServer::where('server_id', $ff)->get();

        $defaultServerId = $serverIds->min();

        // Filter servers based on the fetched IDs
        $serverconfig = Server::whereNull('subhostname')->orderBy('id', 'asc')
            ->pluck('hostname', 'id');


            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity_type' => 'View Mail Upstream',
                'action' => $request->path(),
                'url' => url()->full(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'input' => json_encode([
                    'mail upstream' => 'Retrieve data for viewing Mail Upstream Page',
                    'request' => $request->except(['password']),
                ]),
                'user_agent' => $request->userAgent(),
            ]);

        return view('mailrelay.mailupstream', compact('user','serverconfig', 'defaultServerId'));
    }

    public function api(Request $request)
    {
        // Retrieve serverId from the request
        $serverId = $request->serverId;

        return $serverId;
    }

    public function search(Request $request)
    {

        // Retrieve other inputs from the request
        $serverIds = $request->input('server');

        // You can adjust the search logic based on the selected calendar type and agency here
        $serverMailRelay = Server::where('id',$serverIds)->first();

        $serverName = $serverMailRelay->hostname;

        $queryMailRoute = Mailrouting::where('server', $serverName);

        // Execute the query
        $valDataMailRoute = $queryMailRoute->get();
        $totalDomActive = Mailrouting::where('server', $serverName)->where('status', true)->count();
        $totalDomDeactive = Mailrouting::where('server', $serverName)->where('status', false)->count();
        $totalDomAll = Mailrouting::where('server', $serverName)->count();


        return response()->json([
            'totalDomainActivated' => $totalDomActive,
            'totalDomainDeactivated' => $totalDomDeactive,
            'totalDomainRegistered' => $totalDomAll,
            'dataMailRoute' => $valDataMailRoute,
        ]);
    }

    public function checkServerStatus(Request $request)
    {
        // Get the sub_smtp_relay_server value from the request
        $subSmtpRelayServer = $request->input('sub_smtp_relay_server');

        // Check the status of the server in the database
        $server = Server::where('subhostname', $subSmtpRelayServer)->first();

        if ($server && $server->status_cluster == 1) {
            // Server is active
            return response()->json(['status' => 1]);
        } else {
            // Server is inactive
            return response()->json(['status' => 0]);
        }
    }


}
