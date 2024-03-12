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
            @php
                // dd($smtpMailServers);
            @endphp
            <div class="card-header">Ticket Number : {{ $smtpMailServers->first()->ticket_id }} &nbsp;&nbsp; [Form ID: {{ $smtpMailServers->first()->form_id }}]</div>
            <div class="card-body"><a href="{{ route('smtp_relay.view',['id' => $smtpMailServers->first()->smtpid]) }}" target="_blank" class="btn btn-outline-primary">View Detail</a><hr>
                <div class="row gx-3 mb-3">
                    <!-- Form Group (mail from)-->
                    <div class="col-md-4">
                        <label for="emailaddfrom">Server Mail Relay</label>
                        <p class="text-primary">{{ $smtpMailServers->first()->smtp_relay_server }}</p>
                    </div>
                    <div class="col-md-4">
                        <label for="mailbox">Auth Method</label>
                        <p class="text-primary">@if ($smtpMailServers->first()->smtp_relay_auth_method == 1)
                            IP Based Authentication Relay
                        @else
                            Username/Password Authentication Relay
                        @endif</p>
                    </div>
                    <!-- Form Group (mailbox)-->
                    <div class="col-md-4">
                        <label for="mailbox">MailBox</label>
                        <p class="text-primary">@if ($smtpMailServers->first()->mailbox_created == 1)
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
                        <p class="text-primary">@if (empty($smtpMailServers->first()->smtp_username))
                            <span class="badge bg-danger text-white rounded-pill">None</span>
                        @else
                            {{ $smtpMailServers->first()->smtp_username }}
                        @endif</p>
                    </div>
                    <!-- Form Group (mailbox)-->
                    <div class="col-md-4">
                        <label for="mailbox">SMTP Password</label>
                        <p class="text-primary">@if (empty($smtpMailServers->first()->smtp_password))
                            <span class="badge bg-danger text-white rounded-pill">None</span>
                        @else
                            {{ $smtpMailServers->first()->smtp_password }}
                        @endif</p>
                    </div>
                    <div class="col-md-4">
                        <label for="mailbox">SMTP Port</label>
                        <p class="text-primary">{{ $smtpMailServers->first()->smtp_port }}/tcp</p>
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
    </div> <div class="mb-0">
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-header">CLI Section<br><br><span class="text-danger">***</span> <u>Form ID: {{ $smtpMailServers->first()->form_id }}</u><br><br><span class="badge btn-danger">*** Any command execute will RUN at the Server directly</span></div>
            <div class="card-body">
                <div>
                    <input class="form-control" type="text" id="commandInput" placeholder="Enter command here...."><br>
                    <button class="btn btn-primary" id="sendButton">Send</button>
                    <button class="btn btn-success" id="connectButton">Connect</button>
                </div><br>
                <div id="terminal"></div>
            </div>
        </div>
    </div>
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-header">Remark</div>
            <div class="card-body">
                <label for="exampleFormControlTextarea1">Comments</label><textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea></div>
            </div>
        </div>
    </div>


        <div class="container-fluid px-4">
            <div class="card mb-4">
                <form method="POST" action="{{ route('usersmanagements.update', $smtpMailServers->first()->form_id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body"  style="text-align: center;">
                        <button class="btn btn-danger me-2 my-1" type="button" onclick="rejectRequest()">Reject Request</button>
                        <button class="btn btn-primary me-2 my-1" type="button">Submit Approval</button>
                    </div>
                </form>
            </div>
        </div>

    <script src="https://unpkg.com/xterm@5.2.1/lib/xterm.js"></script>
    <script src="https://unpkg.com/xterm-addon-fit"></script>

    <script src="https://unpkg.com/xterm"></script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const term = new Terminal();
    term.open(document.getElementById('terminal'));

    let socket;

    const connectButton = document.getElementById('connectButton');
    connectButton.addEventListener('click', connectWebSocket);

    function connectWebSocket() {
        const serverAddress = 'ws://20.6.33.199:8000/';

        // Close existing socket if any
        if (socket && socket.readyState === WebSocket.OPEN) {
            socket.close();
        }

        // Create a new WebSocket connection
        socket = new WebSocket(serverAddress);

        // Set up WebSocket event listeners
        socket.addEventListener('open', (event) => {
            console.log('Connected to WebSocket');
            term.writeln('SPMR Connected to the server');
            term.onData(data => socket.send(data));
            socket.onmessage = event => handleWebSocketMessage(event.data);
            window.addEventListener('resize', () => term.fit());
            socket.addEventListener('close', () => {
                term.writeln('\r\nConnection closed.');
                term.detach(socket);
            }); 
        });

        socket.addEventListener('error', (event) => {
            console.error('WebSocket connection error:', event);
            // Handle the error as needed
        });
    }

    function handleWebSocketMessage(message) {
    const formattedOutput = message.replace(/\n/g, '<br>');

    if (message.startsWith('responded')) {
        term.write('<span style="color: green;">' + formattedOutput + '</span>');
    } else if (message.startsWith('FILE_CONTENT:')) {
        const fileContent = message.substring('FILE_CONTENT:'.length);
        term.writeln(fileContent);
    } else {
        term.writeln(formattedOutput);
    }
}


    function sendCommand() {
        const commandInput = document.getElementById('commandInput');
        const command = commandInput.value.trim();

        if (command) {
            term.writeln('Sent: ' + command);
            socket.send(command);
            commandInput.value = '';
        }
    }

    // Handle the 'key' event to send command when Enter is pressed
    term.onKey(e => {
        const printable = !e.altKey && !e.ctrlKey && !e.metaKey;
        if (e.key === '\r' && printable) {
            sendCommand();
        }
    });

    // Handle button click to send command
    document.getElementById('sendButton').addEventListener('click', sendCommand);

    socket.addEventListener('close', (event) => {
        console.log('Connection closed:', event);
    });
});

function rejectRequest() {
    // Assuming you have some identifier for the request (e.g., request ID)
    var requestId = 123;  // Replace with your actual request ID or dynamically retrieve it

    // Make an AJAX request to update the column on the server
    fetch(`/mailrelay/reject/${requestId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Success:', data);
        // Handle success, if needed
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
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
