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
                            Registering Mail Relay
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
            <div class="card-header">Type of Request :
                    @if($smtpreq->types_req === 1)
                        New Application
                    @elseif($smtpreq->types_req === 2)
                        Service Termination
                    {{-- @elseif($smtpreq->types_req === 3)
                        Onboarding Agency --}}
                    @endif
                <br>Ticket Number : {{ $smtpreq->ticket_id }} &nbsp;&nbsp; [Serial Number: {{ $smtpreq->form_id }}]
            </div>

            <div class="card-body"><a href="{{ route('mailrelay.view',['id' => $smtpreq->id,'type' => $smtpreq->types_req]) }}" class="btn btn-outline-primary">View Detail</a><hr>
                <div class="row gx-3 mb-3">
                    <!-- Form Group (mail from)-->
                    <div class="col-md-4">
                        <label for="servermail">Server Mail Relay</label>
                        <p class="text-primary">{{ $smtpreq->smtp_relay_server }}</p>
                        <input type="hidden" id="sub_smtp_relay_server" name="sub_smtp_relay_server" value="{{ $smtpreq->sub_smtp_relay_server }}">
                    </div>
                    <div class="col-md-4" @if($smtpreq->types_req === 2) style="display: none;" @endif>
                        <label for="mailbox">Auth Method</label>
                        <p class="text-primary">
                        @if ($smtpreq->smtp_relay_auth_method == 1)
                            IP Based Authentication Relay
                        @endif
                        @if ($smtpreq->smtp_relay_auth_method == 2)
                            Username/Password Authentication Relay
                        @endif
                        </p>
                    </div>
                    <!-- Form Group (mailbox)-->
                    <div class="col-md-4">
                        <label for="mailbox">MailBox</label>
                        <p class="text-primary">
                            @if ($smtpreq->mailbox_created == 0)
                                <span class="badge bg-danger text-white rounded-pill">Not Answered</span>
                            @elseif($smtpreq->mailbox_created == 1)
                                <span class="badge bg-green text-white rounded-pill">No</span>
                            @else
                                <span class="badge bg-green text-white rounded-pill">Yes</span>
                            @endif
                        </p>
                    </div>
                </div>
                <hr>
                <form id="myForm" method="POST">
                @csrf
                <div class="row gx-3 mb-3">
                    <!-- Form Group (mail from)-->
                    {{-- <div class="col-md-4" @if($smtpreq->types_req === 2) style="display: none;" @endif> --}}
                    <div class="col-md-4">
                        <label for="emailaddfrom">SMTP Username</label>
                        {{-- @if ($smtpreq->smtp_relay_auth_method == 2) --}}
                        <input class="form-control" type="text" id="smtp_username" name="smtp_username" value="{{ $smtpreq->smtp_username }}"/>
                        {{-- @else
                        <br>
                        <input class="form-control" type="hidden" id="smtp_username" name="smtp_username" value=""/>
                        <span class="badge bg-danger text-white rounded-pill">None</span>
                        @endif --}}
                    </div>
                    <!-- Form Group (mailbox)-->
                    {{-- <div class="col-md-4" @if($smtpreq->types_req === 2) style="display: none;" @endif> --}}
                    <div class="col-md-4">
                        <label for="mailbox">SMTP Password</label>
                        {{-- @if ($smtpreq->smtp_relay_auth_method == 2) --}}
                        <input class="form-control" type="text" id="smtp_password" name="smtp_password" value="{{ $smtpreq->smtp_password }}"/>
                        {{-- @else
                        <br>
                        <input class="form-control" type="hidden" id="smtp_password" name="smtp_password" value=""/>
                        <span class="badge bg-danger text-white rounded-pill">None</span>
                        @endif --}}
                    </div>
                    <div class="col-md-4">
                        <label for="mailbox">SMTP Port</label>
                        <p class="text-primary">
                            @if ($smtpreq->smtp_port == 0)
                                Not Answered
                            @elseif ($smtpreq->smtp_port == 1)
                                25/tcp
                            @elseif ($smtpreq->smtp_port == 2)
                                465/tcp
                            @elseif ($smtpreq->smtp_port == 3)
                                587/tcp
                            @elseif ($smtpreq->smtp_port == 4)
                                25/tcp & 465/tcp
                            @elseif ($smtpreq->smtp_port == 5)
                                25/tcp & 587/tcp
                            @elseif ($smtpreq->smtp_port == 6)
                                465/tcp & 587/tcp
                            @elseif ($smtpreq->smtp_port == 7)
                                All
                            @else
                                Not Answered
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-header">List of New Mail Relay Detail</div>
            <div class="card-body">
                <table id="datatablesSimple" class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 5px;">No</th>
                            <th>System Name</th>
                            <th>IP Address</th>
                            <th>Domain System</th>
                            <th>MailFrom</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($smtpMailServers as $smtpMailServer)
                        <tr>
                            <td>{{ $loop-> iteration }}.</td>
                            <td>{{ $smtpMailServer -> server_name}}</td>
                            <td>{{ $smtpMailServer -> ip_addr_server }}</td>
                            <td>{{ $smtpMailServer -> fqdn_server }}</td>
                            <td>{{ $smtpMailServer -> mailfrom }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        <textarea hidden id="access_list" name="access_list" rows="10" cols="50">
            @foreach ($smtpMailServers as $smtpMailServer){{ preg_replace('/^\s*/', '', $smtpMailServer->ip_addr_server) }} #{{ $smtpMailServers->first()->form_id }};
            @endforeach
        </textarea>
    <div class="mb-0">
        <div class="container-fluid px-4" hidden>
            <div class="card mb-4">
                <div class="card-header">CLI Section<br><br><span class="text-danger">***</span> <u>Serial Number: {{ $smtpMailServers->first()->form_id }}</u><br><br><span class="badge btn-danger">*** Any command execute will RUN at the Server directly</span></div>
                {{-- <div class="card-body">
                    <div>
                        <textarea id="access_list2" name="access_list2" rows="10" cols="50">{{$logContent}}</textarea>
                    </div>
                </div> --}}
            </div>
        </div>
        <div class="container-fluid px-4">
            <div class="card mb-4">
                <div class="card-header">Remark</div>
                <div class="card-body">
                    <label for="exampleFormControlTextarea1">Comments</label><textarea class="form-control" id="comment" name="comment" rows="3"></textarea></div>
                </div>
            </div>
        </div>
        <div class="container-fluid px-4">
            <div class="card mb-4">
            <input type="hidden" id="implementationname" name="implementationname" value="{{ $user->first()->username }}"/>
            <input type="hidden" id="smtpid" name="smtpid" value="{{ $smtpreq->id }}"/>
            <input type="hidden" id="ticketno" name="ticketno" value="{{ $smtpreq->ticket_id }}"/>
            <input type="hidden" id="form_id" name="form_id" value="{{ $smtpreq->form_id }}"/>
            <input type="hidden" id="owner_email_addr" name="owner_email_addr" value="{{ $smtpreq->owner_email_addr }}"/>
            <input type="hidden" id="types_req" name="types_req" value="{{ $smtpreq->types_req }}"/>
            <input type="hidden" id="project_name" name="project_name" value="{{ $smtpreq->project_name }}"/>
            <input type="hidden" id="smtp_relay_server" name="smtp_relay_server" value="{{ $smtpreq->smtp_relay_server }}"/>
            <input type="hidden" id="verifier_2_name" name="verifier_2_name" value="{{ $smtpreq->verifier_2_name }}"/>
                    <div class="card-body"  style="text-align: center;">
                        <button class="btn btn-danger me-2 my-1" type="button" name="rejectBtn" id="rejectBtn">Reject Request</button>
                        <button class="btn btn-primary me-2 my-1" type="button" name="submitBtn" id="submitBtn">Submit Approval</button>
                    </div>
            </div>
        </div>
    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {

            function toggleInputs() {

            // var selectBox = document.getElementById("servermail");
            var sub_smtp_relay_server = document.getElementById("sub_smtp_relay_server").value;
            var smtp_username = document.getElementById("smtp_username").value;
            var smtp_password = document.getElementById("smtp_password").value;
            // var selectedValue = selectBox.value;

            const smtpMailServers = @json($smtpMailServers);
                        //console.log(smtpMailServers);
            const serverAddress = 'wss://spmr.mygovuc.gov.my/' + sub_smtp_relay_server;
            //console.log("WebSocket URL: " + serverAddress);
            const socket = new WebSocket(serverAddress);

            socket.addEventListener('open', (event) => {
                // Flag to check if any commands were sent
                let commandSent = false;
                let finalCommandSent = false;

                smtpMailServers.forEach((smtpMailServer, index) => {
                    let command = '';

                    if (smtpMailServer.types_req == 1) {
                        const ipAddress = smtpMailServer.ip_addr_server;
                        const formIds = smtpMailServer.form_id;
                        command = `echo '${ipAddress} #${formIds}:' >> /opt/cluster/postfix/access_list && /usr/sbin/postmap /opt/cluster/postfix/access_list && /usr/bin/systemctl reload postfix && echo 'Command succeeded' || echo 'Command failed'`;
                        commandSent = true;
                    }

                    if (smtpMailServer.types_req == 2) {
                        const ipAddress = smtpMailServer.ip_addr_server;
                        const formIds = smtpMailServer.form_id;
                        command = `grep -q '^${ipAddress}' /opt/cluster/postfix/access_list && sed -i 's/^${ipAddress} .*/#${ipAddress} #${formIds}:/' /opt/cluster/postfix/access_list || echo '#${ipAddress} #${formIds}:' >> /opt/cluster/postfix/access_list`;
                        commandSent = true;
                    }

                    if (command) {
                        //console.log("Sending command: " + command);
                        socket.send(command);

                        // Show loading only if a command was sent
                        if (!commandSent) return;

                        Swal.fire({
                            title: 'Succesfully register mail relay..',
                            allowOutsideClick: false, // Prevent outside clicks
                            showConfirmButton: false, // Disable the "OK" button
                            onBeforeOpen: () => {
                                Swal.showLoading();
                            },
                            timer: 500, // 6 seconds
                            willClose: () => {
                                // You can add any action you want to perform when the alert is about to close
                                //console.log('The alert is closing');
                            }
                        });
                    }

                    let command2 = '';

                    if (smtpMailServer.types_req == 1 && smtp_username != "") {
                        command2 = `echo '${smtp_password}:' | /usr/sbin/saslpasswd2 -c '${smtp_username}'`;
                        //console.log("Sending command user and password: " + command2);
                        socket.send(command2);
                    }

                    if (smtpMailServer.types_req == 2 && smtp_username != "") {
                        command2 = `/usr/sbin/saslpasswd2 -d '${smtp_username}'`;
                        //console.log("Sending command user and password for terminated: " + command2);
                        socket.send(command2);
                    }

                    // Check if it's the last iteration of the loop
                    if (index === smtpMailServers.length - 1 && commandSent && !finalCommandSent) {
                        const finalCommand = `/usr/sbin/postmap /opt/cluster/postfix/access_list && /usr/bin/systemctl reload postfix`;
                        //console.log("Sending final command: " + finalCommand);
                        socket.send(finalCommand);
                        finalCommandSent = true;
                    }
                });
            });

        }

        $('#submitBtn').click(function(event) {
            // Prevent the default form submission
            event.preventDefault();
            if (confirm('Are you sure you want to submit this form?')) {
                // Serialize form data
                var formData = $('#myForm').serialize();
                // Show loading animation
                Swal.fire({
                    title: 'Processing...',
                    allowOutsideClick: false, // Prevent outside clicks
                    showConfirmButton: false, // Disable the "OK" button
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                    timer: 10000, // 6 seconds
                    willClose: () => {
                        // You can add any action you want to perform when the alert is about to close
                        //console.log('The alert is closing');
                    }
                });
                // Send AJAX request
                $.ajax({
                    url: "{{ route('mailrelay.form') }}", // Replace with your reject route
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token header
                    },
                    success: function(response) {
                        //console.log(response);
                        toggleInputs();
                        window.location.href = "{{ route('smtp_relay.smtp_relay_req') }}?message=" + response.message;
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        //console.error(xhr.responseText);
                    }
                });

            } else {
                // If the user clicks "Cancel" in the confirmation dialog, do nothing
                return false;
            }
        });


        $('#rejectBtn').click(function() {

            if (confirm('Are you sure you want to Reject this form?')) {
            var formData = $('#myForm').serialize();
            // Send reject AJAX request
            $.ajax({
                url: "{{ route('mailrelay.reject') }}", // Replace with your reject route
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token header
                },
                success: function(response) {
                    // Handle success response
                    window.location.href = "{{ route('smtp_relay.smtp_relay_req') }}?message=" + encodeURIComponent(response.message);
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    //console.error(xhr.responseText);
                }
            });

            } else {
                // If the user clicks "Cancel" in the confirmation dialog, do nothing
                return false;
            }
        });

        });
    </script>

    <style>
        th,td {
            font-size: var(--bs-body-font-size);
        }
        label {
            font-weight: bold;
        }

    </style>
</main>

@endsection
