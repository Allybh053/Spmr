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

                            {!! Form::select('server', $serverconfig,  $defaultServerId ?? 1,  ['id'=>'server', 'name'=>'server', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
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
                                                    <div  class="small text-muted">Updated every 30 minutes</div>
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
                                                    <div  class="small text-muted">Updated every 30 minutes</div>
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
                                                    <div  class="small text-muted">Updated every 30 minutes</div>
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

$(document).ready(function () {
    // Event listener for select change
    $('#server').on('change', function() {
        var selectedServerId = $(this).val();
        // AJAX request to fetch data based on the selected server ID
        $.ajax({
            url: "{!! route('mailrelay.api') !!}",
            method: 'GET',
            data: { serverId: selectedServerId },
            success: function(response) {
                // Handle success response and update UI
            },
            error: function(xhr, status, error) {
                // Handle error
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
            { data: 'status', name: 'status', class: 'text-center' },
        ],
        dom: '<"top"fBl>rt<"bottom"ip>',
        buttons: [
            {
                extend: 'copy',
                text: 'Copy',
                title: 'List of Domain Registered',
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            return column === 0 ? row + 1 : data;
                        }
                    }
                }
            },
            {
                extend: 'csv',
                text: 'CSV',
                title: 'List of Domain Registered',
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            return column === 0 ? row + 1 : data;
                        }
                    }
                }
            },
            {
                extend: 'excel',
                text: 'Excel',
                title: 'List of Domain Registered',
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            return column === 0 ? row + 1 : data;
                        }
                    }
                }
            },
            {
                extend: 'pdf',
                text: 'PDF',
                title: 'List of Domain Registered',
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            return column === 0 ? row + 1 : data;
                        }
                    }
                }
            },
            {
                extend: 'print',
                text: 'Print',
                title: 'List of Domain Registered',
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            return column === 0 ? row + 1 : data;
                        }
                    }
                }
            }
        ],
        data: [],
        drawCallback: function(settings) {
            var api = this.api();
            api.column(0, {search:'applied', order:'applied'}).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }
    });

    $('.dataTables_filter', tableMailRoute.table().container()).addClass('d-inline-block mr-2').append($('.dt-buttons', tableMailRoute.table().container()));

    function submitSearchForm() {
        var formData = $('#searchForm').serialize();
        if (formData === 'server=') {
            alert("Please Choose Server in options field.");
        } else {
            let timerInterval;
            Swal.fire({
                title: "Please wait...",
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                        if (timer) {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                        }
                    }, 500);
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
                    Swal.close();
                    $('#totalIP').text(response.totalDomainActivated || "0");
                    $('#totalDeactivated').text(response.totalDomainDeactivated || "0");
                    $('#totalDomain').text(response.totalDomainRegistered || "0");

                    $.each(response.dataMailRoute, function (index, row) {
                        row['DT_RowIndex'] = index + 1;
                        row['status'] = row['status'] === true ? 'Activated' : 'Deactivated';
                    });
                    tableMailRoute.clear().rows.add(response.dataMailRoute).draw();
                },
                error: function (error) {
                    Swal.close();
                    console.log(error);
                }
            });
        }
    }

    $('#searchForm').submit(function (e) {
        e.preventDefault();
        submitSearchForm();
    });

    // Call the function on page load
    submitSearchForm();
});
</script>
@endpush
