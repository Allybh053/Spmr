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
                            Update SMTP Relay Request MyGovUC3.0
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
            <div class="card-header text-center"><h3 class=" text-primary">Update SMTP Relay Request MyGovUC3.0</h3></div>
            <div class="card-body">
                <form id="formserver" action="{{ route('smtp_relay.kemaskinidata', ['id' => $formsmtp->form_id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="col-md-6">
                    <label for="form_id">Form ID</label><br>
                    <p style="color: #0061f2 !important;">{{ $formsmtp->form_id }}</p>
                    <input class="form-control" id="form_id" name="form_id" type="hidden" value="{{ $formsmtp->form_id }}" disabled/>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="ticket_id">Ticket Number</label><br>
                        {{--<p style="color: #0061f2 !important;">{{ $formsmtp->ticket_id }}</p>--}}
                        <input class="form-control" id="ticket_id" name="ticket_id" type="text" value="{{ old('ticket_id', $formsmtp->ticket_id) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="types_req">Type Of Request</label>
                        <select class="form-control select2" name="types_req" id="types_req" aria-label="Default select">
                            <option value="{{ old('types_req', $formsmtp->types_req) }}">
                            @if ($formsmtp->types_req == 1)
                                New Application
                            @elseif ($formsmtp->types_req == 2)
                                Service Termination
                            @else
                                Onboarding Agency
                            @endif
                            </option>
                            <option value="1">New Application</option>
                            <option value="2">Service Termination</option>
                            <option value="3">Onboarding Agency</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="project_name">Project Name</label>
                    <input class="form-control" id="project_name" name="project_name" type="text" value="{{ old('project_name', $formsmtp->project_name) }}" required>
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
                        <input class="form-control" id="owner_phone_number" name="owner_phone_number" type="phone" value="{{ old('owner_phone_number', $formsmtp->owner_phone_number) }}" required/>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="ministry">Ministry</label>
                    <select class="form-control select2" name="ministry" id="ministry" aria-label="Default select">
                        {{--<option selected disabled>Select Department/Division/Unit</option>--}}
                        <option value="{{ old('ministry', $formsmtp->ministry) }}">{{ old('ministry', $formsmtp->ministry) }}</option>
                        @foreach ($domainsArray as $domain)
                        <option value="{{ $domain['ministry_name'] }}">{{ $domain['ministry_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="agencies">Agency</label>
                        <select class="form-control select2" name="agencies" id="agencies" aria-label="Default select">
                            <option value="{{ old('agencies', $formsmtp->agencies) }}">{{ old('agencies', $formsmtp->agencies) }}</option>
                            @foreach ($domainsArray as $domain)
                            <option value="{{ $domain['agencies_name'] }}">{{ $domain['agencies_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="dept_div_unit">Department/Division/Unit</label>
                        <select class="form-control select2" name="dept_div_unit" id="dept_div_unit" aria-label="Default select">
                            <option value="{{ old('dept_div_unit', $formsmtp->dept_div_unit) }}">{{ old('dept_div_unit', $formsmtp->dept_div_unit) }}</option>
                            @foreach ($domainsArray as $domain)
                            <option value="{{ $domain['ministry_name'] }}">{{ $domain['ministry_name'] }}</option>
                            @endforeach
                        </select>
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
                    <select class="form-control select2" name="smtp_relay_auth_method" id="smtp_relay_auth_method" required>
                        <option value="{{ old('smtp_relay_auth_method', $formsmtp->smtp_relay_auth_method) }}">@if ($formsmtp->smtp_relay_auth_method == 1)
                            IP Based Authentication Relay
                        @else
                            Username/Password Authentication Relay
                        @endif</option>
                        <option value="1">IP Based Authentication Relay</option>
                        <option value="2">Username/Password Authentication Relay</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="smtp_relay_server">Server Mail Relay</label>
                    <select class="form-control select2" name="smtp_relay_server" id="smtp_relay_server" aria-label="Default select" required>
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
                                <th scope="col">FQDN System</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="dynamic-inputs-container">
                            @foreach ($details as $detail)
                                <tr class="dynamic-input-row">
                                    <td hidden><input type="text" name="tableSpmr[{{ $loop->index }}][id]" id="id" value="{{ old('tableSpmr.' . $loop->index . '.id', $detail->id) }}" class="form-control" /></td>
                                    <td>
                                        <input type="text" name="tableSpmr[{{ $loop->index }}][server_name]" id="server_name" value="{{ old('tableSpmr.' . $loop->index . '.server_name', $detail->server_name) }}" class="form-control" required />
                                    </td>
                                    <td><input type="text" name="tableSpmr[{{ $loop->index }}][ip_addr_server]" id="ip_addr_server" value="{{ old('tableSpmr.' . $loop->index . '.ip_addr_server', $detail->ip_addr_server) }}" class="form-control" required /></td>
                                    {{--<td>
                                        <select class="form-control select2" name="tableSpmr[{{ $loop->index }}][fqdn_server]" id="fqdn_server" required>
                                            <option value="{{ old('tableSpmr.' . $loop->index . '.fqdn_server', $detail->fqdn_server) }}">{{ old('tableSpmr.' . $loop->index . '.fqdn_server', $detail->fqdn_server) }}</option>
                                            @foreach ($domainsArray as $domain)
                                            <option value="{{ $domain['domain_name'] }}">{{ $domain['domain_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>--}}
                                        <td><input type="text" name="tableSpmr[{{ $loop->index }}][fqdn_server]" id="fqdn_server" value="{{ old('tableSpmr.' . $loop->index . '.fqdn_server', $detail->fqdn_server) }}" class="form-control" required /></td>
                                        <td hidden><input type="text" name="tableSpmr[{{ $loop->index }}][deactivated]" id="deactivated" value="{{ old('tableSpmr.' . $loop->index . '.deactivated', $detail->deactivated) }}" class="form-control" /></td>
                                    <td><button class="btn btn-danger remove-row"><i class="me-1" data-feather="minus"></i>Remove</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" style="text-align: right;">
                                    <button id="add-row-table" type="button" class="btn btn-primary "><i class="me-1" data-feather="plus"></i>Add</button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="smtp_port">SMTP Port Number</label>
                        <select class="form-control select2" name="smtp_port" id="smtp_port" aria-label="Default select" required>
                            <option value="{{ old('smtp_port', $formsmtp->smtp_port) }}">{{ old('smtp_port', $formsmtp->smtp_port) }} /tcp</option>
                            <option value="0">Not Answered</option>
                            <option value="25">25/tcp</option>
                            <option value="465">465/tcp</option>
                            <option value="587">587/tcp</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for=""></label>
                    </div>
                </div>
                <div class="row gx-3 mb-3" id="authusername" style="display: none;">
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
                    <div class="col-md-6">
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
                        <input class="form-control" id="est_no_system_users_per_day" name="est_no_system_users_per_day" type="number" pattern="[0-9]*" inputmode="numeric"  value="{{ old('est_no_system_users_per_day', $formsmtp->est_no_system_users_per_day) }}" required/>
                    </div>
                    <div class="col-md-6">
                        <label for="est_no_email_sent_from_system_users_per_day">Estimated number of emails sent from the system (per day)</label>
                        <input class="form-control" id="est_no_email_sent_from_system_users_per_day" name="est_no_email_sent_from_system_users_per_day" type="number" pattern="[0-9]*" inputmode="numeric"  value="{{ old('est_no_email_sent_from_system_users_per_day', $formsmtp->est_no_email_sent_from_system_users_per_day) }}" required/>
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
                        <select class="form-control select2" name="applicant_position" id="applicant_position" aria-label="Default select">
                            <option value="{{ old('applicant_position', $formsmtp->applicant_position) }}">{{ old('applicant_position', $formsmtp->applicant_position) }}</option>
                            {{--<option value="Penolong Pengarah F44">Penolong Pengarah F44</option>--}}
                            @foreach ($positionsArray as $position)
                                <option value="{{ $position['name'] }}">{{ $position['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="applicant_dept_div">Applicant's Department / Division:</label>
                        <select class="form-control select2" name="applicant_dept_div" id="applicant_dept_div" aria-label="Default select">
                            <option value="{{ old('applicant_dept_div', $formsmtp->applicant_dept_div) }}">{{ old('applicant_dept_div', $formsmtp->applicant_dept_div) }}</option>
                            {{--<option value="Jabatan Audit Negara/Bahagian Teknologi Maklumat">Jabatan Audit Negara/Bahagian Teknologi Maklumat</option>--}}
                            @foreach ($departmentsArray as $department)
                                <option value="{{ $department['name'] }}">{{ $department['name'] }}</option>
                            @endforeach
                        </select>
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
                        <select class="form-control select2" name="verifier_1_position" id="verifier_1_position" aria-label="Default select">
                            <option value="{{ old('verifier_1_position', $formsmtp->verifier_1_position) }}">{{ old('verifier_1_position', $formsmtp->verifier_1_position) }}</option>
                            {{--<option value="Pegawai Teknologi Maklumat F41">Pegawai Teknologi Maklumat F41</option>
                            <option value="Pegawai Teknologi Maklumat F44">Pegawai Teknologi Maklumat F44</option>--}}
                            @foreach ($positionsArray as $position)
                                <option value="{{ $position['name'] }}">{{ $position['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifier_1_dept_div">Verifier Department / Division:</label>
                        <select class="form-control select2" name="verifier_1_dept_div" id="verifier_1_dept_div" aria-label="Default select">
                            <option value="{{ old('verifier_1_dept_div', $formsmtp->verifier_1_dept_div) }}">{{ old('verifier_1_dept_div', $formsmtp->verifier_1_dept_div) }}</option>
                            {{--<option value="Jabatan Audit Negara/Bahagian Teknologi Maklumat">Jabatan Audit Negara/Bahagian Teknologi Maklumat</option>--}}
                            @foreach ($departmentsArray as $department)
                                <option value="{{ $department['name'] }}">{{ $department['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="date_verification_1">Date:</label>
                        <input class="form-control" type="date" id="date_verification_1" name="date_verification_1" value="{{ old('date_verification_1', date('Y-m-d', strtotime($formsmtp->date_verification_1))) }}">
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
                    </div>
                </div>
                <hr>
                <div class="card-header text-center"><h4 class=" text-primary">Approval MAMPU</h4></div><br>
                <div class="mb-3">
                    <label for="verifier_3_name">Approval Name</label>
                    <input class="form-control" id="verifier_3_name" name="verifier_3_name" type="text" placeholder="" value="{{ old('verifier_3_name', $formsmtp->verifier_3_name) }}" required>
                </div>
                <div class="row col-12">
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifier_3_position">Approval Position</label>
                        <select class="form-control select2" name="verifier_3_position" id="verifier_3_position" aria-label="Default select">
                            <option value="{{ old('verifier_3_position', $formsmtp->verifier_3_position) }}">{{ old('verifier_3_position', $formsmtp->verifier_3_position) }}</option>
                            {{--<option value="PPK MAMPU">PPK MAMPU</option>--}}
                            @foreach ($positionsArray as $position)
                                <option value="{{ $position['name'] }}">{{ $position['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifier_3_dept_div">Approval Department / Division:</label>
                        <select class="form-control select2" name="verifier_3_dept_div" id="verifier_3_dept_div" aria-label="Default select">
                            <option value="{{ old('verifier_3_dept_div', $formsmtp->verifier_3_dept_div) }}">{{ old('verifier_3_dept_div', $formsmtp->verifier_3_dept_div) }}</option>
                            {{--<option value="Pasukan Projek MyGovUC3.0">Pasukan Projek MyGovUC3.0</option>--}}
                            @foreach ($departmentsArray as $department)
                                <option value="{{ $department['name'] }}">{{ $department['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="date_verification_3">Date:</label>
                        <input class="form-control" type="date" id="date_verification_3" name="date_verification_3"  value="{{ old('date_verification_3', date('Y-m-d', strtotime($formsmtp->date_verification_3))) }}">
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <label for="attachement">Current Attachement</label><br>
                    {{--<a href="" target="_blank">test</a>--}}
                    <a href="{{ asset('storage/' . old('upload_copy_attachment', $formsmtp->upload_copy_attachment)) }}" target="_blank">
                        {{ old('upload_copy_attachment', $formsmtp->upload_copy_attachment) }}
                    </a>
                </div>
                <div class="mb-3">
                    <label for="upload_copy_attachment">Upload Attachement</label>
                    <input class="form-control btn-outline-primary" type="file" name="upload_copy_attachment" id="upload_copy_attachment" accept=".pdf"/>
                    <span class="text-xs text-danger">Upload File format .pdf</span>
                </div><hr>
                <a class="btn btn-danger me-2 my-1" type="button" href="{{ route('smtp_relay.smtp_relay_req')}}">Cancel</a>
                @can('smtp_mail_relay_update')
                <button class="btn btn-primary me-2 my-1" type="submit">Update Request Form</button>
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
<script>
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
</script>
@endpush
