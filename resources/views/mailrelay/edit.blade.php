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
            <div class="card-header">Ticket Number</div>
            <div class="card-body"><p>Abc123</p><button class="btn btn-outline-primary" type="button">View Detail</button></div>
        </div>
    </div>
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-header">List of New Mail Relay Detail</div>
            <div class="card-body">
                <table id="datatablesSimple" style="text-align: center;">
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
                        <tr>
                            <td>1.</td>
                            <td>123.123.123.123</td>
                            <td><div class="badge bg-danger text-white rounded-pill">None</div></td>
                            <td>IP Based Mail Relay</td>
                            <td>Postmaster.gov.my</td>
                            <td>25</td>
                            <td><div class="badge bg-danger text-white rounded-pill">None</div></td>
                            <td><div class="badge bg-danger text-white rounded-pill">None</div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <div class="mb-0">
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-header">CLI Section</div>
            <div class="card-body">
                <div id="terminal"></div>
            </div>
        </div>
    </div>
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-header">Comment</div>
            <div class="card-body">
                <label for="exampleFormControlTextarea1">Example textarea</label><textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea></div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-body"  style="text-align: center;">
                <button class="btn btn-primary me-2 my-1" type="button">Save Changes</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/xterm.js') }}"></script>
    <script src="{{ asset('js/socket.io.js') }}"></script>
    <script src="{{ asset('js/ssh-terminal.js') }}"></script>
</main>

@endsection
