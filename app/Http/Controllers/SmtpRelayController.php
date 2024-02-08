<?php

namespace App\Http\Controllers;

use App\Models\SmtpRelayReqForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SmtpRelayController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;

        return view('smtp_relay.smtp_relay_req', compact('user', 'role'));
    }

    public function new()
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;

        return view('smtp_relay.new_smtp_relay_req', compact('user', 'role'));
    }

    public function add(Request $request)
    {
        /* validation required field */
        /*$request->validate([
            'mailfrom_email_addr' => 'required|email',
            'owner_email_addr' => 'required|email',
        ], [
            'mailfrom_email_addr.required' => 'The MAILFROM Email Address field is required.',
            'mailfrom_email_addr.email' => 'The MAILFROM Email Address must be a valid email address.',
            'owner_email_addr.required' => 'The Email Address Owner System field is required.',
            'owner_email_addr.email' => 'The Email Address Owner System must be a valid email address.',
        ]);*/

        /* store data in table smtp_relay_req_form*/
        $smtpRelayReq = new SmtpRelayReqForm();
        $smtpRelayReq->form_id = Str::random(9);
        $smtpRelayReq->ticket_id = $request->input('inputTickNom');
        $smtpRelayReq->types_req = $request->input('inputTypesApp');
        $smtpRelayReq->project_name = $request->input('inputProjName');
        $smtpRelayReq->server_name = $request->input('inputServerName');
        $smtpRelayReq->ip_addr_server = $request->input('inputIpAddrServer');
        $smtpRelayReq->fqdn_system = $request->input('inputFqdnServer');
        $smtpRelayReq->mailfrom_email_addr = $request->input('inputMailfromEmailAddr');
        $smtpRelayReq->mailbox_created = $request->input('inputMailboxExistence');
        $smtpRelayReq->system_owner_name = $request->input('inputSystOwnerName');
        $smtpRelayReq->owner_email_addr = $request->input('inputEmailAddrOwnerSyst');
        $smtpRelayReq->owner_phone_number = $request->input('inputPhoneOwnerSyst');
        $smtpRelayReq->agencies = $request->input('inputAgencies');
        $smtpRelayReq->dept_div_unit = $request->input('inputDeptDivUnit');
        $smtpRelayReq->agencies_addr = $request->input('inputAgencyAddr');
        $smtpRelayReq->req_justifications = $request->input('inputJustApp');
        $smtpRelayReq->smtp_relay_auth_method = $request->input('inputSmtpRelayAuthMethod');
        $smtpRelayReq->smtp_relay_server = $request->input('inputSmtpRelayServer');
        $smtpRelayReq->smtp_port = $request->input('inputSmtpPort');
        $smtpRelayReq->smtp_username = $request->input('inputSmtpUsername');
        $smtpRelayReq->smtp_password = $request->input('inputSmtpPwd');
        $smtpRelayReq->est_no_system_users_per_day = $request->input('inputEstSystUsers');
        $smtpRelayReq->est_no_email_sent_from_system_users_per_day = $request->input('inputEstNumbEmailSent');
        $smtpRelayReq->applicant_name = $request->input('inputApplicantName');
        $smtpRelayReq->applicant_position = $request->input('inputApplicantPosition');
        $smtpRelayReq->applicant_dept_div = $request->input('inputApplicantDeptDiv');
        $smtpRelayReq->date_application = $request->input('inputAppDate');
        $smtpRelayReq->verifier_1_name = $request->input('inputVerifiedName1');
        $smtpRelayReq->verifier_1_position = $request->input('inputVerifiedPosition1');
        $smtpRelayReq->verifier_1_dept_div = $request->input('inputVerifiedDeptDiv1');
        $smtpRelayReq->date_verification_1 = $request->input('inputVerificationDate1');
        $smtpRelayReq->verifier_2_name = $request->input('inputVerifiedName2');
        $smtpRelayReq->verifier_2_position = $request->input('inputVerifiedPosition2');
        $smtpRelayReq->verifier_2_dept_div = $request->input('inputVerifiedDeptDiv2');
        $smtpRelayReq->date_verification_2 = $request->input('inputVerificationDate2');
        $smtpRelayReq->verifier_3_name = $request->input('inputVerifiedName3');
        $smtpRelayReq->verifier_3_position = $request->input('inputVerifiedPosition3');
        $smtpRelayReq->verifier_3_dept_div = $request->input('inputVerifiedDeptDiv3');
        $smtpRelayReq->date_verification_3 = $request->input('inputVerificationDate3');
        $smtpRelayReq->save();

        //return redirect()->back()->with('success', 'SMTP Relay Request successfully created');
        return redirect()->route('smtp_relay.index')->with('success', 'SMTP Relay Request successfully created');
    }

}
