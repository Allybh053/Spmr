@extends('layouts.app')
@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="cloud"></i></div>
                               Registered Domain
                            </h1>
                        </div>
                        @can('domain_name_add')
                        <div class="col-12 col-xl-auto mb-3">
                            <a class="btn btn-sm btn-light text-primary" href="{{ route('domain.register') }}">
                                <i class="me-1" data-feather="plus"></i>
                                Add New Domain
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </header>
        <div class="container-xl px-4">
            @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 3000  // Automatically close after 3 seconds
                    });
                });
            </script>
            @endif

            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'error',
                            title: '',
                            text: '{{ session('error') }}',
                            showConfirmButton: false,
                            timer: 3000  // Automatically close after 3 seconds
                        });
                    });
                </script>
            @endif
            <div class="card mb-4">
                <div class="card-header">Domain</div>
                <div class="card-body">
                    <table id="datatablesSimple1"  class="display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Domain (FQDN)</th>
                                <th>Ministry</th>
                                <th>Agency</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach ($fqdndata as $data)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ $data->domain_name }}</td>
                                <td>{{ $data->ministry_name }}</td>
                                <td>{{ $data->agencies_name }}</td>
                                <td>
                                 @if($data->enabled == 1)
                                    <span class="badge bg-green-soft text-green">Active</span>
                                @else
                                    <span class="badge bg-red-soft text-red">Inactive</span>
                                @endif
                                </td>
                                <td>{{ $data->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <!-- <a class="btn btn-datatable btn-icon me-2 text-blue" title="View Detail" href=""><i data-feather="eye"></i></a> -->
                                    @can('domain_name_update')
                                    <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" title="Edit Detail" href="{{ route('domain.edit', ['id' => $data->id]) }}"><i data-feather="edit"></i></a>
                                    @endcan
                                    @can('domain_name_delete')
                                    <form class="btn btn-datatable btn-icon me-2 text-blue" action="{{ route('domain.delete', [$data['id']]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this FQDN?')">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-datatable btn-icon text-danger btn-transparent" title="Delete FQDN"><i data-feather="trash-2"></i></button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                             @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </main>
@endsection
@push('script')
    <script>
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
                    columns: ':visible',
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
                exportOptions: {
                    columns: ':visible',
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
                exportOptions: {
                    columns: ':visible',
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
                exportOptions: {
                    columns: ':visible',
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
                exportOptions: {
                    columns: ':visible',
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
})(jQuery);

    </script>
    @endpush
