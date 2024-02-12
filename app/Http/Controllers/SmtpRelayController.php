<?php

namespace App\Http\Controllers;

use App\Models\SmtpRelayReqForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SmtpRelayController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        return view('smtp_relay.index', compact('user', 'role'));
    }

    public function create() 
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;

        $applicationType = [
            "0" => "New Request",
            "1" => "Service Termination",
            "2" => "Agency Onboarding",
        ];
        $mailbox =[
           "0" => "Mailbox Available",
           "1" => "No Existing Mailbox",
        ];
        $smtpAuth =[
            "0" => "SMTP IP Based Authentication Relay",
            "1" => "SMTP Username/Password Authentication Relay",
         ];
         $smtpRelayServer =[
            "0" => "postmaster.mygovuc.gov.my",
            "1" => "postmaster2.mygovuc.gov.my",
            "2" => "postmaster3.mygovuc.gov.my",
         ];
         $smtpPort =[
            "0" => "25/tcp",
            "1" => "465/tcp",
            "2" => "587/tcp",
         ];
         $department =[
            "0" => "department 1",
            "1" => "department 2",
            "2" => "department 3",
         ];
         $position =[
            "0" => "position 1",
            "1" => "position 2",
            "2" => "position 3",
         ];
         $agency =[
            "0" => "agency 1",
            "1" => "agency 2",
            "2" => "agency 3",
         ];

        return view('smtp_relay.form', 
        compact(
            'user', 
            'role',
            'applicationType',
            'mailbox',
            'smtpAuth',
            'smtpRelayServer',
            'smtpPort',
            'department',
            'position',
            'agency'));
    }

    public function store(Request $request){

        // $messages = [
            // 'mailfrom_email_addr.required' => 'The MAILFROM Email Address field is required.',
            // 'mailfrom_email_addr.email' => 'The MAILFROM Email Address must be a valid email address.',
            // 'owner_email_addr.required' => 'The Email Address Owner System field is required.',
            // 'owner_email_addr.email' => 'The Email Address Owner System must be a valid email address.',
        // ];

        // $validation = [
            // 'mailfrom_email_addr' => 'required|email',
            // 'owner_email_addr' => 'required|email',
        // ];

        // $request->validate($validation, $messages);

        // Validate the request data
        // $validatedData = $request->validate([
        //     'server_name.*' => 'required',
        //     'ip_server.*' => 'required',
        //     'fqdn_system.*' => 'required',
        // ]);

        // // Process the form submission
        // foreach ($validatedData['server_name'] as $key => $value) {
        //     $applicantInfo = new ApplicantInfo();
        //     $applicantInfo->server_name = $validatedData['server_name'][$key];
        //     $applicantInfo->ip_server = $validatedData['ip_server'][$key];
        //     $applicantInfo->fqdn_system = $validatedData['fqdn_system'][$key];
        //     $applicantInfo->save();
        // }

        try {
            DB::beginTransaction();

            $smtpRelayReq = new SmtpRelayReqForm();
            $smtpRelayReq->form_id = Str::random(9);
            $smtpRelayReq->ticket_id = $request->ticket_no;
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

            DB::commit();
            return response()->json([
                'success' => '1',
                'redirect' => route('smtp_relay.smtp_relay_req'),
                'message' => 'Application Successfully Saved'
            ]);
        }catch (\Throwable $e) {
            DB::rollback();

            return response()->json([
                'error' => '1',
                'message' => 'Application Unsuccessfully Saved'.$e->getMessage()
            ], 400);
        }
    }

    public function edit($id)
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;

        $applicationType = [
            "0" => "New Request",
            "1" => "Service Termination",
            "2" => "Agency Onboarding",
        ];
        $mailbox =[
           "0" => "Mailbox Available",
           "1" => "No Existing Mailbox",
        ];
        $smtpAuth =[
            "0" => "SMTP IP Based Authentication Relay",
            "1" => "SMTP Username/Password Authentication Relay",
         ];
         $smtpRelayServer =[
            "0" => "postmaster.mygovuc.gov.my",
            "1" => "postmaster2.mygovuc.gov.my",
            "2" => "postmaster3.mygovuc.gov.my",
         ];
         $smtpPort =[
            "0" => "25/tcp",
            "1" => "465/tcp",
            "2" => "587/tcp",
         ];
         $department =[
            "0" => "department 1",
            "1" => "department 2",
            "2" => "department 3",
         ];
         $position =[
            "0" => "position 1",
            "1" => "position 2",
            "2" => "position 3",
         ];
         $agency =[
            "0" => "agency 1",
            "1" => "agency 2",
            "2" => "agency 3",
         ];
        $applicantInfo=null;
        // $applicantInfo = ApplicantInfo::find($id);
        return view('smtp_relay.form',
        compact( 'user', 
            'role',
            'applicationType',
            'mailbox',
            'smtpAuth',
            'smtpRelayServer',
            'smtpPort',
            'department',
            'position',
            'agency',
            'applicantInfo'));
    }

    public function update(Request $request,$id)
    {
        // $messages = [
        //     'companyName.required' => 'Company name field is required.',
        //     'address1.required' => 'Address 1 field is required.',
        //     'postcode.required' => 'Postcode field is required.',
        //     'city.required' => 'City field is required.',
        //     'state.required' => 'Please Select State.',
        //     'phone.max' => 'Phone Number field max is 15 number.',
        //     'email.required' => 'Email field is required.',
        // ];

        // $validation = [
        //     'companyName' => 'required',
        //     'address1' => 'required',
        //     'postcode' => 'required',
        //     'city' => 'required',
        //     'state' => 'required',
        //     'phone' => 'max:15',
        //     'email' => 'required',
        // ];

        // $request->validate($validation, $messages);

        try {
            DB::beginTransaction();

            // $application = ApplicantInfo::find($id);

            // $application->save();

            DB::commit();
            return response()->json([
                'success' => '1',
                'redirect' => route('smtp_relay.smtp_relay_req'),
                'message' => 'Application Successfully Updated'
            ]);
        }catch (\Throwable $e) {
            DB::rollback();

            return response()->json([
                'error' => '1',
                'message' => 'Application Unsuccessfully Updated'.$e->getMessage()
            ], 400);
        }
    }

    public function datatable()
    {
        // $application = ApplicantInfo::get();

           // Dummy data array
            $application = [
                [
                    'id' => 1,
                    'ticket_no' => 'SR-123456',
                    'types_application' => 'New Application',
                    'project_name' => 'MyLAND PROD',
                    'server_name' => 'JKPTG_ApacheWeb1_P',
                    'ip_address' => '10.10.10.101',
                    'fqdn_system' => 'myland.gov.my',
                    'date_created' => '20-06-2024',
                    'date_updated' => '20-06-2024',
                    'status' => 'Done Created',
                ],
                // Add more rows as needed
            ];

            return datatables()->of($application)
            ->addIndexColumn()
            ->addColumn('ticket_number', function ($row) {
                return $row['ticket_no'];
            })
            ->addColumn('types_application', function ($row) {
                return $row['types_application'];
            })
            ->addColumn('project_name', function ($row) {
                return $row['project_name'];
            })
            ->addColumn('server_name', function ($row) {
                return $row['server_name'];
            })
            ->addColumn('ip_address_server', function ($row) {
                return $row['ip_address'];
            })
            ->addColumn('fqdn_system', function ($row) {
                return $row['fqdn_system'];
            })
            ->addColumn('date_created', function ($row) {
                return $row['date_created'];
            })
            ->addColumn('date_updated', function ($row) {
                return $row['date_updated'];
            })
            ->addColumn('status', function ($row) {
                return $row['status'];
            })
            ->addColumn('action', function ($row) {
                $data = '<a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="'.route('smtp_relay.edit',[$row['id']]).'"><i class="fas fa-edit text-primary"></i></a>
                <a type="button" class="btn btn-datatable btn-icon btn-transparent-dark" id="delete" data-id="'.$row['id'].'" data-url="'.route('smtp_relay.delete',[$row['id']]).'">
                <i class="fas fa-trash-alt text-danger"></i>
                </a>';
            
            return $data;
            
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function delete(Request $request)
    {
        // $application = ApplicantInfo::find($id);
        // $application    ->  updated_by = Auth::user()->id;
        // $application    ->  delete();

        return response()->json(['success' => '1', 'redirect' => route('smtp_relay.smtp_relay_req'), 'message' => 'Application successfully deleted']);
    }

}
