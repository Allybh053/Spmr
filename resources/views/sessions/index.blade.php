@extends('layouts.app')
@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-fluid px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="list"></i></div>
                                List Active Sessions
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
                            <h4 class="mb-0 text-primary">Active Sessions</h4>
                        </div>
                        {{--@can('ministry_add')
                            <div class="col-auto">
                                <a href="{{ route ('ministry.addministry')}}" class="btn btn-sm btn-light text-primary">
                                    <i class="me-1" data-feather="plus"></i> Add New Ministry
                                </a>
                            </div>
                        @endcan--}}
                        <div class="col-auto">
                            <a class="btn btn-sm btn-green" onclick="refreshBtn()">
                                <i class="me-1 fas fa-redo"></i> Refresh Sessions
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatablesSimple1" class="table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Client IP</th>
                            <th>Session ID</th>
                            <th>Token</th>
                            {{--<th>URL</th>--}}
                            <th>Previous URL</th>
                            {{--<th>Referral URL</th>--}}
                            <th>Access Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($sessions as $session)
                            <tr>
                                <td>{{ $loop->iteration }}.</td>
                                <td>{{ $session->username }}</td>
                                <td>{{ $session->client_ip }}</td>
                                <td>{{ $session->session_id }}</td>
                                <td>{{ $session->token }}</td>
                                {{--<td>{{ $session->URL }}</td>--}}
                                {{--@if( $session->URL == "--")
                                    <td>--</td>
                                @else
                                    <td>{{ $session->URL }}</td>
                                @endif--}}
                                <td>{{ $session->previous_url }}</td>
                                {{--<td>{{ $session->created_at }}</td>--}}
                                @if( $session->created_at == null)
                                    <td>--</td>
                                @else
                                    <td>{{ $session->created_at }}</td>
                                @endif
                                {{--<td>{{ $ministry->created_at->format('d/m/Y') }}</td>--}}
                                <td>
                                    {{--@can('ministry_update')
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2 text-primary" title="Edit Detail" href="{{ route('ministry.updateministry', [$ministry['id']]) }}"><i data-feather="edit"></i></a>
                                    @endcan--}}
                                    @can('session_delete')
                                        <form class="btn btn-datatable btn-icon me-2 text-blue" action="{{ route('sessions.sessionsdelete', [$session['id']]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this session?')">
                                            @csrf
                                            @method('DELETE')
                                            {{--<button type="submit" class="btn btn-datatable btn-icon text-danger btn-transparent" title="Delete Data"><i data-feather="trash-2"></i></button>--}}
                                            @if( $session->session_id !== $users->session_id)
                                                <button type="submit" class="btn btn-datatable btn-icon text-danger btn-transparent" title="Delete Data"><i data-feather="trash-2"></i></button>
                                            @endif
                                        </form>
                                    @endcan
                                    {{--<form class="btn btn-datatable btn-icon me-2 text-blue" action="{{ route('sessions.sessionsdelete', [$session['id']]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this session?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-datatable btn-icon text-danger btn-transparent" title="Delete Data"><i data-feather="trash-2"></i></button>
                                    </form>--}}
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
                            columns:  ':not(:last-child)',
                            format: {
                                body: function (data, row, column, node) {
                                    var textContent = $('<div>').html(data).text(); // Use jQuery to strip HTML tags
                                    // Handle date formatting if necessary
                                    if (/\d{2}\/\d{2}\/\d{4}/.test(textContent)) {
                                        var dateParts = textContent.split('/');
                                        textContent = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]).toLocaleDateString();
                                    }
                                    return column === 0 ? row + 1 : textContent;
                                }
                            }
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        exportOptions: {
                            columns:  ':not(:last-child)',
                            format: {
                                body: function (data, row, column, node) {
                                    var textContent = $('<div>').html(data).text(); // Use jQuery to strip HTML tags
                                    // Handle date formatting if necessary
                                    if (/\d{2}\/\d{2}\/\d{4}/.test(textContent)) {
                                        var dateParts = textContent.split('/');
                                        textContent = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]).toLocaleDateString();
                                    }
                                    return column === 0 ? row + 1 : textContent;
                                }
                            }
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns:  ':not(:last-child)',
                            format: {
                                body: function (data, row, column, node) {
                                    var textContent = $('<div>').html(data).text(); // Use jQuery to strip HTML tags
                                    // Handle date formatting if necessary
                                    if (/\d{2}\/\d{2}\/\d{4}/.test(textContent)) {
                                        var dateParts = textContent.split('/');
                                        textContent = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]).toLocaleDateString();
                                    }
                                    return column === 0 ? row + 1 : textContent;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        exportOptions: {
                            columns:  ':not(:last-child)',
                            format: {
                                body: function (data, row, column, node) {
                                    var textContent = $('<div>').html(data).text(); // Use jQuery to strip HTML tags
                                    // Handle date formatting if necessary
                                    if (/\d{2}\/\d{2}\/\d{4}/.test(textContent)) {
                                        var dateParts = textContent.split('/');
                                        textContent = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]).toLocaleDateString();
                                    }
                                    return column === 0 ? row + 1 : textContent;
                                }
                            }
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            columns:  ':not(:last-child)',
                            format: {
                                body: function (data, row, column, node) {
                                    var textContent = $('<div>').html(data).text(); // Use jQuery to strip HTML tags
                                    // Handle date formatting if necessary
                                    if (/\d{2}\/\d{2}\/\d{4}/.test(textContent)) {
                                        var dateParts = textContent.split('/');
                                        textContent = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]).toLocaleDateString();
                                    }
                                    return column === 0 ? row + 1 : textContent;
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

        function refreshBtn() {
            location.reload();
        }

    </script>
@endpush
