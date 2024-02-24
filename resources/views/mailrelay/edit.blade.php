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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($smtpMailServers as $smtpMailServer)
                        <tr>
                            <td>{{ $loop-> iteration }}.</td>
                            <td>{{ $smtpMailServer -> server_name}}</td>
                            <td>{{ $smtpMailServer -> ip_addr_server }}</td>
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
                            <button class="btn btn-danger me-2 my-1" type="button">Reject Request</button>
                        <button class="btn btn-primary me-2 my-1" type="button">Submit Approval</button>
                    </div>
                </form>
            </div>
        </div>
    <script>
        const terminal = new Terminal();
        const ticketId = @json($smtpMailServers->first()->ticket_id);
        const socket = new WebSocket(`ws://${window.location.host}/mailrelay/detail/${ticketId}`);

        terminal.open(document.getElementById('terminal'));

        socket.addEventListener('open', (event) => {
            terminal.writeln('SSH connection established.');
        });

        socket.addEventListener('message', (event) => {
            terminal.write(event.data);
        });

        socket.addEventListener('close', (event) => {
            terminal.writeln('SSH connection closed.');
        });

        terminal.onData((data) => {
            socket.send(data);
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
