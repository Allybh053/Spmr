@extends('layouts.app')
@push('css')
@endpush

@section('content')
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"></div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">Validation & Verification</li>
                                </ol>
                            </nav>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4" id="searchingBox">
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">Loopback SMTP Test</div>

                    <div class="card-body mt-1 mb-4">
                        <form id="report-form">
                            <div class="row col-12 mb-1">
                                <div class="col-md-4">
                                    <label class="small mb-1" for="serverName">Server Name:</label>
                                    {!! Form::select('serverName', $serverName, $defaultServerId ?? 1, ['id'=>'serverName', 'name'=>'serverName', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                </div>
                            </div>
                            <div class="row col-12 mb-1">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="mailFrom">Mail From:</label>
                                    {!! Form::text('mailFrom',  null, ['id'=>'mail_from', 'name'=>'mail_from', 'class'=>'form-control','required']) !!}
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="recepientTo">Recepient To:</label>
                                    {!! Form::text('recepientTo', null, ['id'=>'rec_to', 'name'=>'rec_to', 'class'=>'form-control','required']) !!}
                                </div>
                            </div>

                            <div class="row col-12 mb-1">
                                <div class="col-md-6">
                                    <label for="ports">SMTP Port Number <span style="color: red">*</span></label>
                                    <select class="form-control select2" name="ports" id="ports" required>
                                        <option disabled selected value="">Select Port</option>
                                        <option value="25">25/tcp</option>
                                        <option value="465">465/tcp</option>
                                        <option value="587">587/tcp</option>
                                    </select>
                                </div>
                                <div class="col-md-6"></div>
                            </div>

                            <div class="row col-12 mb-1" id="saslFields">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="sasl_user">SASL Username:</label>
                                    <input type="text" id="sasl_user" name="sasl_user" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="sasl_pass">SASL Password:</label>
                                    <input type="password" id="sasl_pass" name="sasl_pass" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div style="padding: 25px; text-align: right;">
                                    <button class="btn btn-secondary" id="clear">Clear</button>
                                    <button class="btn btn-success" id="test">Test</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function () {

            toggleSaslFields(); // Run on page load

            $('#ports').on('change', function () {
                toggleSaslFields();
            });

            function toggleSaslFields() {
                const port = $('#ports').val();

                // Show SASL fields for ports 465 and 587
                if (port === "465" || port === "587") {
                    $('#saslFields').show();
                } else {
                    $('#saslFields').hide();
                    $('#sasl_user').val('');
                    $('#sasl_pass').val('');
                }
            }

            $('#clear').on('click', function (e) {
                $('#mail_from').val('');
                $('#rec_to').val('');
                $('#ports').val('').trigger('change');
                $('#sasl_user').val('');
                $('#sasl_pass').val('');
            });

            $('#serverName').on('change', function () {
                var selectedServerId = $(this).val();
                $.ajax({
                    url: "{!! route('loopback.api') !!}",
                    method: 'GET',
                    data: {
                        serverId: selectedServerId
                    },
                    success: function (response) {},
                    error: function (xhr, status, error) {}
                });
            });

            $('#report-form').submit(function (e) {
                e.preventDefault();

                var formData = $(this).serialize();
                var port = $('#ports').val();
                var saslUser = $('#sasl_user').val();
                var saslPass = $('#sasl_pass').val();

                if (port === "587" && (!saslUser || !saslPass)) {
                    Swal.fire({ icon: 'error', title: 'Missing SASL Credentials', text: 'SASL username and password are required for port 587.' });
                    return;
                }

                $.ajax({
                    type: 'GET',
                    url: "{{ route('loopback.smtpTest') }}",
                    data: formData,
                    success: function (response) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: 'Proceed with the action?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                sendTestCmd(response);
                            }
                        });
                    },
                    error: function (error) {}
                });
            });

            function sendTestCmd(response) {
                const selectedServer = response.data;
                const mailFrom = response.MailFrom;
                const recTo = response.RecepientTo;
                const port = $('#ports').val();
                const saslUser = $('#sasl_user').val();
                const saslPass = $('#sasl_pass').val();

                const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/' + selectedServer;
                const socket = new WebSocket(serverAddress);

                socket.addEventListener('open', () => {
                    let command;

                    if (port === "25") {
                        command = `/opt/scripts/websocket/cmd14 {{ $user->email }} ${mailFrom} ${recTo}`;
                    } else if (port === "465" || port === "587") {
                        command = `/opt/scripts/websocket/cmd14 {{ $user->email }} ${mailFrom} ${recTo} '${saslUser}' '${saslPass}'`;
                    } else {
                        Swal.fire({ icon: 'error', title: 'Invalid Port', text: 'Unsupported port selected.' });
                        socket.close();
                        return;
                    }

                    socket.send(command);
                });

                socket.addEventListener('message', (event) => {
                    const response = event.data;
                    let alertConfig = {
                        icon: response.includes('Failed') ? 'error' : 'success',
                        title: response.includes('Failed') ? 'Failed' : 'Success',
                        text: response.includes('Failed') ? "Failed to send SMTP Test on " + selectedServer + "." : "Successfully sent SMTP Test on " + selectedServer + ".",
                        showConfirmButton: true
                    };

                    Swal.fire(alertConfig).then((result) => {});
                    document.querySelector('.swal2-confirm').addEventListener('click', () => {
                        Swal.close();
                        socket.close();
                        location.reload();
                    });
                });
            }

        });
    </script>
@endpush
