@extends('layouts.app')
@section('content')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="mail"></i></div>
                            Mail Relay List
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="{{route('mailrelay.register')}}">
                            <i class="me-1" data-feather="mail"></i>
                            Register New Mail Relay
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main page content-->
    <div class="container-fluid px-4">
        @if(isset($_GET['success']))
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">Message</h5>
                {{ $_GET['success'] }}
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- @if(session('success'))
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">Message</h5>
                {{ session('success') }}
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif --}}
        <div class="card">
            <div class="card-body">
                <table id="datatablesSimple1" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Ticket No</th>
                            <th>Mail Relay Server</th>
                            <th>Type Application</th>
                            <th>Requestor</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($smtpMailServers as $smtpMailServer)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $smtpMailServer -> ticket_id }}</td>
                            <td>{{ $smtpMailServer -> smtp_relay_server }}</td>
                            <td>@if($smtpMailServer -> types_req == 1)
                                <span class="badge bg-success text-white rounded-pill">New Application</span>
                            @elseif($smtpMailServer -> types_req == 2)
                                <span class="badge bg-danger text-white rounded-pill">Terminate of Service</span>
                            @else
                                <span class="badge bg-primary text-white rounded-pill">Onboarding Agency</span>
                            @endif</td>
                            <td>{{ $smtpMailServer -> applicant_name }}</td>
                            <td>
                                @if($user->status == 0 )
                                    <span class="badge bg-success text-white rounded-pill">Pending</span>
                                @else
                                    <span class="badge bg-red text-white rounded-pill">Pending</span>
                                @endif
                            </td>
                            <td> {{ \Carbon\Carbon::parse($smtpMailServer->created_at)->format('d-m-Y') }}</td>
                            <td>
                            @can('manage_mail_relay_view')
                                <a class="btn btn-datatable btn-icon me-2 text-blue" title="View Detail" href="{{ route('smtp_relay.view', ['id' => $smtpMailServer->id]) }}"><i data-feather="eye"></i></a>
                            @endcan
                            @can('manage_mail_relay_edit')
                                <a hidden class="btn btn-datatable btn-icon me-2 text-blue" title="Edit Detail" href="{{ route('smtp_relay.edit',['id' => $smtpMailServer->id]) }}"><i data-feather="edit"></i></a>
                            @endcan
                            @can('manage_mail_relay_delete')
                                <form class="btn btn-datatable btn-icon me-2 text-blue" action="#" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-datatable btn-icon text-danger btn-transparent" title="Delete User"><i data-feather="trash-2"></i></button>
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
    <style>
        th,td {
            font-size: var(--bs-body-font-size);
        }
    </style>
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
