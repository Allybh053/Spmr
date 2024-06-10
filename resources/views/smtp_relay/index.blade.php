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
                                         <li class="breadcrumb-item">SMTP Relay Request MyGovUC</li>
                                    </ol>
                                </nav>
                            </h1>
                        </div>

                        @can('smtp_mail_relay_add')
                            <div class="col-12 col-xl-auto mb-3">
                                {{--<a class="btn btn-sm btn-green" href="#" data-bs-toggle="modal" data-bs-target="#importModal">
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
        <!-- Main page content-->
        <div class="container-fluid px-4">
            @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 2500 // milliseconds
                    });
                });
            </script>
            @endif
            {{-- @if(request()->has('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Message',
                        text: '{{ request()->get('message') }}',
                        showConfirmButton: false,
                        timer: 5000  // Automatically close after 3 seconds
                    });
                });
            </script>
            @endif --}}
            <div class="card">
                 <div class="card-header">List SMTP Relay Request MyGovUC</div>
                <div class="card-body">
                <div class="table-responsive">
                    <table id="spmrDatatable"  class="display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Date Created</th>
                                <th>Serial No.</th>
                                <th>Ticket No.</th>
                                <th>Types Application</th>
                                <th>Agency Name</th>
                                <th>Project Name</th>
                                <th>Server Mail Relay</th>
                                <th>Ip Address</th>
                                <th>Date Approval</th>
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

     <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModal" aria-hidden="true">
         <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title">Bulk Add</h5>
                     <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>

                 <form method="post" action="{{ route('smtp_relay.import') }}" enctype="multipart/form-data">
                     @csrf
                     <div class="modal-body">
                         <input class="form-control" type="file" name="file">
                     </div>
                     <div class="modal-footer">
                         <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
                         <button type="submit" class="btn btn-success">Import</button>
                     </div>
                 </form>

             </div>
         </div>
     </div>

 @endsection

@push('script')

<script src="{{ asset('js/vendor/moment.min.js') }}"></script>
<script>
  $(document).ready(function() {
        var table = $('#spmrDatatable').DataTable({
            processing: true,
            serverSide: false,
            ordering: true,
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ], // Customize the length menu options
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
                { data: 'created_at', name: 'created_at', class: 'text-center' },
                { data: 'form_id', name: 'form_id', class: 'text-center' },
                { data: 'ticket_id', name: 'ticket_number', class: 'text-center' },
                { data: 'types_req', name: 'types_req', class: 'text-center' },
                { data: 'agencies', name: 'agencies', class: 'text-center' },
                { data: 'project_name', name: 'project_name', class: 'text-center' },
                { data: 'smtp_relay_server', name: 'smtp_relay_server', class: 'text-center' },
                { data: 'ip_addr_server', name: 'ip_addr_server', class: 'text-center' },
                { data: 'updated_at', name: 'updated_at', class: 'text-center' },
                { data: 'status', name: 'status', class: 'text-center' },
                { data: 'action', name: 'type', class: 'text-center', orderable: false, searchable: false },
            ],
            dom: '<"top"fBl>rt<"bottom"ip>',
            buttons: [
            {
                extend: 'copy',
                text: 'Copy',
                title: 'List SMTP Relay Request MyGovUC',
                exportOptions: {
                    columns: ':not(:last-child)', // Exclude the last column
                    format: {
                        body: function (data, row, column, node) {
                            // Add row number for the first column
                            return column === 0 ? row + 1 : data;
                        }
                    }
                }
            },
            {
                extend: 'csv',
                text: 'CSV',
                title: 'List SMTP Relay Request MyGovUC',
                exportOptions: {
                    columns: ':not(:last-child)', // Exclude the last column
                    format: {
                        body: function (data, row, column, node) {
                            // Add row number for the first column
                            return column === 0 ? row + 1 : data;
                        }
                    }
                }
            },
            {
                extend: 'excel',
                text: 'Excel',
                title: 'List SMTP Relay Request MyGovUC',
                exportOptions: {
                    columns: ':not(:last-child)', // Exclude the last column
                    format: {
                        body: function (data, row, column, node) {
                            // Add row number for the first column
                            return column === 0 ? row + 1 : data;
                        }
                    }
                }
            },
            {
                extend: 'pdf',
                text: 'PDF',
                title: 'List SMTP Relay Request MyGovUC',
                exportOptions: {
                    columns: ':not(:last-child)', // Exclude the last column
                    format: {
                        body: function (data, row, column, node) {
                            // Add row number for the first column
                            return column === 0 ? row + 1 : data;
                        }
                    }
                }
            },
            {
                extend: 'print',
                text: 'Print',
                title: 'List SMTP Relay Request MyGovUC',
                exportOptions: {
                    columns: ':not(:last-child)', // Exclude the last column
                    format: {
                        body: function (data, row, column, node) {
                            // Add row number for the first column
                            return column === 0 ? row + 1 : data;
                        }
                    }
                }
            }
        ],
            columnDefs: [
        { type: 'date-uk', targets: [/* index of your date column */] }
    ],
            drawCallback: function(settings) {
                var api = this.api();
                api.column(0, {search:'applied', order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }
        });

        $('.dataTables_filter').addClass('d-inline-block mr-2'); // Make search input inline
    $('.dt-buttons').appendTo('.dataTables_filter'); // Append export buttons to search container
    });
    </script>
@endpush
