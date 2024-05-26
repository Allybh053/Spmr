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
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">Loopback SMTP Test</div>


                        <div class="card-body mt-1 mb-4">
                        <form id="report-form">
                        <div class="row col-12 mb-1">
                                    <div class="col-md-4">
                                        <label class="small mb-1" for="serverName">Server Name:</label>
                                        {!! Form::select('serverName', $serverName, null, ['id'=>'serverName', 'name'=>'serverName', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                    </div>
                        </div>
                        <div class="row col-12 mb-1">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="mailFrom">Mail From:</label>
                                        {!! Form::text('mailFrom',  null, ['id'=>'mail_from', 'name'=>'mail_from', 'class'=>'form-control']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="recepientTo">Recepient To:</label>
                                        {!! Form::text('recepientTo', null, ['id'=>'rec_to', 'name'=>'rec_to', 'class'=>'form-control']) !!}
                                    </div>
                        </div>
                        <div class="col-md-12">
                            <!-- Save and Back button-->
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
             $('#clear').on('click', function () {
                 $('#mail_from').val('');
                 $('#rec_to').val('');
             })

             $('#serverName').on('change', function() {
                 var selectedServerId = $(this).val();

                 // AJAX request to fetch data based on the selected server ID
                 $.ajax({
                     url: "{!! route('loopback.api') !!}",
                     method: 'GET',
                     data: {
                         serverId: selectedServerId
                     },
                     success: function(response) {
                         // Handle success response and update UI
                         //console.log(response);
                     },
                     error: function(xhr, status, error) {
                         // Handle error
                         //console.error(xhr.responseText);
                     }
                 });
             });

             $('#report-form').submit(function (e) {
                 e.preventDefault();

                 var formData = $(this).serialize();

                 $.ajax({
                     type: 'GET',
                     url: "{{ route('loopback.smtpTest') }}",
                     data: formData,
                     success: function (response) {
                         //console.log(response);
                         sendTestCmd(response);
                     },
                     error: function (error) {
                         //console.log(error);
                     }
                 });
             });

             function sendTestCmd(response) {
                 const selectedServer = response.data;
                 const mailFrom = response.MailFrom;
                 const recTo = response.RecepientTo;

                //  const serverAddress = 'wss://' + selectedServer + '/wss/';
                 //const serverAddress = 'wss://' + selectedServer + ':8000';
                 const serverAddress = 'wss://spmr.mygovuc.gov.my/' + selectedServer;
                 const socket = new WebSocket(serverAddress);

                 socket.addEventListener('open', (event) => {
                     const command = 'swaks --from '+ mailFrom +' --to '+ recTo +' --server '+ selectedServer +':25 --header "Subject: Internal Relay test - `date` from `hostname -f`" --body "Hello this is an internal relay test. PLEASE IGNORE."';
                     socket.send(command);
                 });

                 socket.addEventListener('message', (event) => {
                     const response = event.data;
                     if (response.includes('Failed')) {
                         alert("Failed to send SMTP Test on " + selectedServer + ".");
                     } else {
                         alert("Successfully to send SMTP Test on " + selectedServer + ".");
                     }
                     socket.close();
                     location.reload();
                 });
             }

         });
     </script>
 @endpush
