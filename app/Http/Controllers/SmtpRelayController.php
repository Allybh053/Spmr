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
use App\Models\Ministry;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
//use Faker\Factory as Faker;
use App\Models\RoleServer;
use Illuminate\Support\Facades\Mail;
use App\Mail\mailForm;
//use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Log;


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

    public function datatable(Request $request)
    {
          // Get search parameters
        $agency = $request->input('agency');
        $calendarType = $request->input('calendarType');

        // Start building the query, xnk guna yg ni
        // $query =  SmtpRelayReqForm::select('smtp_relay_req_form.*', 'smtp_relay_server.ip_addr_server')
        //     ->leftJoin('smtp_relay_server', 'smtp_relay_req_form.form_id', '=', 'smtp_relay_server.form_id');


        $query = smtp_relay_server::select('smtp_relay_req_form.updated_at as updated_at','smtp_relay_req_form.form_id as formA', 'smtp_relay_req_form.ticket_id', 'smtp_relay_req_form.agencies', 'smtp_relay_req_form.project_name', 'smtp_relay_req_form.smtp_relay_server', 'smtp_relay_req_form.date_verification_3', 'smtp_relay_server.types_req as typeB', 'smtp_relay_server.ip_addr_server','smtp_relay_server.id as detailID', 'smtp_relay_server.smtpid','smtp_relay_server.created_at as created_at', 'smtp_relay_server.server_name', 'smtp_relay_req_form.status as status')
                ->leftJoin('smtp_relay_req_form', 'smtp_relay_req_form.id', '=', 'smtp_relay_server.smtpid');
                // ->distinct('smtp_relay_server.types_req');

        // Apply filters based on search parameters
        // if (!empty($agency)) {
        //   $query->where('agency_column', $agency); // Replace 'agency_column' with the actual column name for agency in your database table
        // }



      // Execute the query
      $application = $query->get();

            return datatables()->of($application)
            ->addIndexColumn()
            ->addColumn('form_id', function ($row) {
                return $row['formA'];
            })
            ->addColumn('ticket_id', function ($row) {
                return $row['ticket_id'];
            })
            ->addColumn('types_req', function ($row) {
                switch ($row['typeB']) {
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
            ->addColumn('agencies', function ($row) {
                return $row['agencies'];
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

                return $row->ip_addr_server;

            })
            ->addColumn('date_verification_3', function ($row) {
                $date = Carbon::parse($row['date_verification_3']);
                return $date->format('d/m/Y');
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
                    $data .= '<a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="'.route('smtp_relay.view',[$row['formA'],$row['typeB']]).'"><i class="fas fa-eye text-primary"></i></a>';
                }
                if ($row['status'] == 0) {
                    if (auth()->user()->can('smtp_mail_relay_update')) {
                        $data .= '<a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="'.route('smtp_relay.kemaskini',[$row['smtpid'],$row['typeB']]).'"><i class="fas fa-edit text-primary"></i></a>';
                    }
                    if (auth()->user()->can('smtp_mail_relay_delete')) {
                        $data .= '<a type="button" class="btn btn-datatable btn-icon btn-transparent-dark" id="delete" data-id="'.$row['smtpid'].'" data-url="'.route('smtp_relay.delete',[$row['smtpid'],$row['detailID'],$row['typeB']]).'">
                            <i class="fas fa-trash-alt text-danger"></i>
                        </a>';
                    }
                }

                return $data;

            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function delete(Request $request, $id, $ip, $idApp)
    {
        $application = SmtpRelayReqForm::find($id);

        $tbldetail = smtp_relay_server::where('id', $ip)->where('types_req',$idApp)->first();

        $count = smtp_relay_server::where('form_id', $application->form_id)->where('types_req', $application->types_req)->count();

        $datacount = $count;

        // Check if count is less than or equal to 1
        if ($datacount <= 1) {
            $tbldetail->delete();

            $application->delete();
        } else {
            $tbldetail->delete();
        }

        if (!$application) {
            return response()->json(['success' => '0', 'message' => 'Application not found']);
        }

        // Delete data smtp relay server based on form_id
        // smtp_relay_server::where('form_id', $application->form_id)->where('types_req',$application->types_req)->delete();
        return response()->json(['success' => '1', 'redirect' => route('smtp_relay.smtp_relay_req'), 'message' => 'Application successfully deleted']);
    }

    public function daftar()
    {
        $serversArray = Server::where('status',1)->get()->toArray();
        $domainsArray = DomainMinistryAgencies::where('enabled', 1)->get()->toArray();
        $departmentsArray = Department::where('status', 1)->get()->toArray();
        $positionsArray = Position::where('status', 1)->get()->toArray();
        $ministriesArray = Ministry::where('status', 1)->get()->toArray();
        $agenciesArray = Agency::where('status', 1)->get()->toArray();
        return view('smtp_relay.register', compact('serversArray','domainsArray','departmentsArray','positionsArray','ministriesArray','agenciesArray'));
    }

    public function saving(Request $request)
    {
        // $verifierdate = $request->input('verifierdate');
        // $applicantdate = $request->input('applicantdate');

        // if (strtotime($verifierdate) <= strtotime($applicantdate)) {
        //     return redirect()->route('smtp_relay.smtp_relay_req')->with('error', 'Date Verifier must be greater than Applicant Date!');
        // }

        $type_reqs = $request->input('type_req');

        if ($type_reqs == 1) {
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
                $files->move(storage_path('../storage/app/attachments'), $fileName);
            } else {
                // No file was uploaded, set fileName to null
                $fileName = null;
            }

            $ministry = $request->input('ministry');
            $agency = $request->input('agency');

            $existingMinistry = Ministry::where('name', $ministry)->first();
            if (!$existingMinistry) {
                Ministry::create([
                    'name' => $ministry,
                    'status'=> 1, // You need to provide the domain name here
                ]);
            }

            $existingagency = Agency::where('name', $agency)->first();
            if (!$existingagency) {
                Agency::create([
                    'name' => $agency,
                    'status'=> 1, // You need to provide the domain name here
                ]);
            }

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
                'smtp_port'=>$request->input('ports'),
                'status' => 0,
            ]);

            $serverNames = $request->input('server_name');
            $ipAddresses = $request->input('ipaddress');
            $domains = $request->input('domain');
            $nombor = $request->input('form_id');
            $deactivated = 0;
            $smtpid = $formdata->id;

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
                            'types_req' => 1,
                            'smtpid' => $smtpid
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
                            'types_req' => 1,
                            'smtpid' => $smtpid
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

        } else if ( $type_reqs == 2 || $type_reqs == 3 ){

            $randomId = Str::random(9);

            $tableSpmrDatas = $request->input('tableSpmr');
            $formIds = null;

            foreach ($tableSpmrDatas as $row) {
                $existingServer = smtp_relay_server::where('ip_addr_server', $row['ipaddress'])
                ->where('types_req',$request->input('type_req'))
                ->first();

                if ($existingServer) {
                    $formIds = $existingServer->form_id;
                    break;
                }
            }

            if(!$formIds){
                $formIds = $randomId;
            }

            //$existingServermail = SmtpRelayReqForm::where('form_id',$formIds)->first();

            // $types_reqest = $existingServermail->types_req;
            // $servername = $existingServermail->smtp_relay_server;

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
                $files->move(storage_path('../storage/app/attachments'), $fileName);
            } else {
                // No file was uploaded, set fileName to null
                $fileName = null;
            }

            $ministry = $request->input('ministry');
            $agency = $request->input('agency');

            $existingMinistry = Ministry::where('name', $ministry)->first();
            if (!$existingMinistry) {
                Ministry::create([
                    'name' => $ministry,
                    'status'=> 1,
                ]);
            }

            $existingagency = Agency::where('name', $agency)->first();
            if (!$existingagency) {
                Agency::create([
                    'name' => $agency,
                    'status'=> 1,
                ]);
            }

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

            $formdata = SmtpRelayReqForm::create([
                'form_id'=> $formIds,
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
                'smtp_port'=>$request->input('ports'),
                'status' => 0,
            ]);

            $serverNames = $request->input('server_name');
            $ipAddresses = $request->input('ipaddress');
            $domains = $request->input('domain');
            $nombor = $formIds;
            $deactivated = 0;
            $jenis = $request->input('type_req');
            $smtpid = $formdata->id;

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
                            'types_req' => $jenis,
                            'smtpid' => $smtpid,
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
                            'types_req' => $jenis,
                            'smtpid' => $smtpid,
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
        } else {
            return redirect()->route('smtp_relay.smtp_relay_req')->with('success', 'Form Error!');
        }

    }

    public function papar($id, $type){
        $datasmtp = SmtpRelayReqForm::where('form_id', $id)->where('types_req', $type)->first();
        $details = smtp_relay_server::where('form_id', $datasmtp->form_id)
                    ->where('types_req', $datasmtp->types_req)
                    ->get();
        return view('smtp_relay.view',compact('datasmtp','details'));
    }

    public function kemaskini(Request $request,$id, $type){
        $formsmtp = SmtpRelayReqForm::find($id);
        $details = smtp_relay_server::where('form_id', '=', $formsmtp->form_id)->where('types_req',$type)->get();
        $serversArray = Server::all()->toArray();
        //$domainsArray = DomainMinistryAgencies::take(10)->get()->toArray();
        $domainsArray = DomainMinistryAgencies::where('enabled', 1)->get()->toArray();
        $departmentsArray = Department::where('status', 1)->get()->toArray();
        $positionsArray = Position::where('status', 1)->get()->toArray();
        $ministriesArray = Ministry::where('status', 1)->get()->toArray();
        $agenciesArray = Agency::where('status', 1)->get()->toArray();
        return view('smtp_relay.update',compact('formsmtp','details','serversArray','domainsArray','departmentsArray','positionsArray','ministriesArray','agenciesArray'));
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
            $files->move(storage_path('../storage/app/attachments'), $fileName);
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
        $types_req = $request->input('types_req');
        $formdata['status'] = 0;
        // Conditionally update the upload_copy_attachment field
        if ($fileName !== null) {
            $formdata['upload_copy_attachment'] = $fileName;
        }
        $getid = SmtpRelayReqForm::where('form_id', $id)->pluck('id')->first();

         // Fetch the form instance
            $form = SmtpRelayReqForm::where('form_id', $id)->first();

            // Fill the form with new data
            $form->fill($formdata);

            // Log dirty fields for debugging
            $dirtyFields = $form->getDirty();
            

        // Process 'tableSpmr' data
        $tableSpmrData = $request->input('tableSpmr');
        $smtpRelayServers = [];
        // dd($tableSpmrData->id);
        // Update existing rows or insert new rows based on the form_id
        $allChanges = [];
        foreach ($tableSpmrData as $row) {
            $existingServer = smtp_relay_server::where('id', $row['id'])
            ->first();
        // dd($existingServer);
        if ($existingServer) {
            // Update the existing server record
            $existingServer->ip_addr_server = $row['ip_addr_server'];
            $existingServer->server_name = $row['server_name'];
            $existingServer->fqdn_server = $row['fqdn_server'];
            $existingServer->deactivated = 0; // Set deactivated to 0 if IP address exists
            $existingServer->updated_at = now();
            $existingServer->save();

            $dirtyFields = $existingServer->getDirty();
            $allChanges[] = $dirtyFields; // Collect changes for auditing


            } else {

                // Insert new row
                $newdata = smtp_relay_server::create([
                    'form_id' => $id,
                    'server_name' => $row['server_name'],
                    'ip_addr_server' => $row['ip_addr_server'],
                    'types_req' => $types_req,
                    'fqdn_server' => $row['fqdn_server'],
                    'deactivated' => 0,
                    'smtpid' => $getid,
                    'created_at' => now(),
                ]);

               // Log dirty fields for auditing
               $dirtyFields = $newdata->getDirty();
               $allChanges[] = $dirtyFields; // Collect changes for auditing


            }

            // Add the IP address to the existingIPs array
            $existingIPs[] = $row['ip_addr_server'];
        }

        if ($form->isDirty() || !empty($allChanges)) {
            // Save the main form (this should trigger the audit)
            $form->save();


        } else {
            return response()->json(['message' => 'No changes detected'], 200);
        }

        // Delete data that was not updated
        smtp_relay_server::where('form_id', $id)
        ->whereNotIn('ip_addr_server', $existingIPs)
        ->delete();

        return redirect()->route('smtp_relay.smtp_relay_req')->with('success', 'Form updated successfully!');
    }



    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('file');

        // Read the CSV file and insert data into the database
        /*Excel::load($file, function($reader) {
            foreach ($reader->toArray() as $row) {
                SmtpRelayReqForm::create([
                        'form_id'=> $row['column1'],
                        'ticket_id' => $row['column2'],
                        'types_req' => $row['column3'],
                        'project_name' => $row['column4'],
                        'ministry' => $row['column5'],
                        'system_owner_name' => $row['column6'],
                        'owner_email_addr' => $row['column7'],
                        'owner_phone_number' => $row['column8'],
                        'agencies' => $row['column9'],
                        'dept_div_unit' => $row['column10'],
                        'smtp_relay_auth_method' => $row['column11'],
                        'mailfrom_email_addr' => $row['column12'],
                        'mailbox_created' => $row['column13'],
                        'applicant_name' => $row['column14'],
                        'date_application' => $row['column15'],
                        'verifier_1_name' => $row['column16'],
                        'verifier_2_name' => $row['column17'],
                        'date_verification_2' => $row['column18'],
                        'verifier_3_name' => $row['column19'],
                        'date_verification_3' => $row['column20'],
                        'smtp_relay_server'=> $row['column21'],
                        'smtp_username'=> $row['column22'],
                        'smtp_password'=> $row['column23'],
                        'smtp_port'=> $row['column24'],
                        'status' => 0,
                ]);
            }
        });*/

        Excel::import(new ImportController(), $file);

        return redirect()->route('smtp_relay.smtp_relay_req')->with('success', 'Data imported successfully.');
    }

    public function showPdf($filename)
    {
        // Path to the PDF file
        $path = storage_path('app/attachments/'.$filename);

        // Check if the file exists
        if (!Storage::disk('local')->exists('attachments/'.$filename)) {
            abort(404);
        }

        // Return the PDF file with appropriate headers
        return response()->file($path);
    }

}
