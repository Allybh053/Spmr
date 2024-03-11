@extends('layouts.app')
@section('content')
     <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-fluid px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"></div>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                                        <li class="breadcrumb-item">SMTP Relay Request MyGovUC3.0</li>
                                    </ol>
                                </nav>
                            </h1>
                        </div>
                        @can('smtp_mail_relay_add')
                            <div class="col-12 col-xl-auto mb-3">
                                <a class="btn btn-sm btn-light text-primary" href="{{ route('smtp_relay.daftar') }}">
                                    <i class="me-1" data-feather="plus"></i>
                                    Add New SMTP Relay Request
                                </a>
                            </div>
                            @endcan
                    </div>
                </div>
            </div>
        </header>
        <div class="container-fluid px-4 mb-4">
            @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Message',
                        text: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 3000  // Automatically close after 3 seconds
                    });
                });
            </script>
            @endif


            <!-- add choose by server then show this searchong by server -->
           <div class="card">
                <div class="card-header">Searching</div>
                <div class="card-body">
                <div class="serverBox col-md-6">
                                <div class="mb-3">
                    <label class="small mb-1" for="inputAgencies">Choose Server:</label>
                        {!! Form::select('server', $serverconfig,  $defaultServerId,  ['id'=>'server', 'name'=>'server', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                        <p class="card-text mt-1 mb-1" id="ipAddress"></p>
                        <div class="small text-muted"></div>
                    </div>
            </div>

                    <div  id="searchBox">
                    <form id="searchForm">
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                                <div class="mb-3">
                                        <label class="small mb-1" for="inputAgencies">Kementerian/Agensi/SUK:</label>
                                        {!! Form::select('agency', $agency, isset($applicantInfo) ? $applicantInfo->agencies : null,  ['id'=>'agency', 'name'=>'agency', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3" id="calendarSelect">
                                <label class="small mb-1" for="calendarType">Select Calendar:</label>
                                <select class="form-control select2" id="calendarType" name="calendarType">
                                    <option value="date">Date</option>
                                    <option value="month">Month</option>
                                    <option value="year">Year</option>
                                    <option value="dateRange">Date Range</option>
                                </select>
                            </div>
                        </div>

                        </div>
                        <div class="row gx-3 mb-3">
                            <div class="mb-3" id="calendarInputs">
                                <!-- Inputs for date, month, year, and date range will be dynamically appended here -->
                            </div>
                        </div>
                            <div class="text-end"> <!-- Align the button to the right -->
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                    </form>

                    <table class="display table table-striped mt-2" cellspacing="0" width="100%">
                        <tfoot id="totals" class="rounded p-3 mb-3 mt-3">
                            <tr>
                                <td colspan="3" style="border-left: 1px solid black;"><span style="font-weight: bold;">Total Aplikasi</span></td>
                                <td colspan="1"><span style="font-weight: bold;">:</span></td>
                                <td colspan="2" style="text-align:left;border-right: 1px solid black;"><span id="totalApplications" style="font-weight: bold;"></span></td>
                                <td colspan="3"><span style="font-weight: bold;">Total IP</span></td>
                                <td colspan="1"><span style="font-weight: bold;">:</span></td>
                                <td colspan="2"  style="text-align:left;border-right: 1px solid black;"><span id="totalIP" style="font-weight: bold;"></span></td>
                                <td colspan="3"><span style="font-weight: bold;">Total Deactivated</span></td>
                                <td colspan="1"><span style="font-weight: bold;">:</span></td>
                                <td colspan="2"  style="text-align:left;border-right: 1px solid black;"><span id="totalDeactivated" style="font-weight: bold;"></span></td>
                            </tr>
                        </tfoot>
                    </table>


            </div>
                </div>
            </div>


        </div>

        <!-- Main page content-->
        <div class="container-fluid px-4">
            @if(session('success'))
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading">Message</h5>
                    {{ session('success') }}
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card">
                 <div class="card-header">List SMTP Relay Request MyGovUC3.0</div>
                <div class="card-body">
                <div class="table-responsive">
                    <table id="spmrDatatable"  class="display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Form ID</th>
                                <th>Ticket No.</th>
                                <th>Types Application</th>
                                <th>Project Name</th>
                                <th>Server Mail Relay</th>
                                <th>Total Ip Address</th>
                                <th>Date Created</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </main>

 @endsection

@push('script')
    <script>
        (function ($) {
            var table = $('#spmrDatatable').DataTable({
                processing: true,
                serverSide: false,
                ordering: true,
                ajax:  {
                    "url" :"{{ route('smtp_relay.datatable') }}",
                    "method": 'POST',
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "data": function (d) {
                        // Add the agencies and calendar type to the request data
                        d.agency = $('#agency').val(); // Assuming the select input for agencies has the ID 'agency'
                        d.calendarType = $('#calendarType').val(); // Assuming the select input for calendar type has the ID 'calendarType'
                        d.month = $('#month').val(); // Assuming the select input for agencies has the ID 'agency'
                        d.year = $('#year').val();
                        d.dateFrom = $('#input2_from').val(); // Assuming the select input for agencies has the ID 'agency'
                        d.dateTo = $('#input2_to').val();
                    },
                    // error callback to handle error
                    "error": function(xhr, error, thrown) {
                        console.log("Error occurred!");
                        console.log(xhr, error, thrown);
                    }
                },
                columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, class: 'text-center' },
                        { data: 'form_id', name: 'form_id', class: 'text-center' },
                        { data: 'ticket_id', name: 'ticket_number', class: 'text-center' },
                        { data: 'types_req', name: 'types_req', class: 'text-center' },
                        { data: 'project_name', name: 'project_name', class: 'text-center' },
                        { data: 'smtp_relay_server', name: 'smtp_relay_server', class: 'text-center' },
                        { data: 'total_ip', name: 'total_ip', class: 'text-center' },
                        { data: 'created_at', name: 'created_at', class: 'text-center' },
                        { data: 'status', name: 'status', class: 'text-center' },
                        { data: 'action', name: 'type', class: 'text-center', orderable: false, searchable: false },
                    ],
            });

        })(jQuery);



        $(document).ready(function () {
            $('#calendarType').change(function () {
        var selectedType = $(this).val();
        $('#calendarInputs').empty(); // Clear previous inputs

        if (selectedType === 'month') {
            $('#calendarInputs').append('<div class="col-md-6"><label for="input2_month">Month:</label><input type="month" class="form-control" name="input2_month" id="month"></div>');
        } else if (selectedType === 'year') {
            var currentYear = new Date().getFullYear();
            var selectYearHtml = '<div class="col-md-6"><label for="input2_year">Year:</label><select class="form-control" name="input2_year" id="year">';
            for (var year = currentYear; year >= currentYear - 10; year--) {
                selectYearHtml += '<option value="' + year + '">' + year + '</option>';
            }
            selectYearHtml += '</select></div>';
            $('#calendarInputs').append(selectYearHtml);
        }  else if (selectedType === 'dateRange') {
            $('#calendarInputs').append('<div class="row"><div class="col-md-6"><label for="input2_from">Date From:</label><input type="date" class="form-control" name="input2_from" id="input2_from" placeholder="From"></div><div class="col-md-6"><label for="input2_to">Date To:</label><input type="date" class="form-control" name="input2_to" id="input2_to" placeholder="To"></div></div>');
        } else {
            $('#calendarInputs').append('<div class="col-md-6"><label for="input2">Calendar:</label><input type="date" class="form-control" name="input2" placeholder="Calendar"></div>');
        }
    });

        $('#searchForm').submit(function (e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                type: 'GET',
                url: "{{ route('smtp_relay.index.search') }}",
                data: formData,
                success: function (response) {
                    $('#totalApplications').text(response.totalApplications);
                    $('#totalIP').text(response.totalIP);
                    $('#totalDeactivated').text(response.totalDeactivated);

                    $('#spmrDatatable').DataTable().ajax.reload();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });

        $('#searchBox').fadeOut();
                        $('.serverBox').fadeIn();

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
                    $('#searchBox').fadeIn();
                        $('.serverBox').fadeOut();

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
