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

    public function store(){
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

            // $application->save();

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
