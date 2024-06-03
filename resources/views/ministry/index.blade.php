@extends('layouts.app')
@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-fluid px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="cloud"></i></div>
                               Registered Ministry
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="container-fluid px-4">
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
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0 text-primary">Ministry</h4>
                        </div>
                        @can('ministry_add')
                        <div class="col-auto">
                            <a href="{{ route ('ministry.addministry')}}" class="btn btn-sm btn-light text-primary">
                                <i class="me-1" data-feather="plus"></i> Add New Ministry
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatablesSimple1" class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Code Name</th>
                                <th>Name</th>
                                <th>Remark</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach ($ministrys as $ministry)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ $ministry->code_name }}</td>
                                <td>{{ $ministry->name }}</td>
                                <td>{{ $ministry->remark }}</td>
                                <td>
                                 @if($ministry->status == 1)
                                    <span class="badge bg-green text-white">Active</span>
                                @else
                                    <span class="badge bg-red text-white">Inactive</span>
                                @endif
                                </td>
                                <td>{{ $ministry->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @can('ministry_update')
                                    <a class="btn btn-datatable btn-icon btn-transparent-dark me-2 text-primary" title="Edit Detail" href="{{ route('ministry.updateministry', [$ministry['id']]) }}"><i data-feather="edit"></i></a>
                                    @endcan
                                    @can('ministry_delete')
                                    <form class="btn btn-datatable btn-icon me-2 text-blue" action="{{ route('ministry.ministrydelete', [$ministry['id']]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this data?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-datatable btn-icon text-danger btn-transparent" title="Delete Data"><i data-feather="trash-2"></i></button>
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
    // DataTable 1
    var table1 = $('#datatablesSimple1').DataTable({
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
        drawCallback: function(settings) {
            // Reinitialize Feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Reinitialize any other JavaScript functionality as needed
            // For example, tooltips, modals, etc.
        }
    });

    // Append export buttons to DataTable 1's search container
    $('.dataTables_filter', table1.table().container()).addClass('d-inline-block mr-2').append($('.dt-buttons', table1.table().container()));

})(jQuery);


(function ($) {
    // DataTable 2
    var table2 = $('#datatablesSimple3').DataTable({
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
        drawCallback: function(settings) {
            // Reinitialize Feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Reinitialize any other JavaScript functionality as needed
            // For example, tooltips, modals, etc.
        }
    });

    // Append export buttons to DataTable 2's search container
    $('.dataTables_filter', table2.table().container()).addClass('d-inline-block mr-2').append($('.dt-buttons', table2.table().container()));

})(jQuery);

    </script>
    @endpush
