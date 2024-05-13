<?php

namespace App\Http\Controllers;

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

class MainRelayController extends Controller
{
    public function index()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        $smtpMailServers = DB::table('smtp_relay_req_form')
        ->get();

        return view('mailrelay.index', compact('user','smtpMailServers'));
    }

    public function show()
    {
        // Register mail relay that is pending
        $user = Auth::user();
        $smtpMailServers = DB::table('smtp_relay_req_form')->where('status',0)
        ->groupBy('form_id','id')
        ->get();

        return view('mailrelay.register', compact('user','smtpMailServers'));
    }
    public function mailupstream(Request $request)
    {
        $ff = $request->serverconfig;

        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        $serverIds = RoleServer::whereIn('role_id', $user->roles->pluck('id')->all())
        ->pluck('server_id');

        $serverconfig = Server::where('status', 1)
        ->get();

        $serverdetail = DomainServer::where('server_id', $ff)->get();

         

        return view('mailrelay.mailupstream', compact('user','serverconfig','serverdetail'));
    }
    public function view(string $id, $type)
    {
        $user = Auth::user();

        $smtpreq = SmtpRelayReqForm::find($id);

        $smtpMailServers = smtp_relay_server::where('form_id',$smtpreq->form_id)
                ->where('types_req',$smtpreq->types_req)
                ->get();

        return view('mailrelay.edit', compact('user','smtpMailServers','smtpreq'));
    }

    public function papar($id, $type){
        $datasmtp = SmtpRelayReqForm::where('id', $id)->where('types_req', $type)->first();
        $details = smtp_relay_server::where('form_id', $datasmtp->form_id)
                    ->where('types_req', $datasmtp->types_req)
                    ->get();
        return view('smtp_relay.view',compact('datasmtp','details'));
    }

    public function submitrejForm(Request $request){

        $comment = $request->input("comment");
        $user = Auth::user();

        $update = SmtpRelayReqForm::where('id', $request->input('smtpid'))->update(['comments' => $comment,'status'=>2,'implementation_name'=>$user->username]);
        return response()->json(['message' => $update]);
        if ($update) {
        $adminEmails = User::where('role_id', 3)->pluck('email')->toArray(); //role admin will receive the email and cc to the helpdesk
        Mail::to($adminEmails)
        ->cc($request->input('owner_email_addr'))
        ->send(new mailReject($request));

        $successMessage = 'Form updated!';
        return response()->json(['message' => $successMessage]);
        }else{
        $Message = 'Error!';
        return response()->json(['message' => $Message]);
        }

    }

    public function submitappForm(Request $request)
    {
        $comment = $request->input("comment");
        $user = Auth::user();

        $update = SmtpRelayReqForm::where('id', $request->input('smtpid'))->update(['comments' => $comment,'status'=>1,'implementation_name'=>$user->username]);

        if ($update) {

            $adminEmails = User::where('role_id', 3)->pluck('email')->toArray(); //role admin will receive the email and cc to the helpdesk
            Mail::to($adminEmails)
            ->cc($request->input('owner_email_addr'))
            ->send(new mailApproved($request));

        $successMessage = 'Form updated!';
        return response()->json(['message' => $successMessage]);
        }else{
        $Message = 'Error!';
        return response()->json(['message' => $Message]);
        }
    }

    // public function executeCommand (Request $request)
    // {
    //     $server = '20.6.33.199';
    //     $username = 'pentadbir';
    //     $privateKey = storage_path('key/postmaster_key.pem');

    //     $ssh = new SSH2($server);
    //     if(!$ssh->login($username, $privateKey)){
    //         return response()->json(['output' => 'Login Failed!']);
    //     }

    //     $command = $request->input('command');
    //     $output = $ssh->exec($command);

    //     $ssh->disconnect();

    //     return response()->json(['output' => 'Login Failed!']);

    // }
}
