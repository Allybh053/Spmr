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
                                    </ol>
                                </nav>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-xl px-4 mt-4" id="searchingBox">
            <div class="row">
                <div class="col-xl-12">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">Uc Charts & Graphics</div>


                        <div class="card-body mt-1 mb-4">
                        <form id="report-form">
                        <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="serverName">Server Name:</label>
                                        {!! Form::select('serverName', $serverconfig, null, ['id'=>'serverName', 'name'=>'serverName', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                    </div>
                                    <div class="col-md-4">
                                        <!-- Save and Back button-->
                                        <div style="padding: 25px; text-align: left;">
                                            <button class="btn btn-success" id="searching">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

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
                    "url" :"{{ route('uc.datatable') }}",
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
                    dom: 'Bfrtip', // Show buttons at the top
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
            });

        })(jQuery);
    </script>
@endpush
