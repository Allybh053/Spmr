@extends('layouts.app')
@push('css')
<style>
</style>
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
                                        <div class="page-header-icon">
                                            <i data-feather="trello"></i>
                                        </div>
                                        <li class="breadcrumb-item">Monthly Report</li>
                                    </ol>
                                </nav>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{--<div class="container-xl px-4 mt-4">
            <div class="row">
                <div class="col-xl-12">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">Monthly Report</div>


                        <div class="card-body mt-1 mb-4">
                        <div class="table-responsive">
                        <table id="spmrDatatable" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            --}}{{-- {{ $logsPaginated->links() }} --}}{{--
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>--}}

<div class="container-xl px-4 mt-4" id="searchingBox">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">Filter</div>
                <div class="card-body">
                    <form id="searchForm">
                        <div class="serverBox col-md-6">
                            <div class="mb-3">
                                <label class="small mb-1" for="inputAgencies">Choose Server:</label>
                                {!! Form::select('server', $serverconfig,  null,  ['id'=>'server', 'name'=>'server', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                <p class="card-text mt-1 mb-1" id="ipAddress"></p>
                                <div class="small text-muted"></div>
                            </div>
                        </div>

                        <div  id="searchBox">
                            {{--<form id="searchForm">--}}
                            <div class="row gx-3 mb-3">
                                <div class="mb-3" id="calendarInputs">
                                    <!-- Inputs for date, month, year, and date range will be dynamically appended here -->
                                </div>
                            </div>
                            <div class="text-end"> <!-- Align the button to the right -->
                                <button type="button" class="btn btn-secondary" id="reset">Reset</button>
                                <button type="submit" class="btn btn-primary">Search</button>
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


// Initialize DataTable with custom sorting for date column
var table = $('#spmrDatatable').DataTable({
    processing: true,
    serverSide: false,
    ordering: true,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
    dom: '<"top"fBl>rt<"bottom"ip>',
    buttons: [
        { extend: 'copy', text: 'Copy', exportOptions: { columns: ':visible' } },
        { extend: 'csv', text: 'CSV', exportOptions: { columns: ':visible' } },
        { extend: 'excel', text: 'Excel', exportOptions: { columns: ':visible' } },
        { extend: 'pdf', text: 'PDF', exportOptions: { columns: ':visible' } },
        { extend: 'print', text: 'Print', exportOptions: { columns: ':visible' } }
    ],
    columnDefs: [
        { type: 'date-uk', targets: ['dd/mm/yyyy'] }
    ],
    drawCallback: function(settings) {
            // Reinitialize Feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Reinitialize any other JavaScript functionality as needed
            // For example, tooltips, modals, etc.
        }
});

// Adjust positioning of search input and export buttons
$('.dataTables_filter').addClass('d-inline-block mr-2'); // Make search input inline
$('.dt-buttons').appendTo('.dataTables_filter'); // Append export buttons to search container
</script>
@endpush

