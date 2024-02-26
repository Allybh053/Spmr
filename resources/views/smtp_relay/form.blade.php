@extends('layouts.app')
@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-fluid px-4">
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
        <div class="container-fluid px-4 mt-4">
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
                                        {!! Form::text('ticket_no',$applicantInfo->ticket_id ?? '' , ['id'=>'ticket_no', 'name'=>'ticket_no', 'class'=>'form-control']) !!}
                                    </div>
                                    <!-- Form Group (types application)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputTypesApp">Types of Application:</label>
                                        {!! Form::select('applicationType', $applicationType, isset($applicantInfo) ? $applicantInfo->types_req : null,  ['id'=>'applicationType', 'name'=>'type', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
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
                                                {!! Form::text('ip_server[]', $applicantInfo->ip_addr_server ?? '', ['class'=>'form-control']) !!}
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
                                        {!! Form::text('mail_add',  $applicantInfo->mailfrom_email_addr ?? '', ['id'=>'mail_add', 'name'=>'mail_add', 'class'=>'form-control']) !!}
                                    </div>
                                    <!-- Form Group (mailbox existence)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputMailboxExistence">Mailbox Existence:</label>
                                        {!! Form::select('mailbox', $mailbox, isset($applicantInfo) ? $applicantInfo->mailbox_created : null,  ['id'=>'mailbox', 'name'=>'mailbox', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                    </div>
                                </div>
                                <!-- Form Group (system owner name)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputSystOwnerName">System Owner Name:</label>
                                    {!! Form::text('sys_owner', $applicantInfo->system_owner_name ?? '', ['id'=>'sys_owner', 'name'=>'sys_owner', 'class'=>'form-control']) !!}
                                </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (email owner system)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputEmailAddrOwnerSyst">Email Address Owner System:</label>
                                        {!! Form::text('email_add_owner', $applicantInfo->owner_email_addr ?? '', ['id'=>'email_add_owner', 'name'=>'email_add_owner', 'class'=>'form-control']) !!}
                                    </div>
                                    <!-- Form Group (phone number owner system)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputPhoneOwnerSyst">Phone / Mobile Number Owner System:</label>
                                        {!! Form::text('phone_owner', $applicantInfo->owner_phone_number ?? '', ['id'=>'phone_owner', 'name'=>'phone_owner', 'class'=>'form-control']) !!}
                                    </div>
                                </div>
                                <!-- Form Group (agencies)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputAgencies">Agencies:</label>
                                    {!! Form::select('agency', $agency, isset($applicantInfo) ? $applicantInfo->agencies : null,  ['id'=>'agency', 'name'=>'agency', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                </div>
                                <!-- Form Group (dept div unit)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputDeptDivUnit">Department / Division / Unit:</label>
                                    {!! Form::select('department', $department, isset($applicantInfo) ? $applicantInfo->dept_div_unit : null,  ['id'=>'department', 'name'=>'department', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}

                                </div>
                                <!-- Form Group (agency address)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputAgencyAddr">Agency Address:</label>
                                    {{ Form::textarea('agency_Addr', $applicantInfo->agencies_addr ?? '', ['class' => 'form-control', 'id' => 'agency_Addr', 'name' => 'agency_Addr', 'rows' => 3]) }}
                                </div>
                                <!-- Form Group (justification for application)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputJustApp">Justification for Application:</label>
                                    {{ Form::textarea('justify_app', $applicantInfo->req_justifications ?? '', ['class' => 'form-control', 'id' => 'justify_app', 'name' => 'justify_app', 'rows' => 3]) }}
                                </div>
                                <!-- Form Group (smtp relay authentication method)-->
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-4">
                                        <label class="small mb-1" for="inputSmtpRelayAuthMethod">SMTP Relay Authentication Method:</label>
                                        {!! Form::select('smtpAuth', $smtpAuth, isset($applicantInfo) ? $applicantInfo->smtp_relay_auth_method : null,  ['id'=>'smtpAuth', 'name'=>'smtpAuth', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
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
                                        {!! Form::text('smtp_pass', $applicantInfo->smtp_password ?? '', ['id'=>'smtp_pass', 'name'=>'smtp_pass', 'class'=>'form-control']) !!}
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (estimated number of system users)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputEstSystUsers">Estimated number of system users (per day):</label>
                                        {!! Form::text('number_user', $applicantInfo->est_no_system_users_per_day ?? '', ['id'=>'number_user', 'name'=>'number_user', 'class'=>'form-control']) !!}
                                    </div>
                                    <!-- Form Group (estimated number of emails sent from the system)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputEstNumbEmailSent">Estimated number of emails sent from the system (per day):</label>
                                        {!! Form::text('number_system', $applicantInfo->est_no_email_sent_from_system_users_per_day ?? '', ['id'=>'number_system', 'name'=>'number_system', 'class'=>'form-control']) !!}
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
                                        {!! Form::select('department', $department, isset($applicantInfo) ? $applicantInfo->applicant_dept_div : null,  ['id'=>'applicantDepartment', 'name'=>'applicantDepartment', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                    </div>
                                    <!-- Form Group (application date)-->
                                    <div class="mb-3 col-md-4">
                                        <label class="small mb-1" for="inputAppDate">Application Date:</label>
                                        {!! Form::text('date', isset($applicantInfo) ? $applicantInfo->date_application : null, ['id' => 'applicant_date', 'name'=>'applicant_date','class' => 'form-control datepicker', 'placeholder' => '----- Please Choose Date -----', 'autocomplete' => 'off']) !!}
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
                                {!! Form::text('verifier_name_1', $applicantInfo->verifier_1_name ?? '', ['id'=>'verifier_name_1', 'name'=>'verifier_name_1', 'class'=>'form-control']) !!}
                            </div>
                            <div class="row col-12">
                            <!-- Form Group (verifier position 1)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerifiedPosition1">Verifier Position I:</label>
                                {!! Form::select('position', $position, isset($applicantInfo) ? $applicantInfo->verifier_1_position : null,  ['id'=>'verifierPosition', 'name'=>'verifierPosition', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                            </div>
                            <!-- Form Group (verifier department/division 1)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerifiedDeptDiv1">Verifier Department / Division I:</label>
                                {!! Form::select('department', $department, isset($applicantInfo) ? $applicantInfo->verifier_1_dept_div : null,  ['id'=>'verifierDepartment', 'name'=>'verifierDepartment', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                            </div>
                            <!-- Form Group (verification date 1)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerificationDate1">Verification Date I:</label>
                                {!! Form::text('date', isset($applicantInfo) ? $applicantInfo->date_verification_1 : null, ['id' => 'verifier_date', 'name'=>'verifier_date', 'class' => 'form-control datepicker', 'placeholder' => '----- Please Choose Date -----', 'autocomplete' => 'off']) !!}
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
                                {!! Form::text('verifier_name_2', $applicantInfo->verifier_2_name ?? '', ['id'=>'verifier_name_2', 'name'=>'verifier_name_2', 'class'=>'form-control']) !!}
                            </div>
                            <div class="row col-12">
                            <!-- Form Group (verifier position 2)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerifiedPosition2">Verifier Position II:</label>
                                {!! Form::select('position', $position, isset($applicantInfo) ? $applicantInfo->verifier_2_position : null,  ['id'=>'verifierPosition2', 'name'=>'verifierPosition2', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                            </div>
                            <!-- Form Group (verifier department/division 2)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerifiedDeptDiv2">Verifier Department / Division II:</label>
                                {!! Form::select('department', $department, isset($applicantInfo) ? $applicantInfo->verifier_2_dept_div : null,  ['id'=>'verifierDepartment2', 'name'=>'verifierDepartment2', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                            </div>
                            <!-- Form Group (verification date 2)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerificationDate2">Verification Date II:</label>
                                {!! Form::text('date', isset($applicantInfo) ? $applicantInfo->date_verification_2 : null, ['id' => 'verifier_date_2', 'name'=>'verifier_date_2', 'class' => 'form-control datepicker', 'placeholder' => '----- Please Choose Date -----', 'autocomplete' => 'off']) !!}

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
                                {!! Form::text('verifier_name_3', $applicantInfo->verifier_3_name ?? '', ['id'=>'verifier_name_3', 'name'=>'verifier_name_3', 'class'=>'form-control']) !!}
                            </div>
                            <div class="row col-12">
                            <!-- Form Group (verifier position 3)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerifiedPosition3">Verifier Position III:</label>
                                {!! Form::select('position', $position, isset($applicantInfo) ? $applicantInfo->verifier_3_position : null,  ['id'=>'verifierPosition3', 'name'=>'verifierPosition3', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                            </div>
                            <!-- Form Group (verifier department/division 3)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerifiedDeptDiv3">Verifier Department / Division III:</label>
                                {!! Form::select('department', $department, isset($applicantInfo) ? $applicantInfo->verifier_3_dept_div : null,  ['id'=>'verifierDepartment3', 'name'=>'verifierDepartment3', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                            </div>
                            <!-- Form Group (verification date 3)-->
                            <div class="mb-3 col-md-4">
                                <label class="small mb-1" for="inputVerificationDate3">Verification Date III:</label>
                                {!! Form::text('date', isset($applicantInfo) ? $applicantInfo->date_verification_3 : null, ['id' => 'verifier_date_3', 'name'=>'verifier_date_3', 'class' => 'form-control datepicker', 'placeholder' => '----- Please Choose Date -----', 'autocomplete' => 'off']) !!}
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

        // Change format date from mm/dd/yy to dd/mm/yy
        $('.datepicker').datepicker({
            dateFormat: 'dd/mm/yy'
        });

        // When submitting the form
        $('#save').on('submit', function(e) {
            // Prevent the default form submission
            e.preventDefault();

            // Get the date value from the input field
            var applicantDateString = $('#applicant_date').val();
            var verifierDateString = $('#verifier_date').val();
            var verifierDate2String = $('#verifier_date_2').val();
            var verifierDate3String = $('#verifier_date_3').val();

            // Parse the date string
            var parts = applicantDateString.split('/');
            var formattedDate = new Date(parts[2], parts[1] - 1, parts[0]);
            var parts1 = verifierDateString.split('/');
            var formattedDate1 = new Date(parts1[2], parts1[1] - 1, parts1[0]);
            var parts2 = verifierDate2String.split('/');
            var formattedDate2 = new Date(parts2[2], parts2[1] - 1, parts2[0]);
            var parts3 = verifierDate3String.split('/');
            var formattedDate3 = new Date(parts3[2], parts3[1] - 1, parts3[0]);

            // Format the date ('YYYY-MM-DD HH:mm:ss') to match the db format
            var formattedDateString = formattedDate.toISOString().slice(0, 10);
            var formattedDateString1 = formattedDate1.toISOString().slice(0, 10);
            var formattedDateString2 = formattedDate2.toISOString().slice(0, 10);
            var formattedDateString3 = formattedDate3.toISOString().slice(0, 10);

            // Set the formatted date value to input back
            $('#applicant_date').val(formattedDateString);
            $('#verifier_date').val(formattedDateString1);
            $('#verifier_date_2').val(formattedDateString2);
            $('#verifier_date_3').val(formattedDateString3);

            // Submit form
            this.submit();
        });



    });

</script>
@endpush
