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
                            SMTP Relay Request MyGovUC
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
            <div class="card-header text-center"><h3 class=" text-primary">SMTP Relay Request MyGovUC</h3></div>
            <div class="card-body">
                <form id="formserver" name="formserver" action="{{ route('smtp_relay.saving')}}" method="post" enctype="multipart/form-data">
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
                        <select class="select2 form-control form-control-danger " name="type_req" id="type_req" required onchange="typeChange()">
                            <option disabled selected value="">Select Type Of Request:</option>
                            <option value="1">New Application</option>
                            <option value="2">Service Termination</option>
                            {{-- <option value="3">Onboarding Agency</option> --}}
                        </select>
                    </div>
                </div>
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
                        <input class="form-control" id="phone" name="phone" type="number" value="" pattern="[0-9]*" required/>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="ministry">Ministry<span style="color: red"> *</span></label>
                    <select class="form-control select2" name="ministry" id="ministry" required>
                        <option selected disabled>Select Ministry</option>
                        @foreach ($ministriesArray as $ministry)
                        <option value="{{ $ministry['name'] }}">{{ $ministry['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="agency">Agency<span style="color: red"> *</span></label>
                        <select class="form-control select2" name="agency" id="agency" required>
                            <option disabled selected>Select Agency</option>
                            @foreach ($agenciesArray as $agency)
                            <option value="{{ $agency['name'] }}">{{ $agency['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="departowner">Department/Division/Unit</label>
                        <select class="form-control select2" name="departowner" id="departowner" required>
                            <option disabled selected>Select Department/Division/Unit</option>
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
                <div class="mb-3" id="myauth">
                    <label for="authmethod">Mail Relay Authentication Method<span style="color: red"> *</span></label>
                    <select class="form-control select2" name="authmethod" id="authmethod" required>
                        <option disabled selected>Select Authentication</option>
                        <option value="1">IP Based Authentication Relay</option>
                        <option value="2">Username/Password Authentication Relay</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="mailrelay">Server Mail Relay<span style="color: red"> *</span></label>
                    <select class="form-control select2" name="mailrelay" id="mailrelay" required onchange="toggleInputs()">
                        <option disabled selected>Select Server</option>
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
                                <th scope="col">Domain System<span style="color: red"> *</span></th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="dynamic-inputs-container" name="dynamic-inputs-container">
                            <tr class="dynamic-input-row">
                                <td><input type="text" name="tableSpmr[0][server_name]" id="server_name" value="" class="form-control" required disabled/></td>
                                <td><input type="text" name="tableSpmr[0][ipaddress]" id="ipaddress" value="" class="form-control" required disabled/></td>
                                <td><input type="text" name="tableSpmr[0][domain]" id="domain" value="" class="form-control" required disabled/></td>
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
                        <strong><p style="color: red;" id="resultContainer"></p></strong>
                    </table>
                </div>

                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="ports">SMTP Port Number<span style="color: red"> *</span></label>
                        <select class="form-control select2" name="ports" id="ports" required>
                            <option disabled selected></option>
                            <option value="0">Not Answered</option>
                            <option value="1">25/tcp</option>
                            <option value="2">465/tcp</option>
                            <option value="3">587/tcp</option>
                            <option value="4">25/tcp & 465/tcp</option>
                            <option value="5">25/tcp & 587/tcp</option>
                            <option value="6">465/tcp & 587/tcp</option>
                            <option value="7">All</option>
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
                        <select class="form-control select2" name="mailbox" id="mailbox" required>
                            <option disabled selected>Select Mailbox</option>
                            <option value="0">Not Answered</option>
                            <option value="1">No</option>
                            <option value="2">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="row gx-3 mb-3" id="myest">
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
                    <input class="form-control" id="applicantname" name="applicantname" type="text" placeholder="" value="" required>
                </div>
                <div class="row col-12">
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="applicantposition">Applicant's Position:</label>
                        <select class="form-control select2" name="applicantposition" id="applicantposition" required>
                            <option disabled selected>Select Position</option>
                            @foreach ($positionsArray as $position)
                            <option value="{{ $position['name'] }}">{{ $position['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="applicantdepart">Applicant's Department / Division:</label>
                        <select class="form-control select2" name="applicantdepart" id="applicantdepart" required>
                            <option disabled selected>Select Department / Division</option>
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
                    <input class="form-control" id="verifiername" name="verifiername" type="text" placeholder="" value="" required>
                </div>
                <div class="row col-12">
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifierposition">Verifier Position:</label>
                        <select class="form-control select2" name="verifierposition" id="verifierposition" required>
                            <option disabled selected>Select Position</option>
                            @foreach ($positionsArray as $position)
                            <option value="{{ $position['name'] }}">{{ $position['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifierdepart">Verifier Department / Division:</label>
                        <select class="form-control select2" name="verifierdepart" id="verifierdepart" required>
                            <option disabled selected>Select Department / Division</option>
                            @foreach ($departmentsArray as $department)
                            <option value="{{ $department['name'] }}">{{ $department['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifierdate">Date:</label>
                        <input class="form-control" type="date" id="verifierdate" name="verifierdate" required><br>
                        <span style="color: red"><div id="message"></div></span>
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
                        <input class="form-control" type="date" id="officerdate" name="officerdate"><br>
                        <span style="color: red"><div id="message2"></div></span>
                    </div>
                </div>
                <hr>
                <div class="card-header text-center"><h4 class=" text-primary">Approval JDN</h4></div><br>
                <div class="mb-3">
                    <label for="approvalname">Approval Name</label>
                    <input class="form-control" id="approvalname" name="approvalname" type="text" placeholder="" value="" required>
                </div>
                <div class="row col-12">
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="approvalposition">Approval Position</label>
                        <select class="form-control select2" name="approvalposition" id="approvalposition" required>
                            <option disabled selected>Select Position</option>
                            @foreach ($positionsArray as $position)
                            <option value="{{ $position['name'] }}">{{ $position['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="approvaldepart">Approval Department / Division:</label>
                        <select class="form-control select2" name="approvaldepart" id="approvaldepart" required>
                            <option disabled selected>Select Department / Division</option>
                            @foreach ($departmentsArray as $department)
                            <option value="{{ $department['name'] }}">{{ $department['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="approvaldate">Date:<span style="color: red"> *</span></label>
                        <input class="form-control" type="date" id="approvaldate" name="approvaldate" required><br>
                        <span style="color: red"><div id="message3"></div></span>
                    </div>
                </div>
                <hr>

                <div class="mb-3">
                    <label for="attachment">Upload Attachment<span style="color: red"> *</span></label>
                    <input class="form-control btn-outline-primary" type="file" name="attachment" accept=".pdf" required/>
                    <span class="text-xs text-danger">Upload File format .pdf</span>
                </div>
                <hr>
                <button class="btn btn-primary me-2 my-1" id="submit" name="submit" type="submit">Submit</button>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.2/jquery.validate.min.js"></script>

<script>

    $(document).ready(function() {

        const dateAInput = document.getElementById('applicantdate');
        const dateBInput = document.getElementById('verifierdate');
        const dateCInput = document.getElementById('officerdate');
        const dateDInput = document.getElementById('approvaldate');
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
        $('#approvaldate').on('change', function() {
            var isValid = $(this).valid();
            if (isValid) {
                $(this).removeClass('is-invalid');
            }
        });
        $('#approvaldepart').on('change', function() {
            var isValid = $(this).valid();
            if (isValid) {
                $(this).removeClass('is-invalid');
            }
        });
        $('#approvalposition').on('change', function() {
            var isValid = $(this).valid();
            if (isValid) {
                $(this).removeClass('is-invalid');
            }
        });
        $('#verifierdepart').on('change', function() {
            var isValid = $(this).valid();
            if (isValid) {
                $(this).removeClass('is-invalid');
            }
        });
        $('#verifierdate').on('change', function() {
            var isValid = $(this).valid();
            if (isValid) {
                $(this).removeClass('is-invalid');
                $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            }
        });
        $('#authpassword').on('change', function() {
            var isValid = $(this).valid();
            if (isValid) {
                $(this).removeClass('is-invalid');
                $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            }
        });
        $('#authusername').on('change', function() {
            var isValid = $(this).valid();
            if (isValid) {
                $(this).removeClass('is-invalid');
                $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            }
        });
        $('#type_req').on('change', function() {
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
        $('#agency').on('change', function() {
            var isValid = $(this).valid();
            if (isValid) {
                $(this).removeClass('is-invalid');
                $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            }
        });
        $('#departowner').on('change', function() {
            var isValid = $(this).valid();
            if (isValid) {
                $(this).removeClass('is-invalid');
                $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            }
        });
        $('#authmethod').on('change', function() {
            var isValid = $(this).valid();
            if (isValid) {
                $(this).removeClass('is-invalid');
                $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            }
        });
        $('#mailrelay').on('change', function() {
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
        $('#applicantposition').on('change', function() {
            var isValid = $(this).valid();
            if (isValid) {
                $(this).removeClass('is-invalid');
                $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            }
        });
        $('#applicantdepart').on('change', function() {
            var isValid = $(this).valid();
            if (isValid) {
                $(this).removeClass('is-invalid');
                $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            }
        });
        $('#applicantdate').on('change', function() {
            var isValid = $(this).valid();
            if (isValid) {
                $(this).removeClass('is-invalid');
                $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            }
        });
        $('#verifierposition').on('change', function() {
            var isValid = $(this).valid();
            if (isValid) {
                $(this).removeClass('is-invalid');
                $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            }
        });
    });


    $('#dynamic-inputs-container').on('input', '#ipaddress', function() {
        var type_req = document.getElementById("type_req");
        var selectBox = document.getElementById("mailrelay");
        var selectedValue = selectBox.options[selectBox.selectedIndex].value;
        var selectedValuetype_req = type_req.options[type_req.selectedIndex].value;
        var ipAddress = $(this).val();

        if (/^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$/.test(ipAddress)) {
            const serverAddress = 'wss://spmr.mygovuc.gov.my/' + selectedValue;
            const socket = new WebSocket(serverAddress);

            socket.addEventListener('open', (event) => {
                let command = '';

                if (selectedValuetype_req == 1) {
                    // Construct your command
                    command = `grep -q '^${ipAddress} ' /opt/cluster/postfix/access_list && echo "IP address exists"`;
                }
                if (selectedValuetype_req == 2) {
                    // Construct your command
                    command = `grep -q '^#${ipAddress} ' /opt/cluster/postfix/access_list && echo "IP address exists"`;
                }

                // Send the command through the WebSocket if it's not empty
                if (command) {
                    socket.send(command);
                }
            });

            socket.addEventListener('message', (event) => {
                const response = event.data;

                if (response.includes('IP address exists')) {
                    $('#resultContainer').html(response);
                } else {
                    //console.log("Successfully sent command");
                }

                socket.close();
            });

            socket.addEventListener('error', (error) => {
                //console.error("WebSocket error observed:", error);
            });

            socket.addEventListener('close', (event) => {
                console.log("connection closed");
                socket.close();
            });
        } else {
            // Clear result container if input is invalid
            $('#resultContainer').empty();
        }
    });


    function typeChange() {

    var selectType = document.getElementById("type_req");
    var selectedValueType = selectType.options[selectType.selectedIndex].value;
    var myauth = document.getElementById("myauth");
    var myest = document.getElementById("myest");


    // Check if the selected option is the "Select Server" option
    if (selectedValueType === "2") {
            myauth.style.display = "none";
            myest.style.display = "none";
        }else{
            myauth.style.display = "block";
            myest.style.display = "block";
        }
    }


    function toggleInputs() {

        var selectBox = document.getElementById("mailrelay");
        var selectedValue = selectBox.options[selectBox.selectedIndex].value;


        var tbody = document.getElementById("dynamic-inputs-container"); // Select the tbody by name

        // Check if the selected option is the "Select Server" option
        if (selectedValue === "") {
            // Disable all input fields inside the tbody
            var inputs = tbody.querySelectorAll("input");
            inputs.forEach(function(input) {
                input.disabled = true;
            });
        } else {
            // Enable all input fields inside the tbody
            var inputs = tbody.querySelectorAll("input");
            inputs.forEach(function(input) {
                input.disabled = false;
            });
        }
    }


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

    container.append(newRow);

}

// Function to update the name attribute of inputs
function updateNameAttribute(nameAttr, newIndex) {
    // Extract the current index from the name attribute
    var regex = /\[(\d+)\]/;
    var matches = regex.exec(nameAttr);
    var currentIndex = matches ? parseInt(matches[1]) : 0;

    // Replace the index in the name attribute
    return nameAttr.replace('[' + currentIndex + ']', '[' + newIndex + ']');
}

</script>
@endpush
