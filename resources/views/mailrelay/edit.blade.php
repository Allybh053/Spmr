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
    <div id="registerpage">
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
                        <label>Server Mail Relay</label>
                        <p class="text-primary">{{ $smtpreq->smtp_relay_server }}</p>
                        {{--<input type="hidden" id="sub_smtp_relay_server" name="sub_smtp_relay_server" value="{{ $smtpreq->sub_smtp_relay_server }}">--}}
                        <input type="hidden" id="sub_smtp_relay_server" name="sub_smtp_relay_server" value="{{ $subhostname }}">
                    </div>
                    <div class="col-md-4">
                        <label>Auth Method</label>
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
                        <label>MailBox</label>
                        <p class="text-primary">
                            @if ($smtpreq->mailbox_created == 1)
                                <span class="badge bg-danger text-white rounded-pill">Not</span>
                            @elseif($smtpreq->mailbox_created == 2)
                                <span class="badge bg-green text-white rounded-pill">Yes</span>
                            @else
                                <span class="badge bg-green text-white rounded-pill">No Answer</span>
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
                    <div class="col-md-4" @if($smtpreq->smtp_relay_auth_method === 1) style="display: none;" @endif>
                        <label for="smtp_username">SMTP Username</label>
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
                    <div class="col-md-4" @if($smtpreq->smtp_relay_auth_method === 1) style="display: none;" @endif>
                        <label for="smtp_password">SMTP Password</label>
                        {{-- @if ($smtpreq->smtp_relay_auth_method == 2) --}}
                        <input class="form-control" type="text" id="smtp_password" name="smtp_password" value="{{ $smtpreq->smtp_password }}"/>
                        {{-- @else
                        <br>
                        <input class="form-control" type="hidden" id="smtp_password" name="smtp_password" value=""/>
                        <span class="badge bg-danger text-white rounded-pill">None</span>
                        @endif --}}
                        <button class="btn btn-success me-2 my-1" type="button" onclick="generatePassword()">Generate Password</button>
                    </div>
                    <div class="col-md-4">
                        <label>SMTP Port</label>
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
                        <input class="form-control" type="hidden" id="smtp_port" name="smtp_port" value="{{ $smtpreq->smtp_port }}"/>
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
                    <label for="comment">Comments</label><textarea class="form-control" id="comment" name="comment" rows="3"></textarea></div>
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
    </div>
    <style>
        #registerpage {
            display: none;
        }
    </style>
    <script src="{{asset('js/vendor/jquery.min.js')}}"></script>
    <script>
    window.addEventListener('load', function() {
        // console.log('Page is fully loaded, including all frames and resources');
        document.getElementById('registerpage').style.display = 'block';
    });

    function generatePassword() {
        const length = 22;
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-=_+[]{}|;:,.<>?';
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * chars.length);
            password += chars[randomIndex];
        }
        document.getElementById('smtp_password').value = password;
    }
    $(document).ready(function() {

        function toggleInputs() {
            return new Promise((resolve, reject) => {
                const sub_smtp_relay_server = document.getElementById("sub_smtp_relay_server").value;
                const smtp_username = document.getElementById("smtp_username").value;
                const smtp_password = document.getElementById("smtp_password").value;

                const smtpMailServers = @json($smtpMailServers);
                const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/' + sub_smtp_relay_server;

                let socket = new WebSocket(serverAddress);
                let commandQueue = [];
                let finalCommandQueue = [];
                let successCount = 0;
                let failCount = 0;
                let failedCommands = [];
                let commandIndex = 0;

                function handleOpen() {
                    processNextCommand();
                    socket.removeEventListener('open', handleOpen); // Remove the listener after execution
                }

                socket.addEventListener('open', handleOpen);

                socket.addEventListener('close', () => {
                    if (commandIndex < commandQueue.length) {
                        // Reconnect WebSocket if needed
                        socket = new WebSocket(serverAddress);
                        socket.addEventListener('open', handleOpen);
                    }
                });

                socket.addEventListener('error', (event) => {
                    failCount++;
                    processNextCommand();
                });

                function processNextCommand() {
                    if (commandIndex >= commandQueue.length) {
                        processFinalCommands();
                        return;
                    }

                    const command = commandQueue[commandIndex];
                    sendCommand(socket, command, () => {
                        commandIndex++;
                        processNextCommand();
                    });
                }

                function processFinalCommands() {
                    finalCommandQueue.push(`/opt/scripts/websocket/cmd9` + ` ` + "{{ $user->email }}");

                    let finalCommandIndex = 0;

                    function processNextFinalCommand() {
                        console.log("Final Command Queue:", finalCommandQueue); // Debugging
                        if (finalCommandIndex >= finalCommandQueue.length) {
                            Swal.fire({
                                title: `Successfully submitted Mail Relay`,
                                allowOutsideClick: false,
                                showConfirmButton: true,
                                icon: 'success'
                            }).then(() => {
                                resolve();
                            });
                            return;
                        }

                        const command = finalCommandQueue[finalCommandIndex];
                        sendCommand(socket, command, () => {
                            finalCommandIndex++;
                            processNextFinalCommand();
                        });
                    }

                    processNextFinalCommand();
                }

                function sendCommand(socket, command, callback) {
                    const retries = 5;
                    const delay = 1500; // 1.5 second

                    function trySend(attempt) {
                        if (attempt > retries) {
                            failCount++;
                            failedCommands.push(command);
                            callback();
                            return;
                        }

                        try {
                            socket.send(command);
                            successCount++;
                            callback();
                        } catch (error) {
                            setTimeout(() => trySend(attempt + 1), delay);
                        }
                    }

                    trySend(1);
                }

                // Populate command queue
                smtpMailServers.forEach(smtpMailServer => {
                    if (smtpMailServer.types_req == 1) {
                        const ipAddress = smtpMailServer.ip_addr_server;
                        const formIds = smtpMailServer.form_id;
                        commandQueue.push(`/opt/scripts/websocket/cmd6` + ` ` + "{{ $user->email }}" + ` ` + `${ipAddress}` + ` ` + `${formIds}`);
                    }

                    if (smtpMailServer.types_req == 2) {
                        const ipAddress = smtpMailServer.ip_addr_server;
                        const formIds = smtpMailServer.form_id;
                        commandQueue.push(`/opt/scripts/websocket/cmd10` + ` ` + "{{ $user->email }}" + ` ` + `${ipAddress}` + ` ` + `${formIds}`);
                    }
                });

                // Add saslpasswd2 commands to finalCommandQueue
                if (smtp_username !== "") {
                    const formIds = document.getElementById("form_id").value;
                    if (smtpMailServers.some(smtpMailServer => smtpMailServer.types_req == 1)) {
                        const cmd7 = `/opt/scripts/websocket/cmd7` + ` ` + "{{ $user->email }}" + ` ` + `${smtp_username}` + ` ` + `'${smtp_password}'` + ` ` + `${formIds}`;
                        if (!finalCommandQueue.includes(cmd7)) {
                            finalCommandQueue.push(cmd7);
                        }
                    }
                    if (smtpMailServers.some(smtpMailServer => smtpMailServer.types_req == 2)) {
                        finalCommandQueue.push(`/opt/scripts/websocket/cmd8` + ` ` + "{{ $user->email }}" + ` ` + `${smtp_username}`);
                    }
                }

                // Start processing commands
                if (socket.readyState === WebSocket.OPEN) {
                    processNextCommand();
                } else {
                    socket.addEventListener('open', handleOpen);
                }
            });
        }

        $('#submitBtn').click(function(event) {
        // Prevent the default form submission
        event.preventDefault();

            // Check if sub_smtp_relay_server is active
            var subSmtpRelayServer = $('#sub_smtp_relay_server').val();
            $.ajax({
                url: "{{ route('check.server.status') }}", // Replace with your route for checking server status
                method: 'POST',
                data: {sub_smtp_relay_server: subSmtpRelayServer},
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status !== 1) {
                        // Alert the user about the inactive server
                        Swal.fire({
                            title: 'Inactive Server',
                            text: 'The SMTP relay server is inactive. Please choose an active server.',
                            icon: 'error',
                            showConfirmButton: true
                        });
                    } else {
                        // Show confirmation dialog
                        showConfirmationDialog();
                    }
                },
                error: function(xhr, status, error) {
                    // console.error(xhr.responseText);
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred while checking server status.',
                        icon: 'error',
                        showConfirmButton: true
                    });
                }
            });
        });

        function showConfirmationDialog() {
        // Show SweetAlert confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to submit this form?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Continue with form submission
                submitForm();
            }
        });
        }

        function submitForm() {
        // Serialize form data
        var formData = $('#myForm').serialize();

        // Show loading animation
        Swal.fire({
            title: 'Processing...',
            allowOutsideClick: false,
            showConfirmButton: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            }
        });

        // Call toggleInputs first
        toggleInputs()
            .then(() => {
                // Send AJAX request
                return $.ajax({
                    url: "{{ route('mailrelay.form') }}",
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
            })
            .then((response) => {
                // Handle success response
                Swal.fire({
                    title: 'Success',
                    text: response.message,
                    icon: 'success',
                    showConfirmButton: true
                }).then(() => {
                    window.location.href = "{{ route('smtp_relay.smtp_relay_req') }}";
                });
            })
            .catch((error) => {
                // Handle error response
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred while processing your request.',
                    icon: 'error',
                    showConfirmButton: true
                });
            });
        }

        $('#rejectBtn').click(function() {
            // Show SweetAlert confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to reject this form?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, reject it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
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
                            // console.error(xhr.responseText);
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // If the user clicks "Cancel" in the confirmation dialog, do nothing
                    return false;
                }
            });
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
