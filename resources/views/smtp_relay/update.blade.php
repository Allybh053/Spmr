@extends('layouts.app')
@section('content')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="mail"></i></div>
                            Update SMTP Relay Request MyGovUC
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">

                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main page content-->
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-header text-center"><h3 class=" text-primary">Update SMTP Relay Request MyGovUC</h3></div>
            <div class="card-body">
                <form id="formserver" action="{{ route('smtp_relay.kemaskinidata', ['id' => $formsmtp->form_id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="col-md-6">
                    <label for="form_id">Serial Number</label><br>
                    <p style="color: #0061f2 !important;">{{ $formsmtp->form_id }}</p>
                    <input class="form-control" id="form_id" name="form_id" type="hidden" value="{{ $formsmtp->form_id }}" disabled/>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="ticket_id">Ticket Number</label><br>
                        <input class="form-control" id="ticket_id" name="ticket_id" type="text" value="{{ old('ticket_id', $formsmtp->ticket_id) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="types_req">Type Of Request</label>
                        <select @readonly(true) class="form-control select" name="types_req" id="types_req">
                            <option value="{{ old('types_req', $formsmtp->types_req) }}">
                            @if ($formsmtp->types_req == 1)
                                New Application
                            @elseif ($formsmtp->types_req == 2)
                                Service Termination
                            @else
                                Onboarding Agency
                            @endif
                            </option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="project_name">Project Name</label>
                    <input class="form-control" id="project_name" name="project_name" type="text" value="{{ old('project_name', $formsmtp->project_name) }}">
                </div>
                <div class="mb-3">
                    <label for="system_owner_name">System Owner Name</label>
                    <input class="form-control" id="system_owner_name" name="system_owner_name" type="text" placeholder="" value="{{ old('system_owner_name', $formsmtp->system_owner_name) }}" required>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="owner_email_addr">Email Address</label>
                        <input class="form-control" id="owner_email_addr" name="owner_email_addr" type="email" value="{{ old('owner_email_addr', $formsmtp->owner_email_addr) }}" required/>
                    </div>
                    <div class="col-md-6">
                        <label for="owner_phone_number">Phone Number</label>
                        <input class="form-control" id="owner_phone_number" name="owner_phone_number" type="number" value="{{ old('owner_phone_number', $formsmtp->owner_phone_number) }}" required/>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="ministry">Ministry</label>
                    <select class="form-control select2" name="ministry" id="ministry">
                        <option value="{{ old('ministry', $formsmtp->ministry) }}">{{ old('ministry', $formsmtp->ministry_name) }}</option>
                        @foreach ($ministriesArray as $ministry)
                        <option value="{{ $ministry['id'] }}" data-code="{{ $ministry['id'] }}">{{ $ministry['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="agencies">Agency</label>
                        <select class="form-control select2" name="agencies" id="agencies" aria-label="Default select">
                            <option value="{{ old('agencies', $formsmtp->agencies) }}">{{ old('agencies', $formsmtp->agencies_name) }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="dept_div_unit">Department/Division/Unit</label>
                        <input class="form-control" id="dept_div_unit" name="dept_div_unit" value="{{ old('dept_div_unit', $formsmtp->dept_div_unit) }}"/>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="agencies_addr">Agency Address</label>
                    <textarea class="form-control" id="agencies_addr" name="agencies_addr" rows="3">{{ old('agencies_addr', $formsmtp->agencies_addr) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="req_justifications">Justification</label>
                    <textarea class="form-control" id="req_justifications" name="req_justifications" rows="3">{{ old('req_justifications', $formsmtp->req_justifications) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="smtp_relay_auth_method">Mail Relay Authentication Method</label>
                    <select class="form-control select2" name="smtp_relay_auth_method" id="smtp_relay_auth_method" @if ($formsmtp->types_req == 1) required @endif>
                        <option value="{{ old('smtp_relay_auth_method', $formsmtp->smtp_relay_auth_method) }}">
                        @if ($formsmtp->smtp_relay_auth_method == 1)
                            IP Based Authentication Relay
                        @else
                            Username/Password Authentication Relay
                        @endif</option>
                        <option value="1">IP Based Authentication Relay</option>
                        <option value="2">Username/Password Authentication Relay</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="smtp_relay_server">Server Mail Relays</label>
                    <select class="form-control select2" name="smtp_relay_server" id="smtp_relay_server" aria-label="Default select" required onchange="toggleInputs()">
                        <option value="{{ old('smtp_relay_server', $formsmtp->smtp_relay_server) }}">{{ old('smtp_relay_server', $formsmtp->smtp_relay_server) }} </option>
                        @foreach ($serversArray as $server)
                        <option value="{{ $server['hostname'] }}">{{ $server['hostname'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <table class="table table-striped" id="tableA">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">System Name</th>
                                <th scope="col">IP Address</th>
                                <th scope="col">Domain System</th>
                                <th scope="col">MailFrom</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="dynamic-inputs-container">
                            @foreach ($details as $detail)
                                <tr class="dynamic-input-row">
                                    <td hidden><input type="text" name="tableSpmr[{{ $loop->index }}][form_id]" id="form_id" value="{{ $formsmtp->form_id }}" class="form-control" /></td>
                                    <td hidden><input type="text" name="tableSpmr[{{ $loop->index }}][id]" id="id" value="{{ old('tableSpmr.' . $loop->index . '.id', $detail->id) }}" class="form-control" /></td>
                                    <td hidden><input type="text" name="tableSpmr[{{ $loop->index }}][types_req]" id="types_req" value="{{ old('tableSpmr.' . $loop->index . '.types_req', $formsmtp->types_req) }}" class="form-control" /></td>
                                    <td>
                                        <input type="text" name="tableSpmr[{{ $loop->index }}][server_name]" id="server_name" value="{{ old('tableSpmr.' . $loop->index . '.server_name', $detail->server_name) }}" class="form-control" required />
                                    </td>
                                    <td><input type="text" name="tableSpmr[{{ $loop->index }}][ip_addr_server]" id="ip_addr_server" value="{{ old('tableSpmr.' . $loop->index . '.ip_addr_server', $detail->ip_addr_server) }}" class="form-control ipaddress-input" required /></td>
                                    <td><input type="text" name="tableSpmr[{{ $loop->index }}][fqdn_server]" id="fqdn_server" value="{{ old('tableSpmr.' . $loop->index . '.fqdn_server', $detail->fqdn_server) }}" class="form-control" required /></td>
                                    <td><input type="text" name="tableSpmr[{{ $loop->index }}][mailfrom]" id="mailfrom" value="{{ old('tableSpmr.' . $loop->index . '.mailfrom', $detail->mailfrom) }}" class="form-control" required /></td>
                                    <td hidden><input type="text" name="tableSpmr[{{ $loop->index }}][deactivated]" id="deactivated" value="{{ old('tableSpmr.' . $loop->index . '.deactivated', $detail->deactivated) }}" class="form-control" /></td>
                                    <td><button class="btn btn-danger remove-row"><i class="me-1" data-feather="minus"></i>Remove</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" style="text-align: right;">
                                    <button id="add-row-table" type="button" class="btn btn-primary "><i class="me-1" data-feather="plus"></i>Add</button>
                                </th>
                            </tr>
                        </tfoot>
                        <strong><p style="color: red;" id="resultContainer"></p></strong>
                        <strong><p style="color: red;" id="resultContainer3"></p></strong>
                    </table>
                </div>

                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="smtp_port">SMTP Port Number</label>
                        <select class="form-control select2" name="smtp_port" id="smtp_port" aria-label="Default select" required>
                            <!-- The default option will be updated via JavaScript -->
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for=""></label>
                    </div>
                </div>
                <div class="row gx-3 mb-3" id="authusername" @if ($formsmtp->smtp_relay_auth_method == 1) style="display: none;" @endif>
                    <div class="col-md-6">
                        <label for="smtp_username">SMTP Username</label>
                        <input type="text" name="smtp_username" id="smtp_username" value="{{ old('smtp_username', $formsmtp->smtp_username) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6" id="authpassword">
                        <label for="smtp_password">SMTP Password</label>
                        <input type="text" name="smtp_password" id="smtp_password" value="{{ old('smtp_password', $formsmtp->smtp_password) }}" class="form-control" required>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6" hidden @disabled(true)>
                        <label for="mailfrom_email_addr">Email Address System (MAILFROM)</label>
                        <input class="form-control" id="mailfrom_email_addr" name="mailfrom_email_addr" type="text" value="{{ old('mailfrom_email_addr', $formsmtp->mailfrom_email_addr) }}" required/>
                    </div>
                    <div class="col-md-6">
                        <label for="mailbox_created">MailBox</label>
                        <select class="form-control select2" name="mailbox_created" id="mailbox_created" aria-label="Default select">
                            <option value="{{ old('mailbox_created', $formsmtp->mailbox_created) }}">@if ($formsmtp->mailbox_created==1)
                                No MailBox
                            @else
                                Yes
                            @endif</option>
                            <option value="0">Not Answered</option>
                            <option value="1">No</option>
                            <option value="2">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="est_no_system_users_per_day">Estimated number of system users (per day)</label>
                        <input class="form-control" id="est_no_system_users_per_day" name="est_no_system_users_per_day" type="number" pattern="[0-9]*" inputmode="numeric"  value="{{ old('est_no_system_users_per_day', $formsmtp->est_no_system_users_per_day) }}"/>
                    </div>
                    <div class="col-md-6">
                        <label for="est_no_email_sent_from_system_users_per_day">Estimated number of emails sent from the system (per day)</label>
                        <input class="form-control" id="est_no_email_sent_from_system_users_per_day" name="est_no_email_sent_from_system_users_per_day" type="number" pattern="[0-9]*" inputmode="numeric"  value="{{ old('est_no_email_sent_from_system_users_per_day', $formsmtp->est_no_email_sent_from_system_users_per_day) }}"/>
                    </div>
                </div>
                <hr>
                <div class="card-header text-center"><h4 class=" text-primary">Application Verification</h4></div><br>
                <span>I hereby confirm that all information and statements provided in this application form are <b>TRUE</b> and <b>VALID</b>:</span><br><br>
                <div class="mb-3">
                    <label for="applicant_name">Applicant Name</label>
                    <input class="form-control" id="applicant_name" name="applicant_name" type="text" value="{{ old('applicant_name', $formsmtp->applicant_name) }}" required>
                </div>
                <div class="row col-12">
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="applicant_position">Applicant's Position:</label>
                        {{-- @if ($formsmtp->types_req == 1) --}}
                        <input class="form-control" id="applicant_position" name="applicant_position" value="{{ old('applicant_position', $formsmtp->applicant_position) }}"/>
                        {{-- @else
                        <select class="form-control select2" name="applicant_position" id="applicant_position" aria-label="Default select">
                            <option value="{{ old('applicant_position', $formsmtp->applicant_position) }}">{{ old('applicant_position', $formsmtp->applicant_position) }}</option>
                            @foreach ($positionsArray as $position)
                                <option value="{{ $position['name'] }}">{{ $position['name'] }}</option>
                            @endforeach
                        </select>
                        @endif --}}
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="applicant_dept_div">Applicant's Department / Division:</label>
                        {{-- @if ($formsmtp->types_req == 1) --}}
                        <input class="form-control" id="applicant_dept_div" name="applicant_dept_div" value="{{ old('applicant_dept_div', $formsmtp->applicant_dept_div) }}"/>
                        {{-- @else
                        <select class="form-control select2" name="applicant_dept_div" id="applicant_dept_div" aria-label="Default select">
                            <option value="{{ old('applicant_dept_div', $formsmtp->applicant_dept_div) }}">{{ old('applicant_dept_div', $formsmtp->applicant_dept_div) }}</option>
                            @foreach ($departmentsArray as $department)
                                <option value="{{ $department['name'] }}">{{ $department['name'] }}</option>
                            @endforeach
                        </select>
                        @endif --}}
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="date_application">Date:</label>
                        <input class="form-control" type="date" id="date_application" name="date_application" value="{{ old('date_application', date('Y-m-d', strtotime($formsmtp->date_application))) }}">
                    </div>
                </div>
                <hr>
                <div class="card-header text-center"><h4 class=" text-primary">Verifier I</h4></div><br>
                <div class="mb-3">
                    <label for="verifier_1_name">Verifier Name</label>
                    <input class="form-control" id="verifier_1_name" name="verifier_1_name" type="text" placeholder="" value="{{ old('verifier_1_name', $formsmtp->verifier_1_name) }}" required>
                </div>
                <div class="row col-12">
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifier_1_position">Verifier Position:</label>
                        {{-- @if ($formsmtp->types_req == 1) --}}
                        <input class="form-control" id="verifier_1_dept_div" name="verifier_1_dept_div" value="{{ old('verifier_1_dept_div', $formsmtp->verifier_1_dept_div) }}"/>
                        {{-- @else
                        <select class="form-control select2" name="verifier_1_position" id="verifier_1_position" aria-label="Default select">
                            <option value="{{ old('verifier_1_position', $formsmtp->verifier_1_position) }}">{{ old('verifier_1_position', $formsmtp->verifier_1_position) }}</option>
                            @foreach ($positionsArray as $position)
                                <option value="{{ $position['name'] }}">{{ $position['name'] }}</option>
                            @endforeach
                        </select>
                        @endif --}}
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifier_1_dept_div">Verifier Department / Division:</label>
                        {{-- @if ($formsmtp->types_req == 1) --}}
                        <input class="form-control" id="verifier_1_dept_div" name="verifier_1_dept_div" value="{{ old('verifier_1_dept_div', $formsmtp->verifier_1_dept_div) }}"/>
                        {{-- @else
                        <select class="form-control select2" name="verifier_1_dept_div" id="verifier_1_dept_div" aria-label="Default select">
                            <option value="{{ old('verifier_1_dept_div', $formsmtp->verifier_1_dept_div) }}">{{ old('verifier_1_dept_div', $formsmtp->verifier_1_dept_div) }}</option>
                            @foreach ($departmentsArray as $department)
                                <option value="{{ $department['name'] }}">{{ $department['name'] }}</option>
                            @endforeach
                        </select>
                        @endif --}}
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="date_verification_1">Date:</label>
                        <input class="form-control" type="date" id="date_verification_1" name="date_verification_1" value="{{ old('date_verification_1', date('Y-m-d', strtotime($formsmtp->date_verification_1))) }}">
                        <span style="color: red"><div id="message"></div></span>
                    </div>
                </div>
                <hr>
                <div class="card-header text-center"><h4 class=" text-primary">Helpdesk Officer</h4></div><br>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="verifier_2_name">Officer Name</label>
                        <input class="form-control" id="verifier_2_name" name="verifier_2_name" type="text" value="{{ old('verifier_2_name', $formsmtp->verifier_2_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="date_verification_2">Date:</label>
                        <input class="form-control" type="date" id="date_verification_2" name="date_verification_2"  value="{{ old('date_verification_2', date('Y-m-d', strtotime($formsmtp->date_verification_2))) }}">
                        <span style="color: red"><div id="message2"></div></span>
                    </div>
                </div>
                <hr>
                <div class="card-header text-center"><h4 class=" text-primary">Approval JDN</h4></div><br>
                <div class="mb-3">
                    <label for="verifier_3_name">Approval Name</label>
                    <input class="form-control" id="verifier_3_name" name="verifier_3_name" type="text" placeholder="" value="{{ old('verifier_3_name', $formsmtp->verifier_3_name) }}" required>
                </div>
                <div class="row col-12">
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifier_3_position">Approval Position</label>
                        {{-- @if ($formsmtp->types_req == 1) --}}
                        <input class="form-control" id="verifier_3_position" name="verifier_3_position" value="{{ old('verifier_3_position', $formsmtp->verifier_3_position) }}"/>
                        {{-- @else
                        <select class="form-control select2" name="verifier_3_position" id="verifier_3_position" aria-label="Default select">
                            <option value="{{ old('verifier_3_position', $formsmtp->verifier_3_position) }}">{{ old('verifier_3_position', $formsmtp->verifier_3_position) }}</option>
                            @foreach ($positionsArray as $position)
                                <option value="{{ $position['name'] }}">{{ $position['name'] }}</option>
                            @endforeach
                        </select>
                        @endif --}}
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifier_3_dept_div">Approval Department / Division:</label>
                        {{-- @if ($formsmtp->types_req == 1) --}}
                        <input class="form-control" id="verifier_3_dept_div" name="verifier_3_dept_div" value="{{ old('verifier_3_dept_div', $formsmtp->verifier_3_dept_div) }}"/>
                        {{-- @else
                        <select class="form-control select2" name="verifier_3_dept_div" id="verifier_3_dept_div" aria-label="Default select">
                            <option value="{{ old('verifier_3_dept_div', $formsmtp->verifier_3_dept_div) }}">{{ old('verifier_3_dept_div', $formsmtp->verifier_3_dept_div) }}</option>
                            @foreach ($departmentsArray as $department)
                                <option value="{{ $department['name'] }}">{{ $department['name'] }}</option>
                            @endforeach
                        </select>
                        @endif --}}
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="date_verification_3">Date:</label>
                        <input class="form-control" type="date" id="date_verification_3" name="date_verification_3"  value="{{ old('date_verification_3', date('Y-m-d', strtotime($formsmtp->date_verification_3))) }}">
                        <span style="color: red"><div id="message3"></div></span>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <label for="attachement">Current Attachement</label><br>
                    @if ($formsmtp->upload_copy_attachment)
                        <a href="{{ route('smtp_relay.viewPdf',['filename' => $formsmtp->upload_copy_attachment]) }}" target="_blank">{{$formsmtp->upload_copy_attachment}}</a></a>
                    @else
                        <span class="text-muted">No Attachment</span>
                    @endif

                </div>
                <div class="mb-3">
                    <label for="upload_copy_attachment">Replace current Attachement (Optional)</label>
                    <input class="form-control btn-outline-primary" type="file" name="upload_copy_attachment" id="upload_copy_attachment" accept=".pdf"/>
                    <span class="text-xs text-danger">Upload File format .pdf</span>
                </div><hr>
                <a class="btn btn-danger me-2 my-1" type="button" href="{{ route('smtp_relay.smtp_relay_req')}}">Cancel</a>
                @can('smtp_mail_relay_update')
                <button class="btn btn-primary me-2 my-1" id="submit" name="submit" type="submit">Update Request Form</button>
                @endcan
                </form>
            </div>
        </div>
    </div>
    <style>
        label {
            font-weight: bold;
        }
        th,td {
            font-size: var(--bs-body-font-size);
        }
    </style>
</main>


@endsection
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.2/jquery.validate.min.js"></script>

<script>
    $(document).ready(function() {

    // Function to check for duplicate IP addresses
    function checkDuplicateIP() {
        var ipAddresses = [];
        var isDuplicate = false;

        $('.ipaddress-input').each(function() {
            var ip = $(this).val();
            if (ip && ipAddresses.includes(ip)) {
                isDuplicate = true;
                var response = "IP address already Keyin in previous row!";
                $(this).addClass('is-invalid');
                $('#resultContainer3').html(response);
            } else {
                $(this).removeClass('is-invalid');
                $('#resultContainer3').empty(response);
                ipAddresses.push(ip);
            }
        });

        return isDuplicate;
    }

// Event listener for changes to the IP address input fields
$(document).on('input', '.ipaddress-input', function() {
    checkDuplicateIP();
});

// Add event listener for removing rows
$(document).on('click', '.remove-row', function() {
    $(this).closest('tr').remove();
    checkDuplicateIP(); // Recheck IP addresses after a row is removed
});

        $('#ministry').change(function() {
            var ministry = $(this).val();
            var ministryCode = $(this).find(':selected').data('code');

            if (ministryCode) {
                $.ajax({
                    url: '/smtp_relay/agency/' + ministryCode,
                    type: 'GET',
                    success: function(data) {
                        //$('#agencies').empty().prop('disabled', false);
                        $('#agencies').append(new Option("Select Agency", ""));
                        $.each(data, function(id, name) {
                            $('#agencies').append(new Option(name, id));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                //$('#agency').empty().prop('disabled', true);
            }
        });

        $('#dynamic-inputs-container').on('input', '#ip_addr_server', function() {
            var type_req = document.getElementById("types_req");
            var selectBox = document.getElementById("smtp_relay_server");
            var selectedValuesmtp_relay_server = selectBox.options[selectBox.selectedIndex].value;
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            var selectedValuetype_req = type_req.options[type_req.selectedIndex].value;
            var ipAddress = $(this).val();
            var submitBtn = document.getElementById("submit");


            if (/^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$/.test(ipAddress)) {
                const serverAddress = 'wss://spmr.mygovuc.gov.my/' + selectedValuesmtp_relay_server;
                const socket = new WebSocket(serverAddress);

                socket.addEventListener('open', (event) => {
                    let command = '';

                    if (selectedValuetype_req == 1) {
                        // Construct your command
                        command = `grep -q '^${ipAddress}' /opt/cluster/postfix/access_list && echo "IP address already Registered!"`;
                    }
                    if (selectedValuetype_req == 2) {
                        // Construct your command
                        command = `grep -q '^#${ipAddress}' /opt/cluster/postfix/access_list && echo "IP address already Terminated!" || (grep -q '^${ipAddress}' /opt/cluster/postfix/access_list || echo "IP address not Registered to Terminate!")`;
                        // command = `grep -q '^#${ipAddress}' /opt/cluster/postfix/access_list && echo "IP address already Terminated!"`;
                    }

                    // Send the command through the WebSocket if it's not empty
                    if (command) {
                        socket.send(command);
                    }
                });

                socket.addEventListener('message', (event) => {
                    const response = event.data;
                    if (selectedValuetype_req == 1) {
                    if (response.includes('IP address already Registered!')) {
                            $('#resultContainer').html(response);
                        } else {
                            $('#resultContainer').empty();
                        }
                    }
                    if (selectedValuetype_req == 2) {
                    if (response.includes('IP address already Terminated!')) {
                        $('#resultContainer').html(response);
                        submitBtn.disabled = true;
                    } else if (response.includes('IP address not Registered to Terminate!')) {
                        $('#resultContainer').html(response);
                        submitBtn.disabled = true;
                    } else {
                        const messages = "IP Address not Registered to Terminate";
                        $('#resultContainer').empty();
                        submitBtn.disabled = false;
                    }
                    }

                    socket.close();
                });

                socket.addEventListener('error', (error) => {
                    // console.error("WebSocket error observed:", error);
                });

                socket.addEventListener('close', (event) => {
                    // console.log("WebSocket connection closed");
                    socket.close();
                });
            } else {
                // Clear result container if input is invalid
                $('#resultContainer').empty();
            }
        });


        const dateAInput = document.getElementById('date_application');
        const dateBInput = document.getElementById('date_verification_1');
        const dateCInput = document.getElementById('date_verification_2');
        const dateDInput = document.getElementById('date_verification_3');
        const messageDiv = document.getElementById('message');
        const messageDiv2 = document.getElementById('message2');
        const messageDiv3 = document.getElementById('message3');

        function compareDates() {
            var dateA = new Date(dateAInput.value);
            var dateB = new Date(dateBInput.value);

            if (dateB && dateA) {
                if (dateB < dateA) {
                    messageDiv.textContent = "Date Verifier must be greater than Date Applicant";
                } else {
                    messageDiv.textContent = "";
                }
            }
        }

        function compareDates2() {

            var dateB = new Date(dateBInput.value);
            var dateC = new Date(dateCInput.value);

            if (dateB && dateC) {
                if ( dateC < dateB ) {
                    messageDiv2.textContent = "Date Helpdesk must be greater than Date Verifier";
                } else {
                    messageDiv3.textContent = "";
                }
            }
        }

        function compareDates3() {
            var dateC = new Date(dateCInput.value);
            var dateD = new Date(dateDInput.value);

            if (dateD && dateC) {
                if (dateD < dateC) {
                    messageDiv3.textContent = "Date Approval must be greater than Date Helpdesk";

                } else {
                    messageDiv2.textContent = "";
                }
            }
        }

        dateBInput.addEventListener('change', compareDates);
        dateCInput.addEventListener('change', compareDates2);
        dateDInput.addEventListener('change', compareDates3);

    $(".select2").select2();

    $("#formserver").validate({
        errorPlacement: function(label, element) {
            if (element.hasClass('select2')) {
                label.insertAfter(element.next('.select2-container')).addClass('mt-2 text-danger');
            } else {
                label.addClass('mt-2 text-danger');
                label.insertAfter(element);
            }
        },
        highlight: function(element) {
            $(element).parent().addClass('is-invalid');
            $(element).addClass('form-control-danger');
        },
        unhighlight: function(element) {
            $(element).parent().removeClass('is-invalid');
            $(element).removeClass('form-control-danger');
        },
        success: function(label, element) {
            if ($(element).hasClass('select2-hidden-accessible')) {
                label.remove();
            } else {
                label.remove();
            }
        }
    });
    $('#date_verification_3').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
        }
    });
    $('#verifier_3_dept_div').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
        }
    });
    $('#verifier_3_position').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
        }
    });
    $('#verifier_1_dept_div').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
        }
    });
    $('#date_verification_1').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });
    $('#smtp_username').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });
    $('#smtp_password').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });
    $('#types_req').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });
    $('#ministry').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });
    $('#agencies').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });
    $('#dept_div_unit').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });
    $('#smtp_relay_auth_method').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });
    $('#smtp_relay_server').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });
    $('#ports').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });
    $('#mailbox').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });
    $('#applicant_position').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });
    $('#applicant_dept_div').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });
    $('#date_application').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });
    $('#verifier_1_position').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
        }
    });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // The smtpPort value passed from PHP to JavaScript
        var smtpPort = @json($formsmtp->smtp_port);

        // Define the mapping from port value to description
        var portDescriptions = {
            0: 'Not Answered',
            1: '25/tcp',
            2: '465/tcp',
            3: '587/tcp',
            4: '25/tcp & 465/tcp',
            5: '25/tcp & 587/tcp',
            6: '465/tcp & 587/tcp',
            7: 'All'
        };

        // Get the select element
        var selectElement = document.getElementById('smtp_port');

        // Create the default option
        var defaultOption = document.createElement('option');
        defaultOption.value = smtpPort;
        defaultOption.textContent = portDescriptions[smtpPort] || 'Unknown';
        //selectElement.appendChild(defaultOption);

        // Add the other options
        for (var value in portDescriptions) {
            if (portDescriptions.hasOwnProperty(value)) {
                var option = document.createElement('option');
                option.value = value;
                option.textContent = portDescriptions[value];
                selectElement.appendChild(option);
            }
        }

        // Set the default selected value
        selectElement.value = smtpPort;
    });

    // Add row button click handler
