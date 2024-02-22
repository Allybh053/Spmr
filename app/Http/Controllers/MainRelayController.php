<?php

namespace App\Http\Controllers;

use App\Models\smtp_relay_server;
use App\Models\SmtpRelayReqForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpseclib3\Net\SSH2;

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
        $smtpMailServers = DB::table('smtp_relay_req_form')
        ->groupBy('form_id','id')
        ->get();

        return view('mailrelay.register', compact('user','smtpMailServers'));
    }
    public function view(string $id)
    {
        // Register mail relay that is pending
        $user = Auth::user();
        $smtpMailServers = DB::table('smtp_relay_server')
        ->join('smtp_relay_req_form', 'smtp_relay_server.form_id', '=', 'smtp_relay_req_form.form_id')
        ->where('smtp_relay_server.form_id', $id)
        ->select('smtp_relay_server.*','smtp_relay_req_form.id as smtpid', 'smtp_relay_req_form.created_at as form_created_at', 'smtp_relay_req_form.types_req as types_req', 'smtp_relay_req_form.applicant_name as applicant_name','smtp_relay_req_form.mailbox_created as mailbox_created','smtp_relay_req_form.ticket_id as ticket_id')
        ->get(); 

        return view('mailrelay.edit', compact('user','smtpMailServers'));
    }

    public function connect()
    {
        $host = '20.6.33.199';
        $port = 22;
        $username = 'pentadbir';
        $privateKeyPath = storage_path('storage/postmaster_key.pem'); // Adjust the path to your key file

        $ssh = new SSH2($host, $port);
        $key = file_get_contents($privateKeyPath);

        if (!$ssh->login($username, $key)) {
            return response()->json(['error' => 'SSH login failed']);
        }

        $output = $ssh->exec('your_ssh_command');

        return response()->json(['output' => $output]);
    }

    public function updatestatus(){

    }
}
