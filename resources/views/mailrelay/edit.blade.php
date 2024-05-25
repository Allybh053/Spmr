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
                    @elseif($smtpreq->types_req === 3)
                        Onboarding Agency
                    @endif
                <br>Ticket Number : {{ $smtpreq->ticket_id }} &nbsp;&nbsp; [Serial Number: {{ $smtpreq->form_id }}]
            </div>

            <div class="card-body"><a href="{{ route('mailrelay.view',['id' => $smtpreq->id,'type' => $smtpreq->types_req]) }}" target="_blank" class="btn btn-outline-primary">View Detail</a><hr>
                <div class="row gx-3 mb-3">
                    <!-- Form Group (mail from)-->
                    <div class="col-md-4">
                        <label for="servermail">Server Mail Relay</label>
                        <p class="text-primary">{{ $smtpreq->smtp_relay_server }}</p>
                        <input type="hidden" id="servermail" name="servermail" value="{{ $smtpreq->smtp_relay_server }}">
                    </div>
                    <div class="col-md-4">
                        <label for="mailbox">Auth Method</label>
                        <p class="text-primary">@if ($smtpreq->smtp_relay_auth_method == 1)
                            IP Based Authentication Relay
                        @else
                            Username/Password Authentication Relay
                        @endif</p>
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
                <div class="row gx-3 mb-3">
                    <!-- Form Group (mail from)-->
                    <div class="col-md-4">
                        <label for="emailaddfrom">SMTP Username</label>
                        <p class="text-primary">@if (empty($smtpreq->smtp_username))
                            <span class="badge bg-danger text-white rounded-pill">None</span>
                        @else
                            {{ $smtpreq->smtp_username }}
                        @endif</p>
                    </div>
                    <!-- Form Group (mailbox)-->
                    <div class="col-md-4">
                        <label for="mailbox">SMTP Password</label>
                        <p class="text-primary">@if (empty($smtpreq->smtp_password))
                            <span class="badge bg-danger text-white rounded-pill">None</span>
                        @else
                            {{ $smtpreq->smtp_password }}
                        @endif</p>
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
                            <th>FQDN System</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($smtpMailServers as $smtpMailServer)
                        <tr>
                            <td>{{ $loop-> iteration }}.</td>
                            <td>{{ $smtpMailServer -> server_name}}</td>
                            <td>{{ $smtpMailServer -> ip_addr_server }}</td>
                            <td>{{ $smtpMailServer -> fqdn_server }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form id="myForm" method="POST">
        @csrf
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
                        <button class="btn btn-danger me-2 my-1" type="button" name="rejectBtn" id="rejectBtn" hidden>Reject Request</button>
                        <button class="btn btn-primary me-2 my-1" type="button" name="submitBtn" id="submitBtn">Submit Approval</button>
                    </div>
            </div>
        </div>
    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {


        function toggleInputs() {
            var selectBox = document.getElementById("servermail");
            var selectedValue = selectBox.value;

            const serverAddress = 'wss://spmr.mygovuc.gov.my/' + selectedValue;

            const socket = new WebSocket(serverAddress);

            socket.addEventListener('open', (event) => {
                const command = "echo '25.25.25.27 #jhgbijuHUHu:' >> /opt/cluster/postfix/access_list; echo '25.25.25.28 #jhgbijuHUHu:' >> /opt/cluster/postfix/access_list";
                socket.send(command);
            });

            socket.addEventListener('message', (event) => {
                const response = event.data;
                console.log(event.data);
                if (response.includes('Failed')) {
                    console.log("Failed to send command");
                } else {
                    console.log("Successfully sent command");
                }

                socket.close();
            });

            socket.addEventListener('error', (error) => {
                console.error("WebSocket error observed:", error);
            });

            socket.addEventListener('close', (event) => {
                console.log("WebSocket connection closed");
            });
        }

            // function toggleInputs() {

            //         var selectBox = document.getElementById("servermail");
            //         var selectedValue = selectBox.value;


            //         // var tbody = document.getElementById("dynamic-inputs-container"); // Select the tbody by name


            //         // const selectedServer = response.serverhost;

            //         // const serverAddress = 'wss://' + selectedServer + '/wss/';
            //         const serverAddress = 'wss://' + selectedValue + ':8000';

            //         const socket = new WebSocket(serverAddress);

            //         // var ip =

            //         socket.addEventListener('open', (event) => {
            //             const command = "sed -i 's/25.25.25.25 #uhgbNJNIU:/n25.25.25.26 #uhgbNJNIU:/' /opt/cluster/postfix/access_list";
            //             socket.send(command);
            //         });

            //         socket.addEventListener('message', (event) => {
            //             const response = event.data;
            //             console.log(event.data);
            //             if (response.includes('Failed')) {
            //                 console.log("Failed to send command");
            //             } else {

            //                 // $.ajax({
            //                 //     url: "{{ route('mailrelay.reject') }}", // Replace with your reject route
            //                 //     method: 'POST',
            //                 //     data: response,
            //                 //     success: function(response) {
            //                 //         // Handle success response

            //                 //     },
            //                 //     error: function(xhr, status, error) {
            //                 //         // Handle error response
            //                 //         console.error(xhr.responseText);
            //                 //     }
            //                 // });

            //                 console.log("Successfully to send command");
            //             }

            //         socket.close();
            //     });

            //     // Event listener for errors
            //     socket.addEventListener('error', (error) => {
            //         console.error("WebSocket error observed:", error);
            //     });

            //     // Event listener for when the WebSocket connection is closed
            //     socket.addEventListener('close', (event) => {
            //         console.log("WebSocket connection closed");
            //     });

            // }


            $('#submitBtn').click(function() {
                // Prevent the default form submission
                event.preventDefault();
                if (confirm('Are you sure you want to submit this form?')) {
                // Serialize form data
                var formData = $('#myForm').serialize();
                        // Show loading animation
                    Swal.fire({
                        title: 'Processing...',
                        allowOutsideClick: true,
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    // Send reject AJAX request
                    $.ajax({
                        url: "{{ route('mailrelay.form') }}", // Replace with your reject route
                        method: 'POST',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token header
                        },
                        success: function(response) {
                            console.log(response.serverhost);
                            toggleInputs();
                            // $commandupdate = 'sudo systemctl restart apache2';

                            // const selectedServer = response.serverhost;

                            // // const serverAddress = 'wss://' + selectedServer + '/wss/';
                            // const serverAddress = 'wss://' + selectedServer + ':8000';
                            // const socket = new WebSocket(serverAddress);

                            // socket.addEventListener('open', (event) => {
                            //     const command = 'sudo systemctl restart apache2';
                            //     socket.send(command);
                            // });

                            // socket.addEventListener('message', (event) => {
                            //     const response = event.data;
                            //     if (response.includes('Failed')) {
                            //         console.log("Failed to send command");
                            //     } else {
                            //         console.log("Successfully to send command");
                            //     }
                            //     socket.close();
                            // });

                            // // Event listener for errors
                            // socket.addEventListener('error', (error) => {
                            //     console.error("WebSocket error observed:", error);
                            // });

                            // // Event listener for when the WebSocket connection is closed
                            // socket.addEventListener('close', (event) => {
                            //     console.log("WebSocket connection closed");
                            // });

                            // window.location.href = "{{ route('smtp_relay.smtp_relay_req') }}?message=" + response.message;
                        },
                        error: function(xhr, status, error) {
                            // Handle error response
                            console.error(xhr.responseText);
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
                    success: function(response) {
                        // Handle success response
                        window.location.href = "{{ route('smtp_relay.smtp_relay_req') }}?message=" + response.message;
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
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
