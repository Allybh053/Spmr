<?php

namespace App\Http\Controllers;

use App\Models\SmtpRelayReqForm;
use App\Models\smtp_relay_server;
use Carbon\Carbon;
use App\Models\Department;
use App\Models\Position;
use App\Models\Server;
use App\Models\User;
use App\Models\DomainMinistryAgencies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\RoleServer;
use Illuminate\Support\Facades\Mail;
use App\Mail\mailForm;

class SmtpRelayController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        $agency = [];
        $domainsArray = DomainMinistryAgencies::where('enabled', 1)->get()->toArray();

        $serverIds = RoleServer::whereIn('role_id', $user->roles->pluck('id')->all())
        ->pluck('server_id');

        $defaultServerId = 1;

        // Filter servers based on the fetched IDs
        $serverconfig = Server::whereIn('id', $serverIds)
        ->where('status', 1)
        ->pluck('hostname', 'id');

        return view('smtp_relay.index', compact('user', 'role','agency','serverIds','defaultServerId','serverconfig','domainsArray'));
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

    public function datatable(Request $request)
    {
          // Get search parameters
        $agency = $request->input('agency');
        $calendarType = $request->input('calendarType');

        // Start building the query
        $query =  SmtpRelayReqForm::select('smtp_relay_req_form.*', 'smtp_relay_server.ip_addr_server')
            ->leftJoin('smtp_relay_server', 'smtp_relay_req_form.form_id', '=', 'smtp_relay_server.form_id');

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
            /*->addColumn('total_ip', function ($row) {
                $id = $row['id'];
                $datasmtp = SmtpRelayReqForm::find($id);
                $details = smtp_relay_server::where('form_id', '=', $datasmtp->form_id)->get();

                if ($details->isNotEmpty()) {
                    $totalCount = $details->count(); // Count the total occurrences of form_id
                    return $totalCount;
                } else {
                    return '0';
                }
            })*/
            ->addColumn('ip_addr_server', function ($row) {
                /*$ipAddrSvr = (array)json_decode($row['ip_addr_server']);
                if (is_array($ipAddrSvr)) {
                    return implode(", ", $ipAddrSvr);
                }
                return $row['ip_addr_server'];*/

                return $row->ip_addr_server;

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
                //return $row['status']; value 0 is pending, 1 done created and 2 rejected
                return $row['status'] == 0 ? 'Pending Created' : ($row['status'] == 1 ? 'Done Created' : 'Rejected');
            })
            ->addColumn('action', function ($row) {
                $data = '';

                // Add @can directive to check for permissions
                if (auth()->user()->can('smtp_mail_relay_view')) {
                    $data .= '<a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="'.route('smtp_relay.view',[$row['id']]).'"><i class="fas fa-eye text-primary"></i></a>';
                }
                if (auth()->user()->can('smtp_mail_relay_update')) {
                    $data .= '<a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="'.route('smtp_relay.kemaskini',[$row['id']]).'"><i class="fas fa-edit text-primary"></i></a>';
                }
                if (auth()->user()->can('smtp_mail_relay_delete')) {
                    $data .= '<a type="button" class="btn btn-datatable btn-icon btn-transparent-dark" id="delete" data-id="'.$row['id'].'" data-url="'.route('smtp_relay.delete',[$row['id']]).'">
                        <i class="fas fa-trash-alt text-danger"></i>
                    </a>';
                }

                return $data;

            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function delete(Request $request, $id)
    {
        // Delete application SMTP Relay
        $application = SmtpRelayReqForm::find($id);
        if (!$application) {
            return response()->json(['success' => '0', 'message' => 'Application not found']);
        }

        // Delete data smtp relay server based on form_id
        smtp_relay_server::where('form_id', $application->form_id)->delete();

        // Delete the application
        $application->delete();

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

        // Check if a file was uploaded
        if ($request->hasFile('attachment')) {
            // Retrieve the uploaded file
            $files = $request->file('attachment');

            // Get the original name of the file
            $originalFileName = pathinfo($files->getClientOriginalName(), PATHINFO_FILENAME);
            $currentDateTime = date('dmYHis');
            $fileExtension = $files->getClientOriginalExtension();
            $fileName = $originalFileName . '_' . $currentDateTime . '.' . $fileExtension;

            // Move the uploaded file to the storage directory
            $files->move(storage_path('../Storage/attachments'), $fileName);
        } else {
            // No file was uploaded, set fileName to null
            $fileName = null;
        }

        $ministry = $request->input('ministry');
        $agency = $request->input('agency');

        // $existingMinistry = DomainMinistryAgencies::where('ministry_name', $ministry)->first();
        // if (!$existingMinistry) {
        //     // Ministry does not exist, store it in the DomainMinistryAgencies table
        //     DomainMinistryAgencies::create([
        //         'ministry_name' => $ministry,
        //         'agencies_name' => $agency,
        //         'enabled'=> 0,
        //         'domain_name'=>$row['domain'], // You need to provide the domain name here
        //     ]);
        // }

        $departowner = $request->input('departowner');
        $existingdepartonwer = Department::where('name', $departowner)->first();
        if(!$existingdepartonwer){
            Department::create(['name'=>$departowner, 'status'=>1]);
        }
        $applicantdepart = $request->input('applicantdepart');
        $existingapplicantdepart = Department::where('name', $applicantdepart)->first();
        if(!$existingapplicantdepart){
            Department::create(['name'=>$applicantdepart, 'status'=>1]);
        }
        $verifierdepart = $request->input('verifierdepart');
        $existingverifierdepart = Department::where('name', $verifierdepart)->first();
        if(!$existingverifierdepart){
            Department::create(['name'=>$verifierdepart, 'status'=>1]);
        }
        $approvaldepart = $request->input('approvaldepart');
        $existingapprovaldepart = Department::where('name', $approvaldepart)->first();
        if(!$existingapprovaldepart){
            Department::create(['name'=>$approvaldepart, 'status'=>1]);
        }

        $applicantposition = $request->input('applicantposition');
        $existingapplicantposition = Position::where('name', $applicantposition)->first();
        if(!$existingapplicantposition){
            Position::create(['name'=>$applicantposition, 'status'=>1]);
        }
        $verifierposition = $request->input('verifierposition');
        $existingverifierposition = Position::where('name', $verifierposition)->first();
        if(!$existingverifierposition){
            Position::create(['name'=>$verifierposition, 'status'=>1]);
        }

        $approvalposition =$request->input('approvalposition');
        $existingapprovalposition = Position::where('name', $approvalposition)->first();
        if(!$existingapprovalposition){
            Position::create(['name'=>$approvalposition, 'status'=>1]);
        }

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
        $deactivated = 0;

        $tableSpmrData = $request->input('tableSpmr');
        $combinedData = [];

        if (!empty($request->input('authusername')) && !empty($request->input('authpassword'))) {
            if(isset($tableSpmrData) && is_array($tableSpmrData)) {
                foreach ($tableSpmrData as $index => $row) {

                    // Retrieve values from each row
                    $serverName = $row['server_name'] ?? null;
                    $ipAddress = $row['ipaddress'] ?? null;
                    $domain = $row['domain'] ?? null;
                    $deactivated = $row['deactivated'] ?? null;

                    // Create combined data array
                    $combinedData[] = [
                        'form_id' => $nombor,
                        'server_name' => $serverName,
                        'ip_addr_server' => $ipAddress,
                        'fqdn_server' => $domain,
                        'deactivated' => 0,
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
                    $deactivated = $row['deactivated'] ?? null;

                    // Create combined data array
                    $combinedData[] = [
                        'form_id' => $nombor,
                        'server_name' => $serverName,
                        'ip_addr_server' => $ipAddress,
                        'fqdn_server' => $domain,
                        'deactivated' => 0,
                    ];
                }
            }
        }

        // Loop through the combined array and store data
        foreach ($combinedData as $data) {
            smtp_relay_server::create($data);
        }
        $adminEmails = User::where('role_id', 3)->pluck('email')->toArray(); //role admin will receive the email and cc to the helpdesk
        Mail::to($adminEmails)
        ->cc($request->input('emailadd'))
        ->send(new MailForm($request));

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
        //$domainsArray = DomainMinistryAgencies::take(10)->get()->toArray();
        $domainsArray = DomainMinistryAgencies::where('enabled', 1)->get()->toArray();
        $departmentsArray = Department::where('status', 1)->get()->toArray();
        $positionsArray = Position::where('status', 1)->get()->toArray();
        return view('smtp_relay.update',compact('formsmtp','details','serversArray','domainsArray','departmentsArray','positionsArray'));
    }

    public function kemaskinidata(Request $request,$id){
            // Check if a file was uploaded
        if ($request->hasFile('upload_copy_attachment')) {
            // Retrieve the uploaded file
            $files = $request->file('upload_copy_attachment');

            // Get the original name of the file
            $originalFileName = pathinfo($files->getClientOriginalName(), PATHINFO_FILENAME);

            $currentDateTime = date('dmYHis');
            $fileExtension = $files->getClientOriginalExtension();
            $fileName = $originalFileName . '_' . $currentDateTime . '.' . $fileExtension;

            // Move the uploaded file to the storage directory
            $files->move(storage_path('../Storage/attachments'), $fileName);
        } else {
            // No file was uploaded, set fileName to null
            $fileName = null;
        }

        $departowner = $request->input('dept_div_unit');
        $existingdepartonwer = Department::where('name', $departowner)->first();
        if(!$existingdepartonwer){
            Department::create(['name'=>$departowner, 'status'=>1]);
        }
        $applicantdepart = $request->input('applicant_dept_div');
        $existingapplicantdepart = Department::where('name', $applicantdepart)->first();
        if(!$existingapplicantdepart){
            Department::create(['name'=>$applicantdepart, 'status'=>1]);
        }
        $verifierdepart = $request->input('verifier_1_dept_div');
        $existingverifierdepart = Department::where('name', $verifierdepart)->first();
        if(!$existingverifierdepart){
            Department::create(['name'=>$verifierdepart, 'status'=>1]);
        }
        $approvaldepart = $request->input('verifier_3_dept_div');
        $existingapprovaldepart = Department::where('name', $approvaldepart)->first();
        if(!$existingapprovaldepart){
            Department::create(['name'=>$approvaldepart, 'status'=>1]);
        }

        $applicantposition = $request->input('applicant_position');
        $existingapplicantposition = Position::where('name', $applicantposition)->first();
        if(!$existingapplicantposition){
            Position::create(['name'=>$applicantposition, 'status'=>1]);
        }
        $verifierposition = $request->input('verifier_1_position');
        $existingverifierposition = Position::where('name', $verifierposition)->first();
        if(!$existingverifierposition){
            Position::create(['name'=>$verifierposition, 'status'=>1]);
        }

        $approvalposition =$request->input('verifier_3_position');
        $existingapprovalposition = Position::where('name', $approvalposition)->first();
        if(!$existingapprovalposition){
            Position::create(['name'=>$approvalposition, 'status'=>1]);
        }
        // Update the main form data
        $formdata = $request->only([
            'ticket_id', 'types_req', 'project_name', 'system_owner_name', 'owner_email_addr', 'owner_phone_number',
            'ministry', 'agencies', 'dept_div_unit', 'agencies_addr', 'req_justifications', 'smtp_relay_auth_method',
            'smtp_relay_server', 'smtp_port', 'mailfrom_email_addr', 'mailbox_created', 'est_no_system_users_per_day',
            'est_no_email_sent_from_system_users_per_day', 'applicant_name', 'applicant_position', 'applicant_dept_div',
            'date_application', 'verifier_1_name', 'verifier_1_position', 'verifier_1_dept_div', 'date_verification_1',
            'verifier_2_name', 'date_verification_2', 'verifier_3_name', 'verifier_3_position', 'verifier_3_dept_div',
            'date_verification_3',
        ]);

        $formdata['form_id'] = $id;
        $formdata['status'] = 0;
        $formdata['upload_copy_attachment'] = $fileName;
        SmtpRelayReqForm::where('form_id', $id)->update($formdata);

        // Process 'tableSpmr' data
        $tableSpmrData = $request->input('tableSpmr');
        $smtpRelayServers = [];

        // Update existing rows or insert new rows based on the form_id
        foreach ($tableSpmrData as $row) {
            $existingServer = smtp_relay_server::where('id', $row['id'])
                ->where('form_id', $id)
                ->first();

            if ($existingServer) {
                // Update existing row
                $existingServer->update([
                    'server_name' => $row['server_name'],
                    'ip_addr_server' => $row['ip_addr_server'],
                    'fqdn_server' => $row['fqdn_server'],
                    'deactivated' => $row['deactivated'],
                    'updated_at' => now(),
                ]);
            } else {
                // Insert new row
                smtp_relay_server::create([
                    'form_id' => $id,
                    'server_name' => $row['server_name'],
                    'ip_addr_server' => $row['ip_addr_server'],
                    'fqdn_server' => $row['fqdn_server'],
                    'deactivated' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('smtp_relay.smtp_relay_req')->with('success', 'Form updated successfully!');
    }

}
