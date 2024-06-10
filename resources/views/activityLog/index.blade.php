@extends('layouts.app')
@push('css')
<style>
    .scrollable-column {
        max-width: 300px; /* Adjust the maximum width as needed */
        overflow-x: auto;
        white-space: pre-line;
    }
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
                                        <li class="breadcrumb-item">Recent Activity Log</li>
                                    </ol>
                                </nav>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="container-fluid px-4 mt-4">
            <div class="row">
                 {{-- <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">Filter</div>
                        <div class="card-body">
                            <form id="searchForm">

                                <div  id="searchBox">--}}

                                    {{-- not in use for choose type --}}
                                    {{-- <div class="row gx-3 mb-3">
                                        <div class="col-md-6">
                                            <div class="mb-3" id="calendarSelect">
                                                <label class="small mb-1" for="calendarType">Filter Type:</label>
                                                <select class="form-control select2" id="calendarType" name="calendarType">
                                                    <option selected disabled></option>
                                                    <option value="range">Date Range</option>
                                                    <option value="month">Month</option>
                                                    <option value="year">Year</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> --}}
                                     {{-- <div class="mb-3" id="calendarInputs">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="start_date">Start Date:</label>
                                                <input type="date" class="form-control" name="start_date" id="start_date">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="end_date">End Date:</label>
                                                <input type="date" class="form-control" name="end_date" id="end_date">
                                            </div>
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
                </div>--}}
                <br>

                <div class="col-xl-12">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">Activity Log</div>


                        <div class="card-body mt-1 mb-4">
                        <div class="table-responsive">
                        <table id="spmrDatatable" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th style="width: 10%;">User</th>
                                    <th style="width: 10%;">Activity Type</th>
                                    <th>Action</th>
                                    <th style="width: 10%;">Ip Address</th>
                                    <th style="width: 15%;">User Agent</th>
                                    <th style="width: 10%;">Modul</th>
                                    <th style="width: 15%;">Old Data</th>
                                    <th style="width: 15%;">New Data</th>
                                    <th>Activity</th>
                                    <th style="width: 10%;">Created At</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logsPaginated->items() as $log)
                                    <tr>
                                        <td>{{ $log->user ? $log->user->username : 'Guest' }}</td>
                                        <td>{{ $log->activity_type ?? $log->event }}</td>
                                        <td>{{ $log->action }}</td>
                                        <td>{{ $log->ip_address }}</td>
                                        <td style="white-space: pre-line;">{{ $log->user_agent }}</td>
                                        <td>{{ $log->url }}</td>
                                        <td class="scrollable-column">
                                            {{ $log->old_values }}
                                        </td>
                                        <td class="scrollable-column">
                                            {{ $log->new_values }}
                                        </td>
                                        <td>
                                            <div style="height: 165px; overflow:auto;">
                                                {{ $log->input }}
                                            </div>
                                        </td>
                                        <td><pre>{{ $log->created_at_display }}</pre></td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            {{-- {{ $logsPaginated->links() }} --}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
@endsection
@push('script')
<script>
    $('#calendarType').change(function () {
            var selectedType = $(this).val();
            $('#calendarInputs').empty(); // Clear previous inputs

            if (selectedType === 'month') {
                var currentYear = new Date().getFullYear();
                var selectMonthHtml = '<div class="col-md-6"><label for="input2_month">Month:</label><select class="form-control" name="input2_month" id="month">';
                var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                for (var month = 0; month < 12; month++) {
                    selectMonthHtml += '<option value="' + (month + 1) + '">' + monthNames[month] + ' ' + currentYear + '</option>';
                }
                selectMonthHtml += '</select></div>';
                $('#calendarInputs').append(selectMonthHtml);
            } else if (selectedType === 'year') {
                var currentYear = new Date().getFullYear();
                var selectYearHtml = '<div class="col-md-6"><label for="input2_year">Year:</label><select class="form-control" name="input2_year" id="year">';
                for (var year = currentYear; year >= currentYear - 10; year--) {
                    selectYearHtml += '<option value="' + year + '">' + year + '</option>';
                }
                selectYearHtml += '</select></div>';
                $('#calendarInputs').append(selectYearHtml);
            }   else if (selectedType === 'range') {
                var selectRangeHtml = '<div class="row"><div class="col-md-6"><label for="start_date">Start Date:</label><input type="date" class="form-control" name="start_date" id="start_date"></div>';
                selectRangeHtml += '<div class="col-md-6"><label for="end_date">End Date:</label><input type="date" class="form-control" name="end_date" id="end_date"></div></div>';
                $('#calendarInputs').append(selectRangeHtml);
            } else {
                // to do
            }
        });

    jQuery.extend(jQuery.fn.dataTable.ext.type.order, {
        "date-uk-pre": function(a) {
            if (!a) {
                return 0;
            }
            var ukDateTime = a.split(' ');
            var ukDate = ukDateTime[0].split('/');
            var ukTime = ukDateTime[1] + ' ' + ukDateTime[2];
            return new Date(ukDate[2], ukDate[1] - 1, ukDate[0], new Date('1970-01-01T' + ukTime).getHours(), new Date('1970-01-01T' + ukTime).getMinutes(), new Date('1970-01-01T' + ukTime).getSeconds());
        },
        "date-uk-asc": function(a, b) {
            return a - b;
        },
        "date-uk-desc": function(a, b) {
            return b - a;
        }
    });

    // Initialize DataTable with custom sorting for date column
    var table = $('#spmrDatatable').DataTable({
        processing: true,
        serverSide: false, // Change this to true for server-side processing
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
            { type: 'date-uk', targets: [9] },
            {
                targets: [6, 7], // Assuming columns containing JSON data are the 6th and 7th columns
                render: function(data, type, row, meta) {
                    if (!data) {
                        return ''; // Return empty string if data is null
                    }

                    try {
                        var json;
                        if (data.startsWith('"') && data.endsWith('"')) {
                            // Unescape the string if wrapped in double quotes
                            var unescapedData = data.slice(1, -1).replace(/\\"/g, '"');
                            json = JSON.parse(unescapedData);
                        } else {
                            // Parse the JSON string directly
                            json = JSON.parse(data);
                        }

                        // Format JSON object with line breaks
                        var formattedJson = JSON.stringify(json, null, 2);
                        // Replace newlines with <br> tags for HTML rendering
                        return '<pre>' + formattedJson + '</pre>';
                    } catch (error) {
                        console.error("Error parsing JSON:", error);
                        return "Invalid JSON";
                    }
                }
            }
        ],
        order: [[9, 'asc']],
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

    // Handle form submission
    $('#searchForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        type: 'POST',
        url: '{{ route("activityLog.filter") }}',
        data: formData,
        success: function(response) {
            console.log(response);
             // Clear existing data from the DataTable
             if (typeof response.data === 'object' && response.data !== null) {
        // Convert response.data object into an array
        var dataArray = Object.values(response.data);

        // Clear existing data from the DataTable
        table.clear().draw();

        // Populate DataTable with new data
        dataArray.forEach(function(item) {
            table.row.add([
                item.user_id, // Assuming these are the respective fields, adjust as needed
                item.activity_type,
                item.action,
                item.ip_address,
                item.user_agent,
                item.url,
                item.old_values,
                item.new_values,
                item.event,
                item.created_at // Assuming this is the timestamp field
            ]).draw(false);
        });
    } else {
        console.error('Response data is not in expected format:', response.data);
    }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});

    </script>

@endpush

