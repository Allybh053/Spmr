@extends('layouts.app')
@section('content')
                    <header class="py-10 mb-4 bg-gradient-primary-to-secondary">
                        <div class="container-xl px-4">
                            <div class="text-center">
                                <h1 class="text-white">Welcome to SPMR System</h1>
                                <p class="lead mb-0 text-white-50">Dashboard For Mail Relay Server</p>
                            </div>
                        </div>
                    </header>
                    <!-- Main page content-->
                    <div class="container-xl px-4 mt-n10">
                        <a class="card card-icon lift lift-sm mb-4" href="#">
                            <div class="row">
                                <div class="col-auto card-icon-aside bg-primary"><i class="text-white-50" data-feather="server"></i></div>
                                <div class="col">
                                   
                                    <div class="card-body py-4">
                                        {!! Form::select('server', $serverconfig, null,  ['id'=>'server', 'name'=>'server', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                        <p class="card-text mt-1 mb-1" id="ipAddress"></p>
                                        <div class="small text-muted"></div>
                                    </div>
                                  
                                </div>
                            </div>
                        </a>
                        <div class="row">
                        <div class="col-xl-4 mb-4">
                            <a class="card lift h-100" href="#!">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather-xl text-primary mb-3" data-feather="activity"></i>
                                            <h5>Total Application Registered</h5>
                                            <div class="inline">
                                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                <h2 id="totalApplications" style="display: inline-block; margin-left: 5px;">123</h2>
                                            </div>
                                        </div>
                                        <img src="{{asset ('assets/img/illustrations/dash_app.svg')}}" alt="..." style="width: 8rem" />
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-4 mb-4">
                            <a class="card lift h-100" href="#!">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather-xl text-secondary mb-3" data-feather="zap"></i>
                                            <h5>Total Domain Registered</h5>
                                            <div class="inline">
                                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                <h2 id="totalDomains" style="display: inline-block; margin-left: 5px;">123</h2>
                                            </div>
                                        </div>
                                        <img src="{{asset ('assets/img/illustrations/dash_domain.svg')}}" alt="..." style="width: 8rem" />
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-4 mb-4">
                            <a class="card lift h-100" href="#!">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather-xl text-green mb-3" data-feather="shield"></i>
                                            <h5>Numbers of Registered SASL</h5>
                                            <div class="inline">
                                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                <h2 id="totalSASL" style="display: inline-block; margin-left: 5px;">123</h2>
                                            </div>
                                        </div>
                                        <img src="{{asset ('assets/img/illustrations/dash_sasl.svg')}}" alt="..." style="width: 8rem" />
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                        <div class="row">
                            <div class="col-xxl-12">
                                <div class="card">
                                    <div class="card-header text-center p-5">
                                        <h5>View MailQueue Statistic (last 5 minutes)</h5>
                                        <p class="mb-4">Create view monitoring for Email Queue Health</p>
                                    </div>
                                    <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="spmrDatatable"  class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Column A</th>
                                                    <th>Column B</th>
                                                    <th>Column C</th>
                                                    <th>Column D</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Data 1</td>
                                                    <td>Data 1</td>
                                                    <td>Data 1</td>
                                                    <td>Data 1</td>
                                                </tr>
                                                <tr>
                                                    <td>2.</td>
                                                    <td>Data 2</td>
                                                    <td>Data 2</td>
                                                    <td>Data 2</td>
                                                    <td>Data 2</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row  mt-5">
                            <div class="col-xxl-12">
                                <div class="card">
                                    <div class="card-header text-center p-5">
                                        <h5>RealTime Service Status</h5>
                                        @php
                                            $status = 'Online';
                                        @endphp
                                    </div>
                                    <div class="card-body mainRealtime" style="min-height: auto; max-height: 800px;overflow-y: auto;">
                                    <div class="row">
                                            <div class="col-xl-4 mb-4">
                                                <a class="card lift h-100" href="#!">
                                                    <div class="card-body cardService d-flex justify-content-center flex-column">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="me-3">
                                                                <h5>Postfix</h5>
                                                            </div>
                                                            <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">

                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-xl-4 mb-4">
                                                <a class="card lift h-100" href="#!">
                                                    <div class="card-body cardService d-flex justify-content-center flex-column">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="me-3">
                                                                <h5>Fail-To-Ban</h5>
                                                            </div>
                                                            <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">

                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-xl-4 mb-4">
                                                <a class="card lift h-100" href="#!">
                                                    <div class="card-body cardService d-flex justify-content-center flex-column">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="me-3">
                                                                <h5>Apache</h5>
                                                            </div>
                                                            <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">

                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-xl-4 mb-4">
                                                <a class="card lift h-100" href="#!">
                                                    <div class="card-body cardService d-flex justify-content-center flex-column">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="me-3">
                                                                <h5>Webmin</h5>
                                                            </div>
                                                            <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">

                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-xl-4 mb-4">
                                                <a class="card lift h-100" href="#!">
                                                    <div class="card-body cardService d-flex justify-content-center flex-column">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="me-3">
                                                                <h5>Replicate Mail Relay</h5>
                                                            </div>
                                                            <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">

                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-xl-4 mb-4">
                                                <a class="card lift h-100" href="#!">
                                                    <div class="card-body cardService d-flex justify-content-center flex-column">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="me-3">
                                                                <h5>Policy Server</h5>
                                                            </div>
                                                            <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">

                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-xl-4 mb-4">
                                                <a class="card lift h-100" href="#!">
                                                    <div class="card-body cardService d-flex justify-content-center flex-column">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="me-3">
                                                                <h5>Keep Alive</h5>
                                                            </div>
                                                            <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">

                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>






                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

 @endsection

 @push('script')
 <script>
    $(document).ready(function() {

        // Event listener for select change
        $('#server').on('change', function() {
            var selectedServerId = $(this).val();

            // AJAX request to fetch data based on the selected server ID
            $.ajax({
                url: "{!! route('dashboard.api') !!}",
                method: 'GET',
                data: {
                    serverId: selectedServerId
                },
                success: function(response) {
                    // Handle success response and update UI
                    console.log(response);

                    $('#ipAddress').text(response.ipAddress);

                    $('#totalApplications').text(response.totalApplications);
                    $('#totalDomains').text(response.totalDomains);
                    $('#totalSASL').text(response.totalSASL);

                    var tbody = $('#spmrDatatable tbody');
                    tbody.empty(); // Clear existing rows

                    // Iterate through the received data and populate the table
                    $.each(response.data, function(index, item) {
                        var row = $('<tr>');
                        row.append($('<td>').text(index + 1));
                        row.append($('<td>').text(item.columnA));
                        row.append($('<td>').text(item.columnB));
                        row.append($('<td>').text(item.columnC));
                        row.append($('<td>').text(item.columnD));
                        tbody.append(row);
                    });


                    var status = response.status;

                    // Update the status of each service
                    $('.cardService').each(function() {
                        var service = $(this).find('h5').text().trim();
                        var serviceStatus = response.services[service];

                        var img = $(this).find('img');
                        if (serviceStatus === 'Online') {
                            img.attr('src', '{{ asset("assets/img/demo/online.png") }}');
                            img.attr('alt', 'Online');
                        } else {
                            img.attr('src', '{{ asset("assets/img/demo/offline.png") }}');
                            img.attr('alt', 'Offline');
                        }
                    });

                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
 @endpush
