@extends('layouts.app')
@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"></div>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                                        <li class="breadcrumb-item">SMTP Relay MyGovUC3.0</li>
                                    </ol>
                                </nav>

                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-xl px-4 mt-4">
            <div class="row">
                <div class="col-xl-12">
                    <!-- Account details card-->
                    <div class="card mb-4">
                    @isset($applicantInfo)
                        <div class="card-header">Update SMTP Relay MyGovUC3.0</div>
                    @else
                        <div class="card-header">Register SMTP Relay MyGovUC3.0</div>
                    @endisset
                        
                        <div class="card-body  mt-1 mb-4">
                            <h4>Application Information</h4>
                            </br>
                            @isset($applicantInfo)
                            <form class="ajax-form" novalidate action="{{ route('smtp_relay.update',[$applicantInfo->id]) }}" method="POST">
                                @else
                                    <form class="ajax-form" novalidate action="{{ route('smtp_relay.store') }}" method="POST">
                                        @endisset
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (tiket number)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputTickNom">Tiket Number:</label>
                                        {!! Form::text('ticket_no',$applicantInfo->ticket_no ?? '' , ['id'=>'ticket_no', 'name'=>'ticket_no', 'class'=>'form-control']) !!}
                                    </div>
                                    <!-- Form Group (types application)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputTypesApp">Types of Application:</label>
                                        {!! Form::select('applicationType', $applicationType, isset($applicantInfo) ? $applicantInfo->application_type : null,  ['id'=>'applicationType', 'name'=>'type', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                    </div>
                                </div>
                                <!-- Form Group (project name)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputProjName">Project Name:</label>
                                    {!! Form::text('project_name', $applicantInfo->project_name ?? '', ['id'=>'project_name', 'name'=>'project_name', 'class'=>'form-control']) !!}
                                </div>
                                <!-- Form Row-->
                                 <!-- Your dynamic inputs container -->
                                    <div id="dynamic-inputs-container">
                                        <!-- Your initial input row -->
                                        <div class="row gx-3 mb-3 dynamic-input-row">
                                            <!-- Form Group (server name)-->
                                            <div class="col-md-3">
                                                <label class="small mb-1" for="inputServerName">Server Name:</label>
                                                {!! Form::text('server_name[]', $applicantInfo->server_name ?? '', ['class'=>'form-control']) !!}
                                            </div>
                                            <!-- Form Group (ip address server)-->
                                            <div class="col-md-3">
                                                <label class="small mb-1" for="inputIpAddrServer">IP Address Server:</label>
                                                {!! Form::text('ip_server[]', $applicantInfo->ip_server ?? '', ['class'=>'form-control']) !!}
                                            </div>
                                            <!-- Form Group (fqdn system)-->
                                            <div class="col-md-3">
                                                <label class="small mb-1" for="inputFqdnServer">FQDN System:</label>
                                                {!! Form::text('fqdn_system[]', $applicantInfo->fqdn_system ?? '', ['class'=>'form-control']) !!}
                                            </div>
                                            <!-- Remove button-->
                                            <div class="col-md-3" style="padding: 25px">
                                                <button class="btn btn-danger small mb-1 remove-row"><i class="me-1" data-feather="minus"></i>Remove</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add button -->
                                    <div class="col-md-11" style="margin: -56px;text-align:right;padding-top: 38px;padding-bottom: 58px;">
                                        <button id="add-row" type="button" class="btn btn-primary small mb-1"><i class="me-1" data-feather="plus"></i>Add</button>
                                    </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (mailfrom email address)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputMailfromEmailAddr">MAILFROM Email Address:</label>
                                        {!! Form::text('mail_add',  $applicantInfo->mail_add ?? '', ['id'=>'mail_add', 'name'=>'mail_add', 'class'=>'form-control']) !!}
                                    </div>
                                    <!-- Form Group (mailbox existence)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputMailboxExistence">Mailbox Existence:</label>
                                        {!! Form::select('mailbox', $mailbox, isset($applicantInfo) ? $applicantInfo->mailbox : null,  ['id'=>'mailbox', 'name'=>'mailbox', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                    </div>
                                </div>
                                <!-- Form Group (system owner name)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputSystOwnerName">System Owner Name:</label>
                                    {!! Form::text('sys_owner', $applicantInfo->sys_owner ?? '', ['id'=>'sys_owner', 'name'=>'sys_owner', 'class'=>'form-control']) !!}
                                </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (email owner system)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputEmailAddrOwnerSyst">Email Address Owner System:</label>
                                        {!! Form::text('email_add_owner', $applicantInfo->email_add_owner ?? '', ['id'=>'email_add_owner', 'name'=>'email_add_owner', 'class'=>'form-control']) !!}
                                    </div>
                                    <!-- Form Group (phone number owner system)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputPhoneOwnerSyst">Phone / Mobile Number Owner System:</label>
                                        {!! Form::text('phone_owner', $applicantInfo->phone_owner ?? '', ['id'=>'phone_owner', 'name'=>'phone_owner', 'class'=>'form-control']) !!}
                                    </div>
                                </div>
                                <!-- Form Group (agencies)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputAgencies">Agencies:</label>
                                    {!! Form::select('agency', $agency, isset($applicantInfo) ? $applicantInfo->agency : null,  ['id'=>'agency', 'name'=>'agency', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                </div>
                                <!-- Form Group (dept div unit)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputDeptDivUnit">Department / Division / Unit:</label>
                                    {!! Form::select('department', $department, isset($applicantInfo) ? $applicantInfo->department : null,  ['id'=>'department', 'name'=>'department', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}

                                </div>
                                <!-- Form Group (agency address)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputAgencyAddr">Agency Address:</label>
                                    {{ Form::textarea('agency_Addr', $applicantInfo->agency_Addr ?? '', ['class' => 'form-control', 'id' => 'agency_Addr', 'name' => 'agency_Addr', 'rows' => 3]) }}
                                </div>
                                <!-- Form Group (justification for application)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputJustApp">Justification for Application:</label>
                                    {{ Form::textarea('justify_app', $applicantInfo->justify_app ?? '', ['class' => 'form-control', 'id' => 'justify_app', 'name' => 'justify_app', 'rows' => 3]) }}
                                </div>
                                <!-- Form Group (smtp relay authentication method)-->
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-4">
                                        <label class="small mb-1" for="inputSmtpRelayAuthMethod">SMTP Relay Authentication Method:</label>
                                        {!! Form::select('smtpAuth', $smtpAuth, isset($applicantInfo) ? $applicantInfo->smtp_auth_method : null,  ['id'=>'smtpAuth', 'name'=>'smtpAuth', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                    </div>
                               
                                    <!-- Form Group (smtp relay server)-->
                                    <div class="col-md-4">
                                        <label class="small mb-1" for="inputSmtpRelayServer">SMTP Relay Server:</label>
                                        {!! Form::select('smtpRelayServer', $smtpRelayServer, isset($applicantInfo) ? $applicantInfo->smtp_relay_server : null,  ['id'=>'smtpRelayServer', 'name'=>'smtpRelayServer', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}                         
                                    </div>
                                    <!-- Form Group (smtp Port)-->
                                    <div class="col-md-4">
                                        <label class="small mb-1" for="inputSmtpPort">SMTP Port</label>
                                        {!! Form::select('smtpPort', $smtpPort, isset($applicantInfo) ? $applicantInfo->smtp_port : null,  ['id'=>'smtpPort', 'name'=>'smtpPort', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (smtp username)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputSmtpUsername">SMTP Username:</label>
                                        {!! Form::text('smtp_username', $applicantInfo->smtp_username ?? '', ['id'=>'smtp_username', 'name'=>'smtp_username', 'class'=>'form-control']) !!}
                                    </div>
                                    <!-- Form Group (smtp password)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputSmtpPwd">SMTP Password:</label>
                                        {!! Form::text('smtp_pass', $applicantInfo->smtp_pass ?? '', ['id'=>'smtp_pass', 'name'=>'smtp_pass', 'class'=>'form-control']) !!}
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (estimated number of system users)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputEstSystUsers">Estimated number of system users (per day):</label>
                                        {!! Form::text('number_user', $applicantInfo->number_user ?? '', ['id'=>'number_user', 'name'=>'number_user', 'class'=>'form-control']) !!}
                                    </div>
                                    <!-- Form Group (estimated number of emails sent from the system)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputEstNumbEmailSent">Estimated number of emails sent from the system (per day):</label>
                                        {!! Form::text('number_system', $applicantInfo->number_system ?? '', ['id'=>'number_system', 'name'=>'number_system', 'class'=>'form-control']) !!}
                                    </div>
                                </div>
                        
                            </br>
                            <hr>
                            </br>
                            <h4>Application Verification</h4>
                            </br>
                            <label class="small mb-1">
                                        I hereby confirm that all information and statements provided in this application form are <b>TRUE</b> and <b>VALID</b>:
                                    </label>

                                    <!-- Form Group (applicant's name)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputApplicantVerification">Applicant's Name:</label>
                                        {!! Form::text('applicant_name',  $applicantInfo->applicant_name ?? '', ['id'=>'applicant_name', 'name'=>'applicant_name', 'class'=>'form-control']) !!}
                                    </div>
                                    <div class="row col-12">
                                    <!-- Form Group (applicant's position)-->
                                    <div class="mb-3 col-md-4">
                                        <label class="small mb-1" for="inputApplicantPosition">Applicant's Position:</label>
                                        {!! Form::select('position', $position, isset($applicantInfo) ? $applicantInfo->applicant_position : null,  ['id'=>'applicantPosition', 'name'=>'applicantPosition', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                    </div>
                                    <!-- Form Group (applicant's department/division)-->
                                    <div class="mb-3 col-md-4">
                                        <label class="small mb-1" for="inputApplicantDeptDiv">Applicant's Department / Division:</label>
                                        {!! Form::select('department', $department, isset($applicantInfo) ? $applicantInfo->department : null,  ['id'=>'applicantDepartment', 'name'=>'applicantDepartment', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                    </div>
                                    <!-- Form Group (application date)-->
                                    <div class="mb-3 col-md-4">
                                        <label class="small mb-1" for="inputAppDate">Application Date:</label>
                                        {!! Form::text('date', null, ['id' => 'applicant_date', 'class' => 'form-control datepicker', 'placeholder' => '----- Please Choose Date -----', 'autocomplete' => 'off']) !!}
                                    </div> 
                                  </div>
                            </br>
                            <hr>
                            </br>
                            <h4>Verifier I</h4>
                            </br>
                        <!-- Form Group (verifier name 1)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="inputVerifiedName1">Verifier Name I:</label>
                                {!! Form::text('verifier_name_1', $applicantInfo->verifier_name_1 ?? '', ['id'=>'verifier_name_1', 'name'=>'verifier_name_1', 'class'=>'form-control']) !!}
                            </div>
                            <div class="row col-12">
                            <!-- Form Group (verifier position 1)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerifiedPosition1">Verifier Position I:</label>
                                {!! Form::select('position', $position, isset($applicantInfo) ? $applicantInfo->verifier_position : null,  ['id'=>'verifierPosition', 'name'=>'verifierPosition', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                            </div>
                            <!-- Form Group (verifier department/division 1)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerifiedDeptDiv1">Verifier Department / Division I:</label>
                                {!! Form::select('department', $department, isset($applicantInfo) ? $applicantInfo->verifier_department : null,  ['id'=>'verifierDepartment', 'name'=>'verifierDepartment', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                            </div>
                            <!-- Form Group (verification date 1)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerificationDate1">Verification Date I:</label>
                                {!! Form::text('date', null, ['id' => 'verifier_date', 'class' => 'form-control datepicker', 'placeholder' => '----- Please Choose Date -----', 'autocomplete' => 'off']) !!}
                            </div>
                            </div>
                            </br>
                            <hr>
                            </br>
                            <h4>Verifier II</h4>
                            </br>
                           <!-- Form Group (verifier name 2)-->
                           <div class="mb-3">
                                <label class="small mb-1" for="inputVerifiedName2">Verifier Name II:</label>
                                {!! Form::text('verifier_name_2', $applicantInfo->verifier_name_2 ?? '', ['id'=>'verifier_name_2', 'name'=>'verifier_name_2', 'class'=>'form-control']) !!}
                            </div>
                            <div class="row col-12">
                            <!-- Form Group (verifier position 2)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerifiedPosition2">Verifier Position II:</label>
                                {!! Form::select('position', $position, isset($applicantInfo) ? $applicantInfo->verifier_position_2 : null,  ['id'=>'verifierPosition2', 'name'=>'verifierPosition2', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                            </div>
                            <!-- Form Group (verifier department/division 2)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerifiedDeptDiv2">Verifier Department / Division II:</label>
                                {!! Form::select('department', $department, isset($applicantInfo) ? $applicantInfo->verifier_department_2 : null,  ['id'=>'verifierDepartment2', 'name'=>'verifierDepartment2', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                            </div>
                            <!-- Form Group (verification date 2)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerificationDate2">Verification Date II:</label>
                                {!! Form::text('date', null, ['id' => 'verifier_date_2', 'class' => 'form-control datepicker', 'placeholder' => '----- Please Choose Date -----', 'autocomplete' => 'off']) !!}
                                    
                            </div>
                             </div>
                            </br>
                            <hr>
                            </br>
                            <h4>Verifier III</h4>
                            </br>
                             <!-- Form Group (verifier name 3)-->
                             <div class="mb-3">
                                <label class="small mb-1" for="inputVerifiedName3">Verifier Name III:</label>
                                {!! Form::text('verifier_name_3', $applicantInfo->verifier_name_3 ?? '', ['id'=>'verifier_name_3', 'name'=>'verifier_name_3', 'class'=>'form-control']) !!}
                            </div>
                            <div class="row col-12">
                            <!-- Form Group (verifier position 3)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerifiedPosition3">Verifier Position III:</label>
                                {!! Form::select('position', $position, isset($applicantInfo) ? $applicantInfo->verifier_position_3 : null,  ['id'=>'verifierPosition3', 'name'=>'verifierPosition3', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                            </div>
                            <!-- Form Group (verifier department/division 3)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerifiedDeptDiv3">Verifier Department / Division III:</label>
                                {!! Form::select('department', $department, isset($applicantInfo) ? $applicantInfo->verifier_department_3 : null,  ['id'=>'verifierDepartment3', 'name'=>'verifierDepartment3', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                            </div>
                            <!-- Form Group (verification date 3)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerificationDate3">Verification Date III:</label>
                                {!! Form::text('date', null, ['id' => 'verifier_date_3', 'class' => 'form-control datepicker', 'placeholder' => '----- Please Choose Date -----', 'autocomplete' => 'off']) !!}
                                        </div>
                            </div>
                            </br>
                            <hr>
                            </br>
                            <h4>Upload Copy Attachment</h4>
                            </br>
                            <div class="mb-3" style="text-align:center;">
                                    <!-- Copy attachment image-->
                                    <img class="img-account-profile rounded-circle mb-2" src="assets/img/illustrations/profiles/profile-1.png" alt="" />

                                    <!-- Copy attachment help block-->
                                    <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>

                                    <!-- Input for file upload -->
                                    <input type="file" class="form-control" id="attachmentInput" style="display: none;" />

                                    <!-- Copy attachment upload button-->
                                    <label for="attachmentInput" class="btn btn-primary">Upload Attachment</label>
                                </div>

                            <div class="row">
                                <!-- Save and Back button-->
                                <div class="col-md-12" style="padding: 25px;text-align:right;">
                                    <a class="btn btn-secondary" type="button" href="{{ route('smtp_relay.smtp_relay_req') }}">Back</a>
                                    
                                    <button class="btn btn-success" id="save">Save</button>
                                </div>
                            </div>
                            
                            </form>
                        </div>
                    </div>

                   
                </div>
            </div>
        </div>
    </main>
@endsection
@push('script')
<script>
    $(document).ready(function() {
        // Add row
        $('#add-row').click(function() {
            var newRow = $('.dynamic-input-row').first().clone();
            newRow.find('input').val('');
            $('#dynamic-inputs-container').append(newRow);
        });

        // Remove row
        $(document).on('click', '.remove-row', function() {
            $(this).closest('.dynamic-input-row').remove();
        });
    });
</script>
@endpush