<?php

namespace App\Http\Controllers;

use App\Models\smtp_relay_server;
use App\Models\SmtpRelayReqForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpseclib3\Net\SSH2;
use Illuminate\Support\Facades\Mail;
use App\Mail\mailApproved;
use App\Mail\mailReject;
use App\Models\User;

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
    public function view(string $id)
    {
        // Register mail relay that is pending
        $user = Auth::user();
        // $smtpMailServers = DB::table('smtp_relay_server')
        // ->join('smtp_relay_req_form', 'smtp_relay_server.form_id', '=', 'smtp_relay_req_form.form_id')
        // ->where('smtp_relay_server.form_id', $id)
        // ->select('smtp_relay_server.*','smtp_relay_req_form.smtp_port as smtp_port','smtp_relay_req_form.smtp_relay_auth_method as smtp_relay_auth_method','smtp_relay_req_form.smtp_relay_server as smtp_relay_server','smtp_relay_req_form.id as smtpid', 'smtp_relay_req_form.created_at as form_created_at', 'smtp_relay_req_form.types_req as types_req', 'smtp_relay_req_form.applicant_name as applicant_name','smtp_relay_req_form.mailbox_created as mailbox_created','smtp_relay_req_form.ticket_id as ticket_id')
        // ->get();

        $smtpMailServers = DB::table('smtp_relay_server')
        ->join('smtp_relay_req_form', 'smtp_relay_server.form_id', '=', 'smtp_relay_req_form.form_id')
        ->join('servers', 'servers.hostname', '=', 'smtp_relay_req_form.smtp_relay_server')
        ->where('smtp_relay_server.form_id', $id)
        ->select(
            'smtp_relay_server.*',
            'smtp_relay_req_form.owner_email_addr as owner_email_addr',
            'smtp_relay_req_form.smtp_port as smtp_port',
            'smtp_relay_req_form.smtp_relay_auth_method as smtp_relay_auth_method',
            'smtp_relay_req_form.smtp_relay_server as smtp_relay_server',
            'smtp_relay_req_form.id as smtpid',
            'smtp_relay_req_form.created_at as form_created_at',
            'smtp_relay_req_form.types_req as types_req',
            'smtp_relay_req_form.applicant_name as applicant_name',
            'smtp_relay_req_form.project_name as project_name',
            'smtp_relay_req_form.mailbox_created as mailbox_created',
            'smtp_relay_req_form.verifier_2_name as verifier_2_name',
            'smtp_relay_req_form.ticket_id as ticket_id',
            'servers.hostname as hostname' // Retrieve hostname as ipaddresshostname
        )
        ->get();

        return view('mailrelay.edit', compact('user','smtpMailServers'));
    }

    public function submitrejForm(Request $request){

        $comment = $request->input("comment");
        $user = Auth::user();

        $update = SmtpRelayReqForm::where('id', $request->input('smtpid'))->update(['comments' => $comment,'status'=>2,'implementation_name'=>"Rejected by ".$user->username]);

        $adminEmails = User::where('role_id', 3)->pluck('email')->toArray(); //role admin will receive the email and cc to the helpdesk
        Mail::to($adminEmails)
        ->cc($request->input('owner_email_addr'))
        ->send(new mailApproved($request));

        $successMessage = 'Form updated!';
        return response()->json(['message' => $successMessage]);

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
}
