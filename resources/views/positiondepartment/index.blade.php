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
                               Registered Position & Department
                            </h1>
                        </div>
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
                            <h4 class="mb-0 text-primary">Position</h4>
                        </div>
                        @can('position_department_add')
                        <div class="col-auto">
                            <a href="{{ route ('positiondepartment.addposition')}}" class="btn btn-sm btn-light text-primary">
                                <i class="me-1" data-feather="plus"></i> Add New Position
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
                                <th>Name</th>
                                <th>Remark</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach ($positions as $position)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ $position->name }}</td>
                                <td>{{ $position->remark }}</td>
                                <td>
                                 @if($position->status == 1)
                                    <span class="badge bg-green text-white">Active</span>
                                @else
                                    <span class="badge bg-red text-white">Inactive</span>
                                @endif
                                </td>
                                <td>{{ $position->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <!-- <a class="btn btn-datatable btn-icon me-2 text-blue" title="View Detail" href=""><i data-feather="eye"></i></a> -->
                                    @can('position_department_update')
                                    <a class="btn btn-datatable btn-icon btn-transparent-dark me-2 text-primary" title="Edit Detail" href="{{ route('positiondepartment.updateposition', [$position['id']]) }}"><i data-feather="edit"></i></a>
                                    @endcan
                                    @can('position_department_delete')
                                    <form class="btn btn-datatable btn-icon me-2 text-blue" action="{{ route('positiondepartment.positiondelete', [$position['id']]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this data?')">
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
            </div><hr>
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0 text-primary">Department</h4>
                        </div>
                        @can('position_department_add')
                        <div class="col-auto">
                            <a href="{{ route ('positiondepartment.adddepartment')}}" class="btn btn-sm btn-light text-primary">
                                <i class="me-1" data-feather="plus"></i> Add New Department
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatablesSimple3" class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Remark</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach ($departments as $department)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->remark }}</td>
                                <td>
                                 @if($department->status == 1)
                                    <span class="badge bg-green text-white">Active</span>
                                @else
                                    <span class="badge bg-red text-white">Inactive</span>
                                @endif
                                </td>
                                <td>{{ $department->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <!-- <a class="btn btn-datatable btn-icon me-2 text-blue" title="View Detail" href=""><i data-feather="eye"></i></a> -->
                                    @can('position_department_update')
                                    <a class="btn btn-datatable btn-icon btn-transparent-dark me-2 btn-primary" title="Edit Detail" href="{{ route('positiondepartment.updatedepartment', ['id' => $department->id]) }}"><i data-feather="edit"></i></a>
                                    @endcan
                                    @can('position_department_delete')
                                    <form class="btn btn-datatable btn-icon me-2 text-blue" action="{{ route('positiondepartment.departmentdelete', [$department['id']]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this data?')">
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
        ],
        columnDefs: [
        { type: 'date-uk', targets: [/* index of your date column */] }
    ]
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
        ],
        columnDefs: [
        { type: 'date-uk', targets: [/* index of your date column */] }
    ]
    });

    // Append export buttons to DataTable 2's search container
    $('.dataTables_filter', table2.table().container()).addClass('d-inline-block mr-2').append($('.dt-buttons', table2.table().container()));

})(jQuery);

    </script>
    @endpush
