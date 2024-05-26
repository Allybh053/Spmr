@extends('layouts.app')
@section('content')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                           {{-- <div class="page-header-icon"><i data-feather="mail"></i></div>
                            Mail Upstream Routing (Domain)--}}

                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <div class="page-header-icon">
                                        <i data-feather="mail"></i>
                                    </div>
                                    <li class="breadcrumb-item">Mail Upstream Routing (Domain)</li>
                                </ol>
                            </nav>

                        </h1>
                    </div>
                    {{--<div class="col-12 col-xl-auto mb-3">

                    </div>--}}
                </div>
            </div>
        </div>
    </header>
    <!-- Main page content-->
    {{--SIDI CODE--}}
    <div class="container-fluid px-4">
        @if(session('success'))
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">Message</h5>
                {{ session('success') }}
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form  id="searchForm" class="ajax-form-search" novalidate action="{{ route('mailrelay.mailupstream') }}" method="GET">
                    <!-- Form Row-->
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="serverconfig">Server Name:</label>
                            {{--<select id="serverconfig" name="serverconfig" class="form-control select2">
                                <option value="">Select an option</option>
                                @foreach ($serverconfig as $server)
                                    <option value="{{ $server->id }}">{{ $server->hostname }}</option>
                                @endforeach
                            </select>--}}

                            {!! Form::select('server', $serverconfig,  null,  ['id'=>'server', 'name'=>'server', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                            <p class="card-text mt-1 mb-1" id="ipAddress"></p>
                            <div class="small text-muted"></div>
                        </div>
                        <div class="col-md-4">
                            <div style="padding: 25px; text-align: left;">
                                <button class="btn btn-success" id="searching">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
            <hr>
        {{--<div class="card">
            <div class="card-body">
                <table id="datatablesSimple1" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Domain Name</th>
                            <th>SMTP</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($serverdetail)
                            @foreach ($serverdetail as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->smtp }}</td>
                                <td>
                                    <button type="submit" class="btn btn-datatable btn-icon text-danger btn-transparent" title="Delete User"><i data-feather="trash-2"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">No data available</td>
                            </tr>
                        @endisset

                    </tbody>
                </table>
            </div>
            <hr>
            <div class="card-body">
                <label class="small mb-1" for="serverconfig">Terminal</label>
                <div id="shell-in-a-box"></div>
            </div>
        </div>--}}

        {{--ROMIE CODE--}}
            <div class="row mb-4 mt-4">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body d-flex justify-content-center flex-column">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="me-3">
                                                    <h5>Total Domain Activated</h5>
                                                    <span>Updated every 30 minutes</span>
                                                    <div class="inline">
                                                        <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                        <h2 id="totalIP" style="display: inline-block; margin-left: 5px;">--</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body d-flex justify-content-center flex-column">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="me-3">
                                                    <h5>Total Domain Deactivated</h5>
                                                    <span>Updated every 30 minutes</span>
                                                    <div class="inline">
                                                        <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                        <h2 id="totalDeactivated" style="display: inline-block; margin-left: 5px;">--</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body d-flex justify-content-center flex-column">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="me-3">
                                                    <h5>Total Domain Registered</h5>
                                                    <span>Updated every 30 minutes</span>
                                                    <div class="inline">
                                                        <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                        <h2 id="totalDomain" style="display: inline-block; margin-left: 5px;">--</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            {{-- Table List Domain Start --}}
                            <br>
                            <div class="card">
                                <div class="card-header">List of Domain Registered</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="listDomain"  class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Domain</th>
                                                {{--<th>Destination</th>--}}
                                                <th>Status</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>

    </div>

    {{--<style>
        th,td {
            font-size: var(--bs-body-font-size);
        }
    </style>--}}



</main>

@endsection
@push('script')
<script>

/*SIDI CODE*/
/*
        // Make AJAX request to fetch Shell In A Box session
        fetch('{{ route('shell-in-a-box') }}')
        .then(response => response.text())
        .then(data => {
            // console.log(data);
            // Update the content on your page
            document.getElementById('shell-in-a-box').innerHTML = data;
        })
        .catch(error => {
            console.error('Error fetching Shell In A Box session:', error);
        });

    (function ($) {
        var table = $('#datatablesSimple1').DataTable({
            processing: true,
            serverSide: false,
            ordering: true,
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            dom: '<"top"fBl>rt<"bottom"ip>',
            buttons: [
                {
                    extend: 'copy',
                    text: 'Copy',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ]
        });

        // Adjust positioning of search input and export buttons
        $('.dataTables_filter').addClass('d-inline-block mr-2'); // Make search input inline
        $('.dt-buttons').appendTo('.dataTables_filter'); // Append export buttons to search container
    })(jQuery);

*/


/*ROMIE CODE*/
$(document).ready(function () {

    // Event listener for select change
    $('#server').on('change', function() {
        var selectedServerId = $(this).val();

        // AJAX request to fetch data based on the selected server ID
        $.ajax({
            url: "{!! route('mailrelay.api') !!}",
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

    var tableMailRoute = $('#listDomain').DataTable({
        ordering: true,
        columns: [
            {
                data: null,
                name: 'DT_RowIndex2',
                orderable: false,
                searchable: false,
                class: 'text-center',
                render: function (data, type, row, meta) {
                    return meta.row + 1; // This will render the index starting from 1
                }
            },
            { data: 'source', name: 'source', class: 'text-center' },
            /*{ data: 'destination', name: 'destination', class: 'text-center' },*/
            { data: 'status', name: 'status', class: 'text-center' },
        ],
        dom: '<"top"fBl>rt<"bottom"ip>',
        buttons: [
            {
                extend: 'copy',
                text: 'Copy',
                title: 'List of Domain Registered',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'csv',
                text: 'CSV',
                title: 'List of Domain Registered',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excel',
                text: 'Excel',
                title: 'List of Domain Registered',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdf',
                text: 'PDF',
                title: 'List of Domain Registered',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'print',
                text: 'Print',
                title: 'List of Domain Registered',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ],
        data: [], // Initialize with empty data
        drawCallback: function(settings) {
            var api = this.api();
            api.column(0, {search:'applied', order:'applied'}).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }
    });
    $('.dataTables_filter', tableMailRoute.table().container()).addClass('d-inline-block mr-2').append($('.dt-buttons', tableMailRoute.table().container()));


    $('#searchForm').submit(function (e) {
        e.preventDefault();

        var formData = $(this).serialize();
        if (formData === 'server=') {
            alert("Please Choose Server in options field.");
        } else {
            let timerInterval;

            Swal.fire({
                title: "Please wait...",
                //html: "Please wait...",
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                        if (timer) {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                        }
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            });

            $.ajax({
                type: 'GET',
                url: "{{ route('mailrelay.index.search') }}",
                data: formData,
                success: function (response) {
                    // Hide the SweetAlert timer
                    Swal.close();

                    // Update UI with the response data
                    $('#totalIP').text(response.totalDomainActivated || "0");
                    $('#totalDeactivated').text(response.totalDomainDeactivated || "0");
                    $('#totalDomain').text(response.totalDomainRegistered || "0");

                    // Add index to each row for jadual2
                    $.each(response.dataMailRoute, function (index, row) {
                        row['DT_RowIndex'] = index + 1;
                        row['status'] = row['status'] ===  true? 'Activated' : 'Deactivated';
                    });
                    // Clear existing data and add new data to the DataTable
                    tableMailRoute.clear().rows.add(response.dataMailRoute).draw();

                },
                error: function (error) {
                    // Hide the SweetAlert timer
                    Swal.close();

                    // Handle the error
                    console.log(error);
                }
            });
        }
    });






});


</script>
@endpush
