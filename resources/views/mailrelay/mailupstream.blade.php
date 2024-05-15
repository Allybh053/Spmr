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
                            Mail Upstream (Domain)
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
                <form  id="searchForm" class="ajax-form-search" novalidate action="{{ route('mailrelay.mailupstream') }}" method="GET">
                    <!-- Form Row-->
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="serverconfig">Server Name:</label>
                            <select id="serverconfig" name="serverconfig" class="form-control select2">
                                <option value="">Select an option</option>
                                @foreach ($serverconfig as $server)
                                    <option value="{{ $server->id }}">{{ $server->hostname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div style="padding: 25px; text-align: left;">
                                <button class="btn btn-success" id="searching">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><hr>
        <div class="card">
            <div class="card-body">
                <table id="datatablesSimple1" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Domain Name</th>
                            <th>SMTP</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($serverdetail)
                            @foreach ($serverdetail as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->smtp }}</td>
                                <td>
                                    <button type="submit" class="btn btn-datatable btn-icon text-danger btn-transparent" title="Delete User"><i data-feather="trash-2"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">No data available</td>
                            </tr>
                        @endisset

                    </tbody>
                </table>
            </div>
            <hr>
            <div class="card-body">
                <label class="small mb-1" for="serverconfig">Terminal</label>
                <div id="shell-in-a-box"></div>
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

    $(document).ready(function() {
        $('#searchForm').submit(function (e) {
            e.preventDefault();

            sendTestCmd();

        });

        function sendTestCmd() {

            const serverAddress = 'wss://postmaster1a-dev.mygovuc.gov.my/wss/';
            const socket = new WebSocket(serverAddress);

            socket.addEventListener('open', (event) => {
                const command = 'cat /opt/cluster/postfix/sender_relay_maps';
                socket.send(command);
            });

            socket.addEventListener('message', (event) => {
                const response = event.data;
                if (response.includes('Failed')) {
                    console.log(response);
                } else {
                    console.log(response);
                }
                socket.close();
                // location.reload();
            });
        }

        // Make AJAX request to fetch Shell In A Box session
        fetch('{{ route('shell-in-a-box') }}')
        .then(response => response.text())
        .then(data => {
            // console.log(data);
            // Update the content on your page
            document.getElementById('shell-in-a-box').innerHTML = data;
        })
        .catch(error => {
            console.error('Error fetching Shell In A Box session:', error);
        });

    });

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
