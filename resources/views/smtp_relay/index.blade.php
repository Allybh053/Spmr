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
                               {{-- <a class="btn btn-sm btn-green" href="#">
                                    <i class="me-1" data-feather="plus"></i>
                                    Bulk Add
                                </a>--}}
                                <a class="btn btn-sm btn-blue" href="{{ route('smtp_relay.daftar') }}">
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



        <!-- Main page content-->
        <div class="container-fluid px-4">
            {{-- @if(session('success'))
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading">Message</h5>
                    {{ session('success') }}
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif --}}
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
                                {{--<th>Total Ip Address</th>--}}
                                <th>Ip Address</th>
                                <th>Date Approval</th>
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
  $(document).ready(function() {
        var table = $('#spmrDatatable').DataTable({
            processing: true,
            serverSide: false,
            ordering: true,
            lengthMenu: false,
            ajax: {
                "url": "{{ route('smtp_relay.datatable') }}",
                "method": 'POST',
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "data": function (d) {
                    d.agency = $('#agency').val();
                    d.calendarType = $('#calendarType').val();
                    d.month = $('#month').val();
                    d.year = $('#year').val();
                    d.dateFrom = $('#input2_from').val();
                    d.dateTo = $('#input2_to').val();
                },
                "error": function (xhr, error, thrown) {
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
                { data: 'ip_addr_server', name: 'ip_addr_server', class: 'text-center' },
                { data: 'date_verification_3', name: 'date_verification_3', class: 'text-center' },
                { data: 'created_at', name: 'created_at', class: 'text-center' },
                { data: 'status', name: 'status', class: 'text-center' },
                { data: 'action', name: 'type', class: 'text-center', orderable: false, searchable: false },
            ],
            dom: 'Bfrtip', // Show buttons at the top
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
    </script>
@endpush