$('#add-row-table').click(function () {
    addRow('#dynamic-inputs-container');
});

// Reusable function to add a row to the specified table
function addRow(containerId) {
    var container = $(containerId);
    var newRow = container.find('.dynamic-input-row').first().clone();

    // Find all input elements inside the cloned row and update their names
    newRow.find('input').each(function () {
        var nameAttr = $(this).attr('name');
        $(this).attr('name', updateNameAttribute(containerId, nameAttr));
        $(this).val(''); // Optionally, clear input values
    });

    // Find the select element inside the cloned row and update its name
    //var selectElement = newRow.find('select');
    //var nameAttr = selectElement.attr('name');
    //selectElement.attr('name', updateNameAttribute(nameAttr));

    // Append the cloned row to the container
    container.append(newRow);

    // Initialize Select2 for the cloned dropdown
    /*selectElement.select2({
        allowClear: true,
        tags: true,
    });*/
}

// Function to update the name attribute of inputs
function updateNameAttribute(containerId, nameAttr) {
    var container = $(containerId);
    var existingRows = container.find('.dynamic-input-row');

    // Extract the current index from the name attribute
    var regex = /\[(\d+)\]/;
    var matches = regex.exec(nameAttr);
    var currentIndex = matches ? parseInt(matches[1]) : 0;

    // Increment the index for the next row
    var newIndex = currentIndex + existingRows.length;

    // Replace the index in the name attribute
    return nameAttr.replace('[' + currentIndex + ']', '[' + newIndex + ']');
}
document.getElementById('submit').addEventListener('click', function() {
            // Show the SweetAlert "Loading..." message
            Swal.fire({
                title: 'Loading...',
                text: 'Please wait while we process your request.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    // Submit the form after the SweetAlert is shown
                    document.getElementById('formserver').submit();
                }
            });
        });
</script>
@endpush
