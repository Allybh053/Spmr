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
                            Form Registration Mail Relay MyGovUC3.0
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
            <div class="card-header text-center"><h3 class=" text-primary">Form Registration Mail Relay MyGovUC3.0</h3></div>
            <div class="card-body">
                <form id="formserver" action="{{ route('smtp_relay.saving')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row gx-3 mb-3">
                    <!-- Form Group (ticket)-->
                    <div class="col-md-6">
                        <label for="ticketno">Ticket Number</label>
                        <input class="form-control" id="ticketno" name="ticketno" type="text" value="" required/>
                        @php
                            $randomId = Illuminate\Support\Str::random(9);
                        @endphp
                        <input class="form-control" id="form_id" name="form_id" type="hidden" value="{{ $randomId }}" required/>
                    </div>
                    <!-- Form Group (request)-->
                    <div class="col-md-6">
                        <label for="type_req">Type Of Request</label>
                        <select class="form-control select2" name="type_req" id="type_req" aria-label="Default select">
                            <option selected disabled>Select Type Of Request:</option>
                            <option value="1">New Application</option>
                            <option value="2">Service Termination</option>
                            <option value="3">Onboarding Agency</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="fqdn">System Fully Qualified Domain Name (FQDN)</label>
                    <select class="form-control select2" name="fqdn" id="fqdn" required>
                        <option selected disabled>Select Domain Name</option>
                        @foreach ($domainsArray as $domain)
                        <option value="{{ $domain['domain_name'] }}">{{ $domain['domain_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="projectname">Project Name</label>
                    <input class="form-control" id="projectname" name="projectname" type="text" placeholder="" value="" required>
                </div>
                <div class="mb-3">
                    <label for="ownername">System Owner Name</label>
                    <input class="form-control" id="ownername" name="ownername" type="text" placeholder="" value="" required>
                </div>
                <div class="row gx-3 mb-3">
                    <!-- Form Group (email)-->
                    <div class="col-md-6">
                        <label for="emailadd">Email Address</label>
                        <input class="form-control" id="emailadd" name="emailadd" type="email" value="" required/>
                    </div>
                    <!-- Form Group (phone)-->
                    <div class="col-md-6">
                        <label for="phone">Phone Number</label>
                        <input class="form-control" id="phone" name="phone" type="phone" value="" required/>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <!-- Form Group (agency)-->
                    <div class="col-md-6">
                        <label for="agency">Agency</label>
                        <select class="form-control select2" name="agency" id="agency" aria-label="Default select">
                            <option selected disabled>Select Agency</option>
                            @foreach ($domainsArray as $domain)
                            <option value="{{ $domain['agencies_name'] }}">{{ $domain['agencies_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Form Group (depart)-->
                    <div class="col-md-6">
                        <label for="departowner">Department/Division/Unit</label>
                        <select class="form-control select2" name="departowner" id="departowner" aria-label="Default select">
                            <option selected disabled>Select Department/Division/Unit</option>
                            @foreach ($domainsArray as $domain)
                            <option value="{{ $domain['ministry_name'] }}">{{ $domain['ministry_name'] }}</option>
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
                    <label for="authmethod">Mail Relay Authentication Method</label>
                    <select class="form-control select2" name="authmethod" id="authmethod" required>
                        <option selected disabled>Select Authentication:</option>
                        <option value="1">IP Based Authentication Relay</option>
                        <option value="2">Username/Password Authentication Relay</option>
                    </select>
                </div>
                <div class="mb-3">
                    <table class="table table-striped" id="tableA" style="display: none;">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">System Name</th>
                                <th scope="col">IP Address</th>
                                <th scope="col">Port Number</th>
                                <th scope="col">FQDN Sistem</th>
                                <th scope="col">Server Mail Relay</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="dynamic-inputs-container">
                            <tr class="dynamic-input-row">
                              <td><input type="text" name="server_name[]" id="server_name_tableA" value="" class="form-control" required></td>
                              <td><input type="text" name="ipaddress[]" id="ipaddress_tableA" value="" class="form-control" required></td>
                              <td>
                                <select class="form-control select2" name="ports[]" id="ports_tableA" aria-label="Default select" required>
                                    <option selected disabled></option>
                                    <option value="25">25/tcp</option>
                                    <option value="465">465/tcp</option>
                                    <option value="587">587/tcp</option>
                                </select>
                              </td>
                              <td>
                                <select class="form-control select2" name="domain[]" id="domain_tableA" aria-label="Default select" required>
                                    <option selected disabled></option>
                                    @foreach ($domainsArray as $domain)
                                    <option value="{{ $domain['domain_name'] }}">{{ $domain['domain_name'] }}</option>
                                    @endforeach
                                </select>
                              </td>
                              <td>
                                <select class="form-control select2" name="mailrelay[]" id="mailrelay_tableA" aria-label="Default select" required>
                                    <option selected disabled></option>
                                    @foreach ($serversArray as $server)
                                    <option value="{{ $server['hostname'] }}">{{ $server['hostname'] }}</option>
                                    @endforeach
                                </select>
                              </td>
                              <td><button class="btn btn-danger remove-row"><i class="me-1" data-feather="minus"></i>Remove</button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6" style="text-align: right;">
                                    <button id="add-row" type="button" class="btn btn-primary "><i class="me-1" data-feather="plus"></i>Add</button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                    <table class="table table-striped" id="tableB" style="display: none;">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">System Name</th>
                                <th scope="col">IP Address</th>
                                <th scope="col">Port Number</th>
                                <th scope="col">FQDN Sistem</th>
                                <th scope="col">Server Mail Relay</th>
                                <th scope="col">Username</th>
                                <th scope="col">password</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="dynamic-inputs-container">
                            <tr class="dynamic-input-row">
                              <td><input type="text" name="server_name[]" id="server_name_tableB" value="" class="form-control"></td>
                              <td><input type="text" name="ipaddress[]" id="ipaddress_tableB" value="" class="form-control"></td>
                              <td>
                                <select class="form-control select2" name="ports[]" id="ports_tableB" required>
                                    <option selected disabled></option>
                                    <option value="25">25/tcp</option>
                                    <option value="465">465/tcp</option>
                                    <option value="587">587/tcp</option>
                                </select>
                              </td>
                              <td>
                                <select class="form-control select2" name="domain[]" id="domain_tableB" required>
                                    <option selected disabled></option>
                                    @foreach ($domainsArray as $domain)
                                    <option value="{{ $domain['domain_name'] }}">{{ $domain['domain_name'] }}</option>
                                    @endforeach
                                </select>
                              </td>
                              <td>
                                <select class="form-control select2" name="mailrelay[]" id="mailrelay_tableB" aria-label="" required>
                                    <option selected disabled></option>
                                    @foreach ($serversArray as $server)
                                    <option value="{{ $server['hostname'] }}">{{ $server['hostname'] }}</option>
                                    @endforeach
                                </select>
                              </td>
                              <td><input type="text" name="authusername[]" id="authusername" value="" class="form-control" required></td>
                              <td><input type="text" name="authpassword[]" id="authpassword" value="" class="form-control" required></td>
                              <td><button class="btn btn-danger  remove-row"><i class="me-1" data-feather="minus"></i>Remove</button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="8" style="text-align: right;">
                                    <button id="add-row2" type="button" class="btn btn-primary "><i class="me-1" data-feather="plus"></i>Add</button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="row gx-3 mb-3">
                    <!-- Form Group (mail from)-->
                    <div class="col-md-6">
                        <label for="emailaddfrom">Email Address System (MAILFROM)</label>
                        <input class="form-control" id="emailaddfrom" name="emailaddfrom" type="email" value="" required/>
                    </div>
                    <!-- Form Group (mailbox)-->
                    <div class="col-md-6">
                        <label for="mailbox">MailBox</label>
                        <select class="form-control select2" name="mailbox" id="mailbox" aria-label="Default select">
                            <option selected disabled>Select Mailbox</option>
                            <option value="1">No</option>
                            <option value="2">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <!-- Form Group (mail from)-->
                    <div class="col-md-6">
                        <label for="enday">Estimated number of system users (per day)</label>
                        <input class="form-control" id="enday" name="enday" type="number" pattern="[0-9]*" inputmode="numeric"  value="" required/>
                    </div>
                    <!-- Form Group (mailbox)-->
                    <div class="col-md-6">
                        <label for="ensystem">Estimated number of emails sent from the system (per day)</label>
                        <input class="form-control" id="ensystem" name="ensystem" type="number" pattern="[0-9]*" inputmode="numeric"  value="" required/>
                    </div>
                </div>
                <hr>
                <div class="card-header text-center"><h4 class=" text-primary">Application Verification</h4></div><br>
                <span>I hereby confirm that all information and statements provided in this application form are <b>TRUE</b> and <b>VALID</b>:</span><br><br>
                <div class="mb-3">
                    <label for="applicantname">Applicant Name</label>
                    <input class="form-control" id="applicantname" name="applicantname" type="text" placeholder="" value="" required>
                </div>
                <div class="row col-12">
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="applicantposition">Applicant's Position:</label>
                        <select class="form-control select2" name="applicantposition" id="applicantposition" aria-label="Default select">
                            <option selected disabled>Select Position</option>
                            <option value="1">test</option>
                            <option value="2">test</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="applicantdepart">Applicant's Department / Division:</label>
                        <select class="form-control select2" name="applicantdepart" id="applicantdepart" aria-label="Default select">
                            <option selected disabled>Select Department / Division</option>
                            <option value="1">test</option>
                            <option value="2">test</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="applicantdate">Date:</label>
                        <input class="form-control" type="date" id="applicantdate" name="applicantdate">
                    </div>
                </div>
                <hr>
                <div class="card-header text-center"><h4 class=" text-primary">Verifier I</h4></div><br>
                <div class="mb-3">
                    <label for="verifiername">Verifier Name</label>
                    <input class="form-control" id="verifiername" name="verifiername" type="text" placeholder="" value="" required>
                </div>
                <div class="row col-12">
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifierposition">Verifier Position:</label>
                        <select class="form-control select2" name="verifierposition" id="verifierposition" aria-label="Default select">
                            <option selected disabled>Select Position</option>
                            <option value="1">test</option>
                            <option value="2">test</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifierdepart">Verifier Department / Division:</label>
                        <select class="form-control select2" name="verifierdepart" id="verifierdepart" aria-label="Default select">
                            <option selected disabled>Select Department / Division</option>
                            <option value="1">test</option>
                            <option value="2">test</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifierdate">Date:</label>
                        <input class="form-control" type="date" id="verifierdate" name="verifierdate">
                    </div>
                </div>
                <hr>
                <div class="card-header text-center"><h4 class=" text-primary">Helpdesk Oficer</h4></div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="officername">Officer Name</label>
                        <input class="form-control" id="officername" name="officername" type="text" placeholder="" value="" required>
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
                    <input class="form-control" id="approvalname" name="approvalname" type="text" placeholder="" value="" required>
                </div>
                <div class="row col-12">
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="approvalposition">Approval Position</label>
                        <select class="form-control select2" name="approvalposition" id="approvalposition" aria-label="Default select">
                            <option selected disabled>Select Position</option>
                            <option value="1">test</option>
                            <option value="2">test</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="approvaldepart">Approval Department / Division:</label>
                        <select class="form-control select2" name="approvaldepart" id="approvaldepart" aria-label="Default select">
                            <option selected disabled>Select Department / Division</option>
                            <option value="1">test</option>
                            <option value="2">test</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="approvaldate">Date:</label>
                        <input class="form-control" type="date" id="approvaldate" name="approvaldate">
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <label for="attachement">Upload Attachement</label>
                    <input class="form-control btn-primary" type="file" name="attachement" id="attachement" accept=".pdf" required/>
                    <span class="text-xs text-danger">Upload File format .pdf</span>
                </div><hr>
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
        th {
            font-size: var(--bs-body-font-size);
        }
    </style>
</main>


@endsection
