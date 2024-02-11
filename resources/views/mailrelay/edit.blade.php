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
            <div class="card-header">Ticket Number : {{ $smtpMailServers->first()->ticket_id }}</div>
            <div class="card-body"><button class="btn btn-outline-primary" type="button">View Detail</button></div>
        </div>
    </div>
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-header">List of New Mail Relay Detail </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>IP Address</th>
                            <th>MailBox</th>
                            <th>Auth Method</th>
                            <th>SMTP Mail</th>
                            <th>SMTP Port</th>
                            <th>Username</th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($smtpMailServers as $smtpMailServer)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $smtpMailServer -> ip_addr_server }}</td>
                            <td>@if($smtpMailServer -> mailbox_created == 0)
                                <span class="badge bg-danger text-white rounded-pill">No MailBox</span>
                            @else
                                <span class="badge bg-success text-white rounded-pill">Create MailBox</span>
                            @endif</td>
                            <td>@if($smtpMailServer -> smtp_relay_auth_method == 0)
                                <span class="badge bg-success text-white rounded-pill">SMTP IP Based Auth</span>
                            @else
                                <span class="badge bg-primary text-white rounded-pill">SMTP Username/Password Auth</span>
                            @endif</td>
                            <td>{{ $smtpMailServer ->smtp_relay_server}}</td>
                            <td>{{ $smtpMailServer ->smtp_port}}/tcp</td>
                            <td>@if($smtpMailServer -> smtp_username == "")
                                <span class="badge bg-danger text-white rounded-pill">None</span>
                            @else
                                {{ $smtpMailServer -> smtp_username }}
                            @endif</td>
                            <td>@if($smtpMailServer -> smtp_password == "")
                                <span class="badge bg-danger text-white rounded-pill">None</span>
                            @else
                                {{ $smtpMailServer -> smtp_password }}
                            @endif</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> <div class="mb-0">
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-header">CLI Section<br><span class="badge btn-danger">*** Any command execute will RUN at the Server directly</span></div>
            <div class="card-body">
                <div id="terminal"></div>
            </div>
        </div>
    </div>
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-header">Remark</div>
            <div class="card-body">
                <label for="exampleFormControlTextarea1">Notes for Approval</label><textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea></div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('usersmanagements.update', $smtpMailServers->first()->ticket_id) }}">
    @csrf
    @method('PUT')
        <div class="container-fluid px-4">
            <div class="card mb-4">
                <div class="card-body"  style="text-align: center;">
                    <button class="btn btn-danger me-2 my-1" type="button">Reject Request</button>
                    <button class="btn btn-primary me-2 my-1" type="button">Submit Approval</button>
                </div>
            </div>
        </div>
    </form>
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
</main>

@endsection
