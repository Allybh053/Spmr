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
                            Pending Mail Relay List
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">

                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main page content-->
    <div class="container-fluid px-4">
        @if(session('success'))
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">Message</h5>
                {{ session('success') }}
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <table id="datatablesSimple1" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Serial No</th>
                            <th>Ticket No</th>
                            <th>Mail Relay Server</th>
                            <th>Type Application</th>
                            <th>Requestor</th>
                            <th>Status</th>
                            <th>Date Request</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($smtpMailServers as $smtpMailServer)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $smtpMailServer -> form_id }}</td>
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
                            @if($smtpMailServer->status == 0)
                                <span class="badge bg-yellow text-white rounded-pill">Pending</span>
                            @else
                                <span class="badge bg-red text-white rounded-pill">Inactive</span>
                            @endif
                            </td>
                            <td> {{ \Carbon\Carbon::parse($smtpMailServer->created_at)->format('d-m-Y') }}</td>
                            <td style="text-align: center;">
                                <a class="btn btn-datatable btn-icon me-2 text-blue" title="View Detail" href="{{ route('mailrelay.edit',['id' => $smtpMailServer->id,'type' => $smtpMailServer->types_req])}}"><i data-feather="eye"></i></a>
                                <form class="btn btn-datatable btn-icon me-2 text-blue" action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-datatable btn-icon text-danger btn-transparent" title="Delete User" hidden><i data-feather="trash-2"></i></button>
                                </form>
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
        ]
    });

    // Adjust positioning of search input and export buttons
    $('.dataTables_filter').addClass('d-inline-block mr-2'); // Make search input inline
    $('.dt-buttons').appendTo('.dataTables_filter'); // Append export buttons to search container
})(jQuery);

    </script>
    @endpush
