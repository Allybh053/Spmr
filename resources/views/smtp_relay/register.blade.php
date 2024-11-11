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
        <div class="card mb-4" id="registerpage">
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
                        <select class="select2 form-control form-control-danger " name="type_req" id="type_req" required>
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
                        <option value="{{ $ministry['id'] }}" data-code="{{ $ministry['id'] }}">{{ $ministry['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="agency">Agency<span style="color: red"> *</span></label>
                        <select class="form-control select2" name="agency" id="agency" disabled required>
                            <option disabled selected>Select Agency</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="departowner">Department/Division/Unit</label>
                        <input class="form-control" name="departowner" id="departowner" value=""> </input>
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
                    <select class="form-control select2" name="authmethod" id="authmethod">
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
                    <input type="hidden" id="subhostname" name="subhostname" value="">
                </div>
                <div class="mb-3">
                    <table class="table table-striped" id="tableA">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">System Name<span style="color: red"> *</span></th>
                                <th scope="col">IP Address<span style="color: red"> *</span></th>
                                <th scope="col">Domain System<span style="color: red"> *</span></th>
                                <th scope="col">MailFrom</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="dynamic-inputs-container" name="dynamic-inputs-container">
                            <tr class="dynamic-input-row">
                                <td><input type="text" name="tableSpmr[0][server_name]" id="server_name" value="" class="form-control" required disabled/></td>
                                <td><input type="text" name="tableSpmr[0][ipaddress]" id="ipaddress" value="" class="form-control ipaddress-input" required disabled/></td>
                                <td><input type="text" name="tableSpmr[0][domain]" id="domain" value="" class="form-control" required disabled/></td>
                                <td><input type="text" name="tableSpmr[0][mailfrom]" id="mailfrom" value="" class="form-control" disabled/></td>
                                <td><button class="btn btn-danger remove-row"><i class="me-1" data-feather="minus"></i>Remove</button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" style="text-align: right;">
                                    <button id="add-row-table" type="button" class="btn btn-primary "><i class="me-1" data-feather="plus"></i>Add</button>
                                </th>
                            </tr>
                        </tfoot>
                        <strong><p style="color: red;" id="resultContainerEror"></p></strong>
                        <strong><p style="color: red;" id="resultContainer"></p></strong>
                        <strong><p style="color: red;" id="resultContainer2"></p></strong>
                        <strong><p style="color: red;" id="resultContainer3"></p></strong>
                        <strong><p style="color: red;" id="resultContainer4"></p></strong>
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
                    <div class="col-md-6" hidden @disabled(true)>
                        <label for="emailaddfrom">Email Address System (MAILFROM)<span style="color: red"> *</span></label>
                        <input class="form-control" id="emailaddfrom" name="emailaddfrom" type="text" value="" required/>
                    </div>
                    <div class="col-md-6">
                        <label for="mailbox">MailBox<span style="color: red"> *</span></label>
                        <select class="form-control select2" name="mailbox" id="mailbox" required>
                            <option disabled selected>Select Mailbox</option>
                            {{-- <option value="0">Not Answered</option> --}}
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
                    <input class="form-control" id="applicantname" name="applicantname" type="text" placeholder="" value="" required>
                </div>
                <div class="row col-12">
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="applicantposition">Applicant's Position:</label>
                        <input class="form-control" name="applicantposition" id="applicantposition" >
                        </input>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="applicantdepart">Applicant's Department / Division:</label>
                        <input class="form-control" name="applicantdepart" id="applicantdepart" >
                        </input>
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
                        <input class="form-control" name="verifierposition" id="verifierposition" value="" >
                        </input>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="verifierdepart">Verifier Department / Division:</label>
                        <input class="form-control" name="verifierdepart" id="verifierdepart" value="" >
                        </input>
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
                        <input class="form-control" name="approvalposition" id="approvalposition" value="" >
                        </input>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class=" mb-1" for="approvaldepart">Approval Department / Division:</label>
                        <input class="form-control" name="approvaldepart" id="approvaldepart" value="" >
                        </input>
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
                    <input class="form-control btn-outline-primary" id="upload" type="file" name="attachment" accept=".pdf" required/>
                    <span class="text-xs text-danger">Upload a file in PDF format that is no larger than 5MB.</span>
                </div>
                <hr>
                <div class="mb-3">
                <button class="btn btn-primary me-2 my-1" id="submit" name="submit" type="submit">Submit</button>
                <a class="btn btn-danger me-2 my-1" type="button" href="{{ route('smtp_relay.smtp_relay_req')}}">Cancel</a>
                </div>
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
        #registerpage {
            display: none;
        }
    </style>
</main>

@endsection
@push('script')
<script src="{{ asset('js/jquery-3.6.4.min.js') }}"></script>
<link rel="stylesheet" href="{{asset('css/select2.min.css')}}">
<script src="{{asset('js/select2.min.js')}}"></script>
<script src="{{asset('js/jquery.validate.min.js')}}"></script>

<script>

window.addEventListener('load', function() {
    console.log('Page is fully loaded, including all frames and resources');
    document.getElementById('registerpage').style.display = 'block';
});


document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formserver');

    form.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent the default form submission
            // Perform any custom actions here
            //alert("Click button Submit to sumit the form.");
        }
    });
});


    $(document).ready(function() {

        $('#upload').on('change', function(e) {
            let size = this.files[0].size;
            //$('#size').text(size);

            if (size > 5000000) {
                alert('The file size is greater than 5MB.');
                $(this).val('');
            }
        });

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
                        $('#agency').empty().prop('disabled', false);
                        $('#agency').append(new Option("Select Agency", ""));
                        $.each(data, function(id, name) {
                            $('#agency').append(new Option(name, id));
                        });
                    },
                    error: function(xhr, status, error) {
                        //console.error(error);
                    }
                });
            } else {
                $('#agency').empty().prop('disabled', true);
            }
        });

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
                    messageDiv2.textContent = "";
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
                    messageDiv3.textContent = "";
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
        $('#verifierdate').on('change', function() {
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
        $('#applicantdate').on('change', function() {
            var isValid = $(this).valid();
            if (isValid) {
                $(this).removeClass('is-invalid');
                $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            }
        });
    });

    $(document).on('input', '#dynamic-inputs-container input#ipaddress', function() {
            var type_req = document.getElementById("type_req");
            var subhostname = document.getElementById("subhostname").value;
            var selectBox = document.getElementById("mailrelay");
            var selectedValue = selectBox.value;
            var selectedValuetype_req = type_req.value;
            var ipAddress = $(this).val();
            var submitBtn = document.getElementById("submit");
            var submitBtn2 = document.getElementById("submit");

            $.ajax({
                url: "{{ route('check.ip.address') }}",
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ ipAddress: ipAddress, mailrelay: selectedValue,type_req: selectedValuetype_req}),
                success: function(response) {
                    if (response.exists) {
                        //console.log(response);
                        // message="Ip Address already keyin in the previous row!";
                        $('#resultContainer2').html(response.message);
                        submitBtn2.disabled = true;
                    } else {
                        //console.log(response);
                        $('#resultContainer2').empty();
                        submitBtn2.disabled = false;
                    }
                },
                error: function(xhr, status, error) {
                    // console.error("Error fetching Primary server:", error);
                    // $('#resultContainer').html("Error fetching Primary server: Contact Administrator!");

                }
            });


            if (/^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$/.test(ipAddress)) {
                const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/' + subhostname;
                // console.log("WebSocket URL: " + serverAddress);
                const socket = new WebSocket(serverAddress);

                socket.addEventListener('open', (event) => {
                    let command = '';

                    if (selectedValuetype_req == 1) {
                        command = `/opt/scripts/websocket/cmd4` + ` ` + "{{ $user->email }}" + ` ` + `${ipAddress}`;
                    }
                    if (selectedValuetype_req == 2) {
                        command = `/opt/scripts/websocket/cmd5` + ` ` + "{{ $user->email }}" + ` ` + `${ipAddress}`;
                    }

                    if (command) {
                        //console.log("Sending command: " + command);
                        socket.send(command);
                    }
                });

                socket.addEventListener('message', (event) => {
                    const response = event.data;
                    // console.log("Received response: " + response);

                    if (selectedValuetype_req == 1) {
                        if (response.includes('IP address already registered!')) {
                            submitBtn.disabled = true;
                            $(this).addClass('is-invalid');
                            $('#resultContainer').html(response);
                        } else {
                            submitBtn.disabled = false;
                            $('#resultContainer').empty();
                            $(this).removeClass('is-invalid');
                        }
                    } else if (selectedValuetype_req == 2) {
                        if (response.includes('IP address already terminated.')) {
                            //console.log("IP address already Terminated: " + response);
                            submitBtn.disabled = true;
                            $(this).addClass('is-invalid');
                            $('#resultContainer').html(response);
                        } else if (response.includes('IP address not registered, but ready for termination.')) {
                            //console.log("IP address not Registered to Terminate: " + response);
                            submitBtn.disabled = true;
                            $(this).addClass('is-invalid');
                            $('#resultContainer').html(response);
                        } else if (response.includes('IP address registered and ready for termination.')) {
                            //console.log("IP address Registered to Terminate: " + response);
                            submitBtn.disabled = false;
                            $('#resultContainer').empty();
                        }else{
                            submitBtn.disabled = false;
                            $(this).addClass('is-invalid');
                            $('#resultContainer').empty();
                            $(this).removeClass('is-invalid');
                        }
                    }
                    socket.close();
                });

                socket.addEventListener('error', (error) => {
                    //console.error("WebSocket error observed:", error);
                    $('#resultContainerError').html("Error fetching server: Contact Administrator!");
                });

                socket.addEventListener('close', (event) => {
                    //console.log("WebSocket connection closed");
                });
            } else {
                // Clear result container if input is invalid
                submitBtn.disabled = false;
                $('#resultContainer').empty();
                $(this).removeClass('is-invalid');
            }

        });


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
            // var inputs = tbody.querySelectorAll("input");
            // inputs.forEach(function(input) {
            //     input.disabled = false;
            // });
            // Use AJAX to send selectedValue to the controller
            $.ajax({
                url: "{{ route('check.subhostname.unique') }}", // Update the URL with your controller route
                type: 'POST',
                data: { selectedValue: selectedValue },
                success: function(response) {
                    // Check if hostname is present in the response
                    if (response.subhostname) {
                        // console.log(response.subhostname);
                        document.getElementById("subhostname").value = response.subhostname;
                        // Enable all input fields inside the tbody
                        var inputs = tbody.querySelectorAll("input");
                        inputs.forEach(function(input) {
                            input.disabled = false;
                            $('#resultContainer4').empty();
                            // Set the input value to the fetched hostname
                            // input.value = response.hostname;
                        });
                    } else {
                        message="Primary Server is not exist!";
                        $('#resultContainer4').html(message);
                    }
                },
                error: function(xhr, status, error) {
                    // console.error("Error fetching Primary server:", error);
                    // $('#resultContainer').html("Error fetching Primary server: Contact Administrator!");

                }
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


document.getElementById('submit').addEventListener('click', function() {
    if ($("#formserver").valid()) {
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
        }

        });
</script>
@endpush
