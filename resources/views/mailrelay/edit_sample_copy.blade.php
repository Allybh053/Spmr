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
                        <label for="emailaddfrom">Server Mail Relay</label>
                        <p class="text-primary">{{ $smtpreq->smtp_relay_server }}</p>
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
                        <p class="text-primary">@if ($smtpreq->mailbox_created == 1)
                            <span class="badge bg-danger text-white rounded-pill">None</span>
                        @else
                            <span class="badge bg-green text-white rounded-pill">Yes</span>
                        @endif</p>
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
                        <p class="text-primary">{{ $smtpreq->smtp_port }}/tcp</p>
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
                            {{-- <th style="text-align: center;">Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($smtpMailServers as $smtpMailServer)
                        <tr>
                            <td>{{ $loop-> iteration }}.</td>
                            <td>{{ $smtpMailServer -> server_name}}</td>
                            <td>{{ $smtpMailServer -> ip_addr_server }}</td>
                            <td>{{ $smtpMailServer -> fqdn_server }}</td>
                            {{-- <td style="text-align: center;"><input type="checkbox" id="myCheckbox" name="myCheckbox" value="checkboxValue"></td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> <div class="mb-0">
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-header">CLI Section<br><br><span class="text-danger">***</span> <u>Serial Number: {{ $smtpMailServers->first()->form_id }}</u><br><br><span class="badge btn-danger">*** Any command execute will RUN at the Server directly</span></div>
            <div class="card-body">
                <div>
                </div>
                {{-- <div class="terminal-window">
                    <textarea id="terminal" placeholder="Enter command..." autofocus></textarea>
                </div> --}}
                {{-- <div id="terminal" contenteditable="true" spellcheck="false"></div> --}}
                <div id="shell-in-a-box"></div>
                {{-- <div id="terminal"></div> --}}
                {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xterm/4.7.0/xterm.min.js"></script> --}}
            </div>
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
    <form id="myForm" method="POST">
    @csrf
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
    <script src="{{ asset('js/jquery-3.6.4.min.js') }}"></script>
    <script src="https://unpkg.com/xterm-addon-fit@0.8.0/lib/xterm-addon-fit.js"></script>
    <script src="https://unpkg.com/xterm@5.3.0/lib/xterm.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
    // const term = new Terminal();
    // term.open(document.getElementById('terminal'));


        $(document).ready(function() {
        // Make AJAX request to fetch Shell In A Box session
        fetch('{{ route('shell-in-a-box') }}')
        .then(response => response.text())
        .then(data => {
            console.log(data);
            // Update the content on your page
            document.getElementById('shell-in-a-box').innerHTML = data;
        })
        .catch(error => {
            console.error('Error fetching Shell In A Box session:', error);
        });

            // $('#terminal').focus();

            // $('#terminal').keydown(function(event) {
            //     if (event.keyCode === 13 && !event.shiftKey) {
            //         event.preventDefault();
            //         sendCommand($(this).text().trim());
            //         $(this).empty();
            //     }
            // });

            // function sendCommand(command) {
            //     $.ajax({
            //         url: "{{ route('terminal.executeCommand') }}",
            //         method: 'POST',
            //         data: {
            //             command: command
            //         },
            //         success: function(response) {
            //             appendToTerminal(response.output + '\n');
            //         },
            //         error: function(xhr, status, error) {
            //             console.error(error);
            //         }
            //     });
            // }

            // function appendToTerminal(output) {
            //     $('#terminal').append(output);
            //     $('#terminal').scrollTop($('#terminal')[0].scrollHeight);
            // }


            $('#submitBtn').click(function() {
                // Prevent the default form submission
                event.preventDefault();
                if (confirm('Are you sure you want to submit this form?')) {
                // Serialize form data
                var formData = $('#myForm').serialize();
                    // Send reject AJAX request
                    $.ajax({
                        url: "{{ route('mailrelay.reject') }}", // Replace with your reject route
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            // Handle success response
                            console.log(response);
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
                        console.log(response);
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

                // // Listen for keydown event on the textarea
                // $('#terminalaimi').on('keydown', function(event) {
                //     // Check if Enter key is pressed
                //     if (event.keyCode === 13) {
                //         event.preventDefault(); // Prevent the default behavior of the Enter key

                //         var command = $('#terminal').val().trim(); // Get the command from the textarea
                //         if (command !== '') {
                //             // Send Ajax request
                //             $.ajax({
                //                 url: "{{ route('terminal.executeCommand') }}", // Route to your controller method
                //                 method: 'POST',
                //                 data: { command: command }, // Send the command in the data
                //                 success: function(response) {
                //                     handleSSHResponse(response);
                //                 },
                //                 error: function(xhr, status, error) {
                //                     // Handle error response
                //                     console.error(xhr.responseText);
                //                     console.error(error);
                //                 }
                //             });
                //         }
                //     }
                // });
                // function handleSSHResponse(response) {

                //     // Check if the response is an object
                //     if (typeof response === 'object' && response.hasOwnProperty('output')) {
                //         // Extract the output from the response
                //         var output = response.output;

                //         // Get the textarea element
                //         var $terminal = $('#terminal');

                //         // Append the output to the textarea
                //         $terminal.val(function(index, currentValue) {
                //             // Append a newline character if the textarea is not empty
                //             var newLine = currentValue !== '' ? '\n' : '';
                //             return currentValue + newLine + output;
                //         });
                //     } else {
                //         // If the response is not in the expected format, display an error message
                //         console.error('Unexpected response format:', response);
                //     }
                // }


                // // Send Ajax request
                // $.ajax({
                //     url: "{{ route('mailrelay.form') }}", // Route to your controller method
                //     method: 'POST',
                //     data: formData,
                //     success: function(response) {
                //         // Handle success response
                //         console.log(response);
                //         window.location.href = "{{ route('smtp_relay.smtp_relay_req') }}?message=" + response.message;
                //     },
                //     error: function(xhr, status, error) {
                //         // Handle error response
                //         console.error(xhr.responseText);
                //         console.error(error);
                //     }
                // });

            // Initialize terminal
            // const term = new Terminal({
            //     scrollback: 9999999 // Set the maximum number of lines in the buffer
            // });

            // Enable mouse wheel scrolling
            // term.element.addEventListener('wheel', (event) => {
            //     if (event.deltaY > 0) {
            //         term.scrollLines(-1); // Scroll up
            //     } else {
            //         term.scrollLines(1); // Scroll down
            //     }
            // });

            // let socket;

            // window.onload = function() {
            //     connectWebSocket();
            // };

            // const inputBuffer = []; // Buffer to store input data

            // function connectWebSocket() {
            //     const ipaddresshostname = {!! $smtpMailServers->pluck('hostname')->unique()->toJson() !!}[0];
            //     const serverAddress = `wss://${ipaddresshostname}/wss/`;

            //     // Create a new WebSocket connection
            //     socket = new WebSocket(serverAddress);
            //     socket.addEventListener('open', (event) => {
            //         term.writeln('SPMR Connected to the server');
            //         // socket.onmessage = event => handleWebSocketMessage(event.data);
            //     });

            //     socket.addEventListener('error', (event) => {
            //         console.error('WebSocket connection error:', event);
            //     });
            // }

            // function handleWebSocketMessage(message) {
            //     const formattedOutput = message.replace(/\n/g, '<br>');

            //     // Handle different types of messages
            //     if (message.startsWith('While opening file ')) {
            //         term.writeln(message.substring('While opening file '.length));
            //         // Add file content to the input buffer for editing
            //         inputBuffer.push(...message.substring('While opening file '.length));
            //     } else if (message.includes('Swap file')) {
            //         socket.send('rm /var/tmp/test.sh.sw*');
            //     } else if (message.includes("rm: remove regular file '/var/tmp/test.sh.sw*'?")) {
            //         socket.send('yes');
            //         socket.send('vim /usr/local/bin/test.sh');
            //     } else {
            //         term.writeln(formattedOutput);
            //     }
            // }

            // Handle key press events in the terminal
            // term.onKey(function (e) {
            //     if (e.domEvent.key === 'Enter') { // If Enter key is pressed
            //         if (e === 'Enter') {
            //             console.log("enter");
            //         } else {
            //             sendCommand(); // Send other keystrokes to the terminal
            //         }
            //     } else if (e.domEvent.key === 'Backspace') { // If Backspace key is pressed
            //         if (inputBuffer.length > 0) {
            //             inputBuffer.pop(); // Remove last character from input buffer
            //             term.write('\b \b'); // Move cursor back and clear character from terminal
            //         }
            //     } else if (e.domEvent.shiftKey && e.domEvent.key === 'I') {
            //         socket.send('"i" >&3');
            //     } else if (e.domEvent.shiftKey && e.domEvent.key === ':') {
            //         const escapeEvent = new KeyboardEvent('keyup', { key: 'Escape' });
            //         const semicolonEvent = new KeyboardEvent('keyup', { key: ';' });
            //         document.dispatchEvent(escapeEvent);
            //         document.dispatchEvent(semicolonEvent);
            //         term.write(':');
            //     } else if (e.domEvent.shiftKey && e.domEvent.key === 'A') {
            //         socket.send('vim /usr/local/bin/test.sh');
            //     } else {
            //         inputBuffer.push(e.key); // Add key to input buffer
            //         term.write(e.key);
            //     }
            // });

            // function sendCommand() {
            //     const command = inputBuffer.join('').trim(); // Get command from input buffer
            //     inputBuffer.length = 0; // Clear input buffer

            //     if (command) {
            //         term.writeln('\r\n$ ' + command); // Display command in terminal
            //         socket.send(command); // Send command via WebSocket
            //     }
            // }
        });
    </script>

    <style>
        th,td {
            font-size: var(--bs-body-font-size);
        }
        label {
            font-weight: bold;
        }
        /* #terminal {
            width: 100%;
            height: 400px;
            border: 1px solid #ccc;
            overflow-y: scroll;
            padding: 10px;
            font-family: monospace;
            white-space: pre-wrap;
            word-wrap: break-word;
            outline: none;
            cursor: text;
        } */
    </style>
</main>

@endsection
