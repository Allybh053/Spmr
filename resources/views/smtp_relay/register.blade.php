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
                            SMTP Relay Request MyGovUC3.0
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
            <div class="card-header text-center"><h3 class=" text-primary">SMTP Relay Request MyGovUC3.0</h3></div>
            <div class="card-body">
                <form id="formserver" action="{{ route('smtp_relay.saving')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="ticketno">Ticket Number<span style="color: red"> *</span></label>
                        <input class="form-control" id="ticketno" name="ticketno" type="text" value="" required/>
                        @php
                            $randomId = Illuminate\Support\Str::random(9);
                        @endphp
                        <input class="form-control" id="form_id" name="form_id" type="hidden" value="{{ $randomId }}"/>
                    </div>
                    <div class="col-md-6">
                        <label for="type_req">Type Of Request<span style="color: red"> *</span></label>
                        <select class="form-control select2" name="type_req" id="type_req" aria-label="Default select" required>
                            <option selected disabled value="">Select Type Of Request:</option>
                            <option value="1">New Application</option>
                            <option value="2">Service Termination</option>
                            <option value="3">Onboarding Agency</option>
                        </select>
                    </div>
                </div>
                {{--<div class="mb-3" hidden @disabled(true)>
                    <label for="fqdn">System Fully Qualified Domain Name (FQDN)</label>
                    <select class="form-control select2" name="fqdn" id="fqdn">
                        <option selected disabled>Select Domain Name</option>
                        @foreach ($domainsArray as $domain)
                        <option value="{{ $domain['domain_name'] }}">{{ $domain['domain_name'] }}</option>
                        @endforeach
                    </select>
                </div>--}}
                <div class="mb-3">
                    <label for="projectname">Project Name</label>
                    <input class="form-control" id="projectname" name="projectname" type="text" placeholder="" value=""/>
                </div>
                <div class="mb-3">
                    <label for="ownername">System Owner Name<span style="color: red"> *</span></label>
                    <input class="form-control" id="ownername" name="ownername" type="text" placeholder="" value="" required/>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="emailadd">Email Address<span style="color: red"> *</span></label>
                        <input class="form-control" id="emailadd" name="emailadd" type="email" value="" required/>
                    </div>
                    <div class="col-md-6">
                        <label for="phone">Phone Number<span style="color: red"> *</span></label>
                        <input class="form-control" id="phone" name="phone" type="phone" value="" required/>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="ministry">Ministry</label>
                    <select class="form-control select2" name="ministry" id="ministry" aria-label="Default select">
                        <option selected disabled>Select Ministry</option>
                        @foreach ($domainsArray as $domain)
                        <option value="{{ $domain['ministry_name'] }}">{{ $domain['ministry_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="agency">Agency<span style="color: red"> *</span></label>
                        <select class="form-control select2" name="agency" id="agency" aria-label="Default select" required>
                            <option selected disabled>Select Agency</option>
                            @foreach ($domainsArray as $domain)
                            <option value="{{ $domain['agencies_name'] }}">{{ $domain['agencies_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="departowner">Department/Division/Unit</label>
                        <select class="form-control select2" name="departowner" id="departowner" aria-label="Default select">
                            <option selected disabled>Select Department/Division/Unit</option>
                            @foreach ($departmentsArray as $department)
                            <option value="{{ $department['name'] }}">{{ $department['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="agencyadd">Agency Address</label>
                    <textarea class="form-control" id="agencyadd" name="agencyadd" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="justification">Justification</label>
                    <textarea class="form-control" id="justification" name="justification" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="authmethod">Mail Relay Authentication Method<span style="color: red"> *</span></label>
                    <select class="form-control select2" name="authmethod" id="authmethod" required>
                        <option selected disabled>Select Authentication</option>
                        <option value="1">IP Based Authentication Relay</option>
                        <option value="2">Username/Password Authentication Relay</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="mailrelay">Server Mail Relay<span style="color: red"> *</span></label>
                    <select class="form-control select2" name="mailrelay" id="mailrelay" aria-label="Default select" required>
                        <option selected disabled>Select Server</option>
                        @foreach ($serversArray as $server)
                        <option value="{{ $server['hostname'] }}">{{ $server['hostname'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <table class="table table-striped" id="tableA">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">System Name<span style="color: red"> *</span></th>
                                <th scope="col">IP Address<span style="color: red"> *</span></th>
                                <th scope="col">FQDN System<span style="color: red"> *</span></th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="dynamic-inputs-container">
                            <tr class="dynamic-input-row">
                              <td><input type="text" name="tableSpmr[0][server_name]" id="server_name" value="" class="form-control" required /></td>
                              <td><input type="text" name="tableSpmr[0][ipaddress]" id="ipaddress" value="" class="form-control" required /></td>
                              {{--<td>
                                <select class="form-control select2" name="tableSpmr[0][domain]" id="domain" aria-label="Default select2" required>
                                    <option selected disabled></option>
                                    @foreach ($domainsArray as $domain)
                                    <option value="{{ $domain['domain_name'] }}">{{ $domain['domain_name'] }}</option>
                                    @endforeach
                                </select>
                              </td>--}}
                                <td><input type="text" name="tableSpmr[0][domain]" id="domain" value="" class="form-control" required /></td>
                              <td><button class="btn btn-danger remove-row"><i class="me-1" data-feather="minus"></i>Remove</button></td>
                            </tr>
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
                        <label for="ports">SMTP Port Number<span style="color: red"> *</span></label>
                        <select class="form-control select2" name="ports" id="ports" aria-label="Default select" required>
                            <option selected disabled></option>
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
                        <label for="authusername">SMTP Username<span style="color: red"> *</span></label>
                        <input type="text" name="authusername" id="authusername" value="" class="form-control" required>
                    </div>
                    <div class="col-md-6" id="authpassword">
                        <label for="authpassword">SMTP Password<span style="color: red"> *</span></label>
                        <input type="text" name="authpassword" id="authpassword" value="" class="form-control" required>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="emailaddfrom">Email Address System (MAILFROM)<span style="color: red"> *</span></label>
                        <input class="form-control" id="emailaddfrom" name="emailaddfrom" type="text" value="" required/>
                    </div>
                    <div class="col-md-6">
                        <label for="mailbox">MailBox<span style="color: red"> *</span></label>
                        <select class="form-control select2" name="mailbox" id="mailbox" aria-label="Default select" required>
                            <option selected disabled>Select Mailbox</option>
                            <option value="0">Not Answered</option>
                            <option value="1">No</option>
                            <option value="2">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="enday">Estimated number of system users (per day)</label>
                        <input class="form-control" id="enday" name="enday" type="number" pattern="[0-9]*" inputmode="numeric"  value=""/>
                    </div>
                    <div class="col-md-6">
                        <label for="ensystem">Estimated number of emails sent from the system (per day)</label>
                        <input class="form-control" id="ensystem" name="ensystem" type="number" pattern="[0-9]*" inputmode="numeric"  value=""/>
                    </div>
                </div>
                <hr>
                <div class="card-header text-center"><h4 class=" text-primary">Application Verification</h4></div><br>
                <span>I hereby confirm that all information and statements provided in this application form are <b>TRUE</b> and <b>VALID</b>:</span><br><br>
                <div class="mb-3">
                    <label for="applicantname">Applicant Name</label>
                    <input class="form-control" id="applicantname" name="applicantname" type="text" placeholder="" value="">
                </div>
                <div class="row col-12">
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="applicantposition">Applicant's Position:</label>
                        <select class="form-control select2" name="applicantposition" id="applicantposition" aria-label="Default select">
                            <option selected disabled>Select Position</option>
                            @foreach ($positionsArray as $position)
                            <option value="{{ $position['name'] }}">{{ $position['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="applicantdepart">Applicant's Department / Division:</label>
                        <select class="form-control select2" name="applicantdepart" id="applicantdepart" aria-label="Default select">
                            <option selected disabled>Select Department / Division</option>
                            @foreach ($departmentsArray as $department)
                            <option value="{{ $department['name'] }}">{{ $department['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="applicantdate">Date:<span style="color: red"> *</span></label>
                        <input class="form-control" type="date" id="applicantdate" name="applicantdate" required>
                    </div>
                </div>
                <hr>
                <div class="card-header text-center"><h4 class=" text-primary">Verifier I</h4></div><br>
                <div class="mb-3">
                    <label for="verifiername">Verifier Name</label>
                    <input class="form-control" id="verifiername" name="verifiername" type="text" placeholder="" value="">
                </div>
                <div class="row col-12">
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifierposition">Verifier Position:</label>
                        <select class="form-control select2" name="verifierposition" id="verifierposition" aria-label="Default select">
                            <option selected disabled>Select Position</option>
                            @foreach ($positionsArray as $position)
                            <option value="{{ $position['name'] }}">{{ $position['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifierdepart">Verifier Department / Division:</label>
                        <select class="form-control select2" name="verifierdepart" id="verifierdepart" aria-label="Default select">
                            <option selected disabled>Select Department / Division</option>
                            @foreach ($departmentsArray as $department)
                            <option value="{{ $department['name'] }}">{{ $department['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifierdate">Date:</label>
                        <input class="form-control" type="date" id="verifierdate" name="verifierdate">
                    </div>
                </div>
                <hr>
                <div class="card-header text-center"><h4 class=" text-primary">Helpdesk Officer</h4></div><br>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="officername">Officer Name</label>
                        <input class="form-control" id="officername" name="officername" type="text" placeholder="" value="">
                    </div>
                    <div class="col-md-6">
                        <label for="officerdate">Date:</label>
                        <input class="form-control" type="date" id="officerdate" name="officerdate">
                    </div>
                </div>
                <hr>
                <div class="card-header text-center"><h4 class=" text-primary">Approval MAMPU</h4></div><br>
                <div class="mb-3">
                    <label for="approvalname">Approval Name</label>
                    <input class="form-control" id="approvalname" name="approvalname" type="text" placeholder="" value="">
                </div>
                <div class="row col-12">
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="approvalposition">Approval Position</label>
                        <select class="form-control select2" name="approvalposition" id="approvalposition" aria-label="Default select">
                            <option selected disabled>Select Position</option>
                            @foreach ($positionsArray as $position)
                            <option value="{{ $position['name'] }}">{{ $position['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="approvaldepart">Approval Department / Division:</label>
                        <select class="form-control select2" name="approvaldepart" id="approvaldepart" aria-label="Default select">
                            <option selected disabled>Select Department / Division</option>
                            @foreach ($departmentsArray as $department)
                            <option value="{{ $department['name'] }}">{{ $department['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="approvaldate">Date:<span style="color: red"> *</span></label>
                        <input class="form-control" type="date" id="approvaldate" name="approvaldate" required>
                    </div>
                </div>
                <hr>

                <div class="mb-3">
                    <label for="attachment">Upload Attachment</label>
                    <input class="form-control btn-outline-primary" type="file" name="attachment" accept=".pdf"/>
                    <span class="text-xs text-danger">Upload File format .pdf</span>
                </div>
                {{-- <div class="mb-3">
                    <label for="attachement">Upload Attachement</label>
                    <input class="form-control btn-outline-primary" type="file" name="attachement" id="attachement" accept=".pdf"/>
                    <span class="text-xs text-danger">Upload File format .pdf</span>
                </div> --}}

                <hr>
                <button class="btn btn-primary me-2 my-1" type="submit">Submit</button>
                <a class="btn btn-danger me-2 my-1" type="button" href="{{ route('smtp_relay.smtp_relay_req')}}">Cancel</a>
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
        $(this).attr('name', updateNameAttribute(nameAttr, container.find('.dynamic-input-row').length));
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
function updateNameAttribute(nameAttr, newIndex) {
    // Extract the current index from the name attribute
    var regex = /\[(\d+)\]/;
    var matches = regex.exec(nameAttr);
    var currentIndex = matches ? parseInt(matches[1]) : 0;

    // Increment the index for the next row
    //var newIndex = currentIndex + 1;

    // Replace the index in the name attribute
    return nameAttr.replace('[' + currentIndex + ']', '[' + newIndex + ']');
}
</script>
@endpush
