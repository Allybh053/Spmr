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
                                        <li class="breadcrumb-item">UC Overview</li>
                                        <li class="breadcrumb-item">Domain Routing Map</li>
                                    </ol>
                                </nav>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-fluid px-4">
            <div class="card">
            <div class="card-header">List of Registered Domain Routing Map</div>
                <div class="card-body">
                <div class="table-responsive"> 
                    <table id="spmrDatatable"  class="display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Ticket No.</th>
                                <th>Types Application</th>
                                <th>Project Name</th>
                                <th>Server Name</th>
                                <th>IP Address Server</th>
                                <th>FQDN System</th>
                                <th>Date Created</th>
                                <th>Date Updated</th>
                                <th>Status</th>
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
<!-- <script type="text/javascript" src="{{ URL::asset ('js/datatables/datatables-simple-demo.js') }}"></script> -->
    <script>
        (function ($) {
            var table = $('#spmrDatatable').DataTable({
                processing: true,
                serverSide: false,
                ordering: true,
                ajax:  {
                    "url" :"{{ route('uc.domain.datatable') }}",
                    "method": 'POST',
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // error callback to handle error
                    "error": function(xhr, error, thrown) {
                        console.log("Error occurred!");
                        console.log(xhr, error, thrown);
                    }
                },
                columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, class: 'text-center' },
                        { data: 'ticket_number', name: 'ticket_number', class: 'text-center' },
                        { data: 'types_application', name: 'types_application', class: 'text-center' },
                        { data: 'project_name', name: 'project_name', class: 'text-center' },
                        { data: 'server_name', name: 'server_name', class: 'text-center' },
                        { data: 'ip_address_server', name: 'ip_address_server', class: 'text-center' },
                        { data: 'fqdn_system', name: 'fqdn_system', class: 'text-center' },
                        { data: 'date_created', name: 'date_created', class: 'text-center' },
                        { data: 'date_updated', name: 'date_updated', class: 'text-center' },
                        { data: 'status', name: 'status', class: 'text-center' },
                    ],
            });

        })(jQuery);
    </script>
@endpush
