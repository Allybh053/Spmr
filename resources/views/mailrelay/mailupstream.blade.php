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
                            Mail Upstream
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
                <form  id="searchForm"  class="ajax-form-search" novalidate action="{{ route('mailrelay.mailupstream') }}" method="GET">
                    <!-- Form Row-->
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="serverconfig">Server Name:</label>
                            <select id="serverconfig" name="serverconfig" class="form-control select2">
                                <option value="">Select an option</option>
                                <!-- Loop through $serverconfig array to generate options -->
                                @foreach ($serverconfig as $server)
                                    <option value="{{ $server->id }}">{{ $server->hostname }}</option>
                                @endforeach
                            </select>
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
    // $(document).ready(function() {
    //     $('#searching').click(function(e) {
    //         e.preventDefault();
    //         var formData = $('#searchForm').serialize();

    //         $.ajax({
    //             type: 'POST',
    //             url: $('#searchForm').attr('action'),
    //             data: formData,
    //             success: function(response) {
    //                 // $('#datatablesSimple1').empty();
    //                 // if (response.success) {
    //                 //     // Loop through the data and generate table rows
    //                 //     $.each(response.data, function(index, detail) {
    //                 //         var row = '<tr>' +
    //                 //                     '<td><p>' + (index + 1) + '.</p></td>' +
    //                 //                     '<td><p>' + detail.name + '</p></td>' +
    //                 //                     '<td><p>' + detail.smtp + '</p></td>' +
    //                 //                 '</tr>';
    //                 //         $('#datatablesSimple1').append(row);
    //                 //     });
    //                 // } else {
    //                 //     // Handle error or display a message
    //                 //     console.error('Error retrieving data');
    //                 // }
    //                 },
    //                 error: function(xhr, status, error) {
    //                     console.error(xhr.responseText);
    //                 },
    //             error: function(xhr, status, error) {
    //                 // Handle errors
    //                 console.error(xhr.responseText);
    //             }
    //             });

    //     });
    //     });

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
