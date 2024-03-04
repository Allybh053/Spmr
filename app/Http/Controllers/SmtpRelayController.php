<?php

namespace App\Http\Controllers;

use App\Models\SmtpRelayReqForm;
use App\Models\smtp_relay_server;
use Carbon\Carbon;
use App\Models\Department;
use App\Models\Position;
use App\Models\Server;
use App\Models\DomainMinistryAgencies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SmtpRelayController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        $agency = [];
        return view('smtp_relay.index', compact('user', 'role','agency'));
    }

    public function search(Request $request)
    {
        $input1 = $request->input('agency'); // Assuming 'agency' is the name of the select input for Kementerian/Agensi/SUK
        $input2Type = $request->input('calendarType');

        // Generate Faker data based on the selected calendar type and agency
        $faker = Faker::create();
        $totalApplications = $faker->numberBetween(100, 500);
        $totalIP = $faker->numberBetween(200, 1000);
        $totalDeactivated = $faker->numberBetween(5, 50);

        // You can adjust the search logic based on the selected calendar type and agency here

        return response()->json([
            'totalApplications' => $totalApplications,
            'totalIP' => $totalIP,
            'totalDeactivated' => $totalDeactivated
        ]);
    }

    /*public function create()
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;

        $applicationType = [
            "1" => "New Application",
            "2" => "Service Termination",
            "3" => "Agency Onboarding",
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
    }*/

    /*public function store(Request $request){

        try {
            DB::beginTransaction();
            $smtpRelayReq = new SmtpRelayReqForm();
            $smtpRelayReq->form_id = Str::random(9);
            $smtpRelayReq->ticket_id = $request->ticket_no;
            $smtpRelayReq->types_req = $request->type;
            $smtpRelayReq->project_name = $request->project_name;
            $smtpRelayReq->server_name = is_array($request->server_name) ? json_encode($request->server_name) : $request->server_name;
            $smtpRelayReq->ip_addr_server = is_array($request->ip_server) ? json_encode($request->ip_server) : $request->ip_server;
            $smtpRelayReq->fqdn_system = is_array($request->fqdn_system) ? json_encode($request->fqdn_system) : $request->fqdn_system;
            $smtpRelayReq->mailfrom_email_addr = $request->mail_add;
            $smtpRelayReq->mailbox_created = $request->mailbox;
            $smtpRelayReq->system_owner_name = $request->sys_owner;
            $smtpRelayReq->owner_email_addr = $request->email_add_owner;
            $smtpRelayReq->owner_phone_number = $request->phone_owner;
            $smtpRelayReq->agencies = $request->agency;
            $smtpRelayReq->dept_div_unit = $request->department;
            $smtpRelayReq->agencies_addr = $request->agency_Addr;
            $smtpRelayReq->req_justifications = $request->justify_app;
            $smtpRelayReq->smtp_relay_auth_method = $request->smtpAuth;
            $smtpRelayReq->smtp_relay_server = $request->smtpRelayServer;
            $smtpRelayReq->smtp_port = $request->smtpPort;
            $smtpRelayReq->smtp_username = $request->smtp_username;
            $smtpRelayReq->smtp_password = $request->smtp_pass;
            $smtpRelayReq->est_no_system_users_per_day = $request->number_user;
            $smtpRelayReq->est_no_email_sent_from_system_users_per_day = $request->number_system;
            $smtpRelayReq->applicant_name = $request->applicant_name;
            $smtpRelayReq->applicant_position = $request->applicantPosition;
            $smtpRelayReq->applicant_dept_div = $request->applicantDepartment;
            //$smtpRelayReq->date_application = Carbon::parse($request->applicant_date)->format('Y-m-d H:i:s');
            $smtpRelayReq->date_application = $request->applicant_date;
            $smtpRelayReq->verifier_1_name = $request->verifier_name_1;
            $smtpRelayReq->verifier_1_position = $request->verifierPosition;
            $smtpRelayReq->verifier_1_dept_div = $request->verifierDepartment;
            //$smtpRelayReq->date_verification_1 = Carbon::parse($request->verifier_date)->format('Y-m-d H:i:s');
            $smtpRelayReq->date_verification_1 = $request->verifier_date;
            $smtpRelayReq->verifier_2_name = $request->verifier_name_2;
            $smtpRelayReq->verifier_2_position = $request->verifierPosition2;
            $smtpRelayReq->verifier_2_dept_div = $request->verifierDepartment2;
            //$smtpRelayReq->date_verification_2 = Carbon::parse($request->verifier_date_2)->format('Y-m-d H:i:s');
            $smtpRelayReq->date_verification_2 = $request->verifier_date_2;
            $smtpRelayReq->verifier_3_name = $request->verifier_name_3;
            $smtpRelayReq->verifier_3_position = $request->verifierPosition3;
            $smtpRelayReq->verifier_3_dept_div = $request->verifierDepartment3;
            //$smtpRelayReq->date_verification_3 = Carbon::parse($request->verifier_date_3)->format('Y-m-d H:i:s');
            $smtpRelayReq->date_verification_3 = $request->verifier_date_3;
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
    }*/

    /*public function edit($id)
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;

        $applicationType = [
            "1" => "New Application",
            "2" => "Service Termination",
            "3" => "Agency Onboarding",
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

        //$applicantInfo=null;
        $applicantInfo = SmtpRelayReqForm::find($id);

        $serverNames = json_decode($applicantInfo['server_name'], true);
        if (is_array($serverNames)) {
            $applicantInfo->server_name = implode(", ", $serverNames);
        }
        $ipAddrSvr = json_decode($applicantInfo['ip_addr_server'], true);
        if (is_array($ipAddrSvr)) {
            $applicantInfo->ip_addr_server = implode(", ", $ipAddrSvr);
        }
        $fqdnSystem = json_decode($applicantInfo['fqdn_system'], true);
        if (is_array($fqdnSystem)) {
            $applicantInfo->fqdn_system = implode(", ", $fqdnSystem);
        }
        $applicantInfo->date_application = Carbon::parse($applicantInfo->date_application)->format('d/m/Y');
        $applicantInfo->date_verification_1 = Carbon::parse($applicantInfo->date_verification_1)->format('d/m/Y');
        $applicantInfo->date_verification_2 = Carbon::parse($applicantInfo->date_verification_2)->format('d/m/Y');
        $applicantInfo->date_verification_3 = Carbon::parse($applicantInfo->date_verification_3)->format('d/m/Y');


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
    }*/

    /*public function update(Request $request,$id)
    {
        try {
            DB::beginTransaction();

            $application = SmtpRelayReqForm::find($id);
            $application->ticket_id = $request->ticket_no;
            $application->types_req = $request->type;
            $application->project_name = $request->project_name;


            $application->mailfrom_email_addr = $request->mail_add;
            $application->mailbox_created = $request->mailbox;
            $application->system_owner_name = $request->sys_owner;
            $application->owner_email_addr = $request->email_add_owner;
            $application->owner_phone_number = $request->phone_owner;
            $application->agencies = $request->agency;
            $application->dept_div_unit = $request->department;
            $application->agencies_addr = $request->agency_Addr;
            $application->req_justifications = $request->justify_app;
            $application->smtp_relay_auth_method = $request->smtpAuth;
            $application->smtp_relay_server = $request->smtpRelayServer;
            $application->smtp_port = $request->smtpPort;
            $application->smtp_username = $request->smtp_username;
            $application->smtp_password = $request->smtp_pass;
            $application->est_no_system_users_per_day = $request->number_user;
            $application->est_no_email_sent_from_system_users_per_day = $request->number_system;
            $application->applicant_name = $request->applicant_name;
            $application->applicant_position = $request->applicantPosition;
            $application->applicant_dept_div = $request->applicantDepartment;
            //$application->date_application = Carbon::parse($request->applicant_date)->format('Y-m-d H:i:s');
            $application->date_application = $request->applicant_date;
            $application->verifier_1_name = $request->verifier_name_1;
            $application->verifier_1_position = $request->verifierPosition;
            $application->verifier_1_dept_div = $request->verifierDepartment;
            //$application->date_verification_1 = Carbon::parse($request->verifier_date)->format('Y-m-d H:i:s');
            $application->date_verification_1 = $request->verifier_date;
            $application->verifier_2_name = $request->verifier_name_2;
            $application->verifier_2_position = $request->verifierPosition2;
            $application->verifier_2_dept_div = $request->verifierDepartment2;
            //$application->date_verification_2 = Carbon::parse($request->verifier_date_2)->format('Y-m-d H:i:s');
            $application->date_verification_2 = $request->verifier_date_2;
            $application->verifier_3_name = $request->verifier_name_3;
            $application->verifier_3_position = $request->verifierPosition3;
            $application->verifier_3_dept_div = $request->verifierDepartment3;
            //$application->date_verification_3 = Carbon::parse($request->verifier_date_3)->format('Y-m-d H:i:s');
            $application->date_verification_3 = $request->verifier_date_3;
            $application->save();

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
    }*/

    public function datatable(Request $request)
    {
          // Get search parameters
    $agency = $request->input('agency');
    $calendarType = $request->input('calendarType');

      // Start building the query
      $query = SmtpRelayReqForm::query();

      // Apply filters based on search parameters
      if (!empty($agency)) {
          $query->where('agency_column', $agency); // Replace 'agency_column' with the actual column name for agency in your database table
      }

      // Execute the query
      $application = $query->get();

            return datatables()->of($application)
            ->addIndexColumn()
            ->addColumn('form_id', function ($row) {
                return $row['form_id'];
            })
            ->addColumn('ticket_id', function ($row) {
                return $row['ticket_id'];
            })
            /*->addColumn('types_req', function ($row) {
                return $row['types_req'];
            })*/
            ->addColumn('types_req', function ($row) {
                switch ($row['types_req']) {
                    case 1:
                        return 'New Application';
                        break;
                    case 2:
                        return 'Service Termination';
                        break;
                    // Add more cases if needed
                    case 3:
                        return 'Agency Onboarding';
                        break;
                }
            })
            ->addColumn('project_name', function ($row) {
                return $row['project_name'];
            })
            ->addColumn('server_name', function ($row) {
                $serverNames = json_decode($row['server_name'], true);
                if (is_array($serverNames)) {
                    return implode(", ", $serverNames);
                }
                return $row['server_name'];
            })
            ->addColumn('ip_addr_server', function ($row) {
                $ipAddrSvr = (array)json_decode($row['ip_addr_server']);
                if (is_array($ipAddrSvr)) {
                    return implode(", ", $ipAddrSvr);
                }
                return $row['ip_addr_server'];
            })
            ->addColumn('total_ip', function ($row) {
                return '0';
            })
            ->addColumn('fqdn_system', function ($row) {
                $fqdnSystem = json_decode($row['fqdn_system'], true);
                if (is_array($fqdnSystem)) {
                    return implode(", ", $fqdnSystem);
                }
                return $row['fqdn_system'];
            })
            ->addColumn('created_at', function ($row) {
                $date = $row['created_at'];
                return $date->format('d/m/Y');
            })
            ->addColumn('updated_at', function ($row) {
                // Parse the date string using DateTime class
                $date = $row['updated_at'];

                // Format the date in the desired format
                return $date->format('d/m/Y');
            })
            ->addColumn('status', function ($row) {
                //return $row['status'];
                return $row['status'] == 0 ? 'Pending Created' : 'Done Created';
            })
            ->addColumn('action', function ($row) {
                $data = '<a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="'.route('smtp_relay.view',[$row['id']]).'"><i class="fas fa-eye text-primary"></i></a>
                <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="'.route('smtp_relay.kemaskini',[$row['id']]).'"><i class="fas fa-edit text-primary"></i></a>
                <a type="button" class="btn btn-datatable btn-icon btn-transparent-dark" id="delete" data-id="'.$row['id'].'" data-url="'.route('smtp_relay.delete',[$row['id']]).'">
                <i class="fas fa-trash-alt text-danger"></i>
                </a>';

            return $data;

            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function delete(Request $request, $id)
    {
        $application = SmtpRelayReqForm::find($id);
        $application    ->  updated_by = Auth::user()->id;
        $application    ->  delete();

        return response()->json(['success' => '1', 'redirect' => route('smtp_relay.smtp_relay_req'), 'message' => 'Application successfully deleted']);
    }


    //penambahbaikkan sidi

    public function daftar()
    {
        $serversArray = Server::all()->toArray();
        $domainsArray = DomainMinistryAgencies::where('enabled', 1)->get()->toArray();
        $departmentsArray = Department::where('status', 1)->get()->toArray();
        $positionsArray = Position::where('status', 1)->get()->toArray();
        return view('smtp_relay.register', compact('serversArray','domainsArray','departmentsArray','positionsArray'));
    }

    public function saving(Request $request){

        $file = $request->file('attachement');
        $fileName = $file->getClientOriginalName();

        // Move the uploaded file to the 'attachements' directory at the same level as the 'app' folder
        $file->move(storage_path('../Storage/attachements'), $fileName);

        // Create the user with the validated data
        $formdata = SmtpRelayReqForm::create([
            'form_id'=> $request->input('form_id'),
            'ticket_id' => $request->input('ticketno'),
            'types_req' => $request->input('type_req'),
            'project_name' => $request->input('projectname'),
            'ministry' => $request->input('ministry'),
            'system_owner_name' => $request->input('ownername'),
            'owner_email_addr' => $request->input('emailadd'),
            'owner_phone_number' => $request->input('phone'),
            'agencies' => $request->input('agency'),
            'dept_div_unit' => $request->input('departowner'),
            'agencies_addr' => $request->input('agencyadd'),
            'req_justifications' => $request->input('justification'),
            'smtp_relay_auth_method' => $request->input('authmethod'),
            'mailfrom_email_addr' => $request->input('emailaddfrom'),
            'mailbox_created' => $request->input('mailbox'),
            'applicant_name' => $request->input('applicantname'),
            'applicant_position' => $request->input('applicantposition'),
            'applicant_dept_div' => $request->input('applicantdepart'),
            'date_application' => $request->input('applicantdate'),
            'verifier_1_name' => $request->input('verifiername'),
            'verifier_1_position' => $request->input('verifierposition'),
            'verifier_1_dept_div' => $request->input('verifierdepart'),
            'date_verification_1' => $request->input('verifierdate'),
            'verifier_2_name' => $request->input('officername'),
            'date_verification_2' => $request->input('officerdate'),
            'verifier_3_name' => $request->input('approvalname'),
            'verifier_3_position' => $request->input('approvalposition'),
            'verifier_3_dept_div' => $request->input('approvaldepart'),
            'date_verification_3' => $request->input('approvaldate'),
            'est_no_system_users_per_day' => $request->input('enday'),
            'est_no_email_sent_from_system_users_per_day' => $request->input('ensystem'),
            'upload_copy_attachment' => $fileName,
            'smtp_relay_server'=>$request->input('mailrelay'),
            'smtp_username'=>$request->input('authusername'),
            'smtp_password'=>$request->input('authpassword'),
            'smtp_port'=>$request->input('authmethod'),
            'smtp_port'=>$request->input('ports'),
            'status' => 0,
        ]);

        $serverNames = $request->input('server_name');
        $ipAddresses = $request->input('ipaddress');
        $domains = $request->input('domain');
        $nombor = $request->input('form_id');

        $tableSpmrData = $request->input('tableSpmr');
        $combinedData = [];

        if (!empty($request->input('authusername')) && !empty($request->input('authpassword'))) {
            if(isset($tableSpmrData) && is_array($tableSpmrData)) {
                foreach ($tableSpmrData as $index => $row) {

                    // Retrieve values from each row
                    $serverName = $row['server_name'] ?? null;
                    $ipAddress = $row['ipaddress'] ?? null;
                    $domain = $row['domain'] ?? null;

                    // Create combined data array
                    $combinedData[] = [
                        'form_id' => $nombor,
                        'server_name' => $serverName,
                        'ip_addr_server' => $ipAddress,
                        'fqdn_server' => $domain,
                    ];
                }
            }

        } else {
            if(isset($tableSpmrData) && is_array($tableSpmrData)) {
                foreach ($tableSpmrData as $index => $row) {
                    // Retrieve values from each row
                    $serverName = $row['server_name'] ?? null;
                    $ipAddress = $row['ipaddress'] ?? null;
                    $domain = $row['domain'] ?? null;

                    // Create combined data array
                    $combinedData[] = [
                        'form_id' => $nombor,
                        'server_name' => $serverName,
                        'ip_addr_server' => $ipAddress,
                        'fqdn_server' => $domain,
                    ];
                }
            }
        }

        // Loop through the combined array and store data
        foreach ($combinedData as $data) {
            smtp_relay_server::create($data);
        }

        return redirect()->route('smtp_relay.smtp_relay_req')->with('success', 'Form added successfully');

    }

    public function papar($id){
        $datasmtp = SmtpRelayReqForm::find($id);
        $details = smtp_relay_server::where('form_id', '=', $datasmtp->form_id)->get();
        return view('smtp_relay.view',compact('datasmtp','details'));
    }

    public function kemaskini(Request $request,$id){
        $formsmtp = SmtpRelayReqForm::find($id);
        $details = smtp_relay_server::where('form_id', '=', $formsmtp->form_id)->get();
        $serversArray = Server::all()->toArray();
        $domainsArray = DomainMinistryAgencies::take(10)->get()->toArray();
        return view('smtp_relay.update',compact('formsmtp','details','serversArray','domainsArray'));
    }

    public function kemaskinidata(Request $request,$id){

        // Update the main form data
        $formdata = $request->only([
            'ticket_id', 'types_req', 'project_name', 'system_owner_name', 'owner_email_addr', 'owner_phone_number',
            'agencies', 'dept_div_unit', 'agencies_addr', 'req_justifications', 'smtp_relay_auth_method',
            'smtp_relay_server', 'smtp_port', 'mailfrom_email_addr', 'mailbox_created', 'est_no_system_users_per_day',
            'est_no_email_sent_from_system_users_per_day', 'applicant_name', 'applicant_position', 'applicant_dept_div',
            'date_application', 'verifier_1_name', 'verifier_1_position', 'verifier_1_dept_div', 'date_verification_1',
            'verifier_2_name', 'date_verification_2', 'verifier_3_name', 'verifier_3_position', 'verifier_3_dept_div',
            'date_verification_3',
        ]);

        $formdata['form_id'] = $id;
        $formdata['status'] = 0;

        SmtpRelayReqForm::where('form_id', $id)->update($formdata);

        // Process 'tableSpmr' data
        $tableSpmrData = $request->input('tableSpmr');
        $smtpRelayServers = [];

        // Update or insert 'smtp_relay_servers' based on the form_id
        foreach ($tableSpmrData as $row) {
            smtp_relay_server::updateOrInsert(
                [
                    'form_id' => $id,
                    'server_name' => $row['server_name'],
                    'ip_addr_server' => $row['ip_addr_server'],
                    'fqdn_server' => $row['fqdn_server'],
                ]
            );
        }
        return redirect()->route('smtp_relay.smtp_relay_req')->with('success', 'Form updated successfully!');
    }

}
