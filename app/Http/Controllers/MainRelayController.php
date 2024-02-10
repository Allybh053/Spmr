<?php

namespace App\Http\Controllers;

use App\Models\smtp_relay_server;
use App\Models\SmtpRelayReqForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $smtpMailServers = DB::table ('smtp_relay_req_form')
        ->groupBy('ticket_id','id')
        ->get();
        return view('mailrelay.register', compact('user','smtpMailServers'));
    }
    public function view(string $id)
    {
        // Register mail relay that is pending
        $user = Auth::user();
        $smtpMailServers = DB::table('smtp_relay_server')
        ->join('smtp_relay_req_form', 'smtp_relay_server.ticket_id', '=', 'smtp_relay_req_form.ticket_id')
        ->where('smtp_relay_server.ticket_id', $id)
        ->select('smtp_relay_server.*', 'smtp_relay_req_form.created_at as form_created_at', 'smtp_relay_req_form.types_req as types_req', 'smtp_relay_req_form.applicant_name as applicant_name','smtp_relay_req_form.mailbox_created as mailbox_created')
        ->get();
        return view('mailrelay.edit', compact('user','smtpMailServers'));
    }
}
