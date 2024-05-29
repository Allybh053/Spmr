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
                                        <li class="breadcrumb-item">Ussage Report</li>
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
                                {!! Form::select('server', $serverconfig,  $defaultServerId ?? 1,  ['id'=>'server', 'name'=>'server', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                <p class="card-text mt-1 mb-1" id="ipAddress"></p>
                                <div class="small text-muted"></div>
                            </div>
                        </div>

                        <div  id="searchBox">
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3" id="calendarSelect">
                                        <label class="small mb-1" for="calendarType">Filter Type:</label>
                                        <select class="form-control select2" id="calendarType" name="calendarType">
                                            <option selected disabled></option>
                                            {{-- <option value="range">Date Range</option> --}}
                                            <option value="month">Month</option>
                                            <option value="year">Year</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3" id="calendarInputs">
                                <!-- Inputs for date, month, year, and date range will be dynamically appended here -->
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
        <div class="row mb-4 mt-4">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Table List Jadual2 Start --}}
                        <br>
                        <div class="card">
                            <div class="card-header">Table of Total Daily Email</div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="listJad2"  class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Date</th>
                                            <th>Received</th>
                                            <th>Delivered</th>
                                            <th>Deferred</th>
                                            <th>Bounced</th>
                                            <th>Rejected</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-5">
                            <div class="card-header text-center p-5">
                                <h5 id="totalDailyEmel">Total Daily Email</h5>
                                <button id="downloadButtonJadual2" class="btn btn-success download-button">
                                    Download Chart (Total Daily Email) &nbsp;&nbsp; <i class="fas fa-download"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <canvas id="dailyEmel"></canvas>
                            </div>
                        </div>

                        {{-- Table List Jadual6 Start --}}
                        <br>
                        <div class="card">
                            <div class="card-header">Table of Per-Hour Traffic Summary</div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="listJad6"  class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Time</th>
                                            <th>Received</th>
                                            <th>Delivered</th>
                                            <th>Deferred</th>
                                            <th>Bounced</th>
                                            <th>Rejected</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-5">
                            <div class="card-header text-center p-5">
                                <h5 id="trafficSummaryHour">Chart of Per-Hour Traffic Summary</h5>
                                <button id="downloadButtonJadual6" class="btn btn-success download-button">
                                    Download Chart (Traffic Summary Per-Hour) &nbsp;&nbsp; <i class="fas fa-download"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <canvas id="trafficSummary"></canvas>
                            </div>
                        </div>

                        {{-- Table List Jadual10 Start --}}
                        <br>
                        <div class="card">
                            <div class="card-header">Table of Top 20 Email Recipients</div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="listJad10"  class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Email</th>
                                            <th>Total Email</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-5">
                            <div class="card-header text-center p-5">
                                <h5 id="toprecipient">Chart of Top 20 Email Recipients</h5>
                                <button id="downloadButtonJadual10" class="btn btn-success download-button">
                                    Download Chart (Top 20 Email Recipients) &nbsp;&nbsp; <i class="fas fa-download"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <canvas id="topEmel"></canvas>
                            </div>
                        </div>

                        {{-- Table List Jadual14 Start --}}
                        <br>
                        <div class="card">
                            <div class="card-header">Table of Top 20 Email Senders</div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="listJad14"  class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Email</th>
                                            <th>Total Email</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-5">
                            <div class="card-header text-center p-5">
                                <h5 id="topsenders">Chart of Top 20 Email Senders</h5>
                                <button id="downloadButtonJadual14" class="btn btn-success download-button">
                                    Download Chart (Top 20 Email Senders) &nbsp;&nbsp; <i class="fas fa-download"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <canvas id="topEmel14"></canvas>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





@endsection
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>

    $(document).ready(function () {
        $('#downloadButtonJadual2').fadeOut();
        $('#downloadButtonJadual6').fadeOut();
        $('#downloadButtonJadual10').fadeOut();
        $('#downloadButtonJadual14').fadeOut();

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

        var tableJad2 = $('#listJad2').DataTable({
            ordering: true,
            columns: [
                {
                    data: null,
                    name: 'DT_RowIndex2',
                    orderable: false,
                    searchable: false,
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // This will render the index starting from 1
                    }
                },
                { data: 'date', name: 'date', class: 'text-center' },
                { data: 'received', name: 'received', class: 'text-center',
                render: function (data, type, row) {
                    return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                } },
                { data: 'delivered', name: 'delivered', class: 'text-center',
                render: function (data, type, row) {
                    return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                } },
                { data: 'deferred', name: 'deferred', class: 'text-center',
                render: function (data, type, row) {
                    return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                } },
                { data: 'bounced', name: 'bounced', class: 'text-center',
                render: function (data, type, row) {
                    return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                } },
                { data: 'rejected', name: 'rejected', class: 'text-center',
                render: function (data, type, row) {
                    return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                } },
            ],
            dom: '<"top"fBl>rt<"bottom"ip>',
            buttons: [
                {
                    extend: 'copy',
                    text: 'Copy',
                    title: 'Table of Total Daily Email',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    title: 'Table of Total Daily Email',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    title: 'Table of Total Daily Email',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: 'Table of Total Daily Email',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'Table of Total Daily Email',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            data: [], // Initialize with empty data
            drawCallback: function(settings) {
                var api = this.api();
                api.column(0, {search:'applied', order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }
        });
        $('.dataTables_filter', tableJad2.table().container()).addClass('d-inline-block mr-2').append($('.dt-buttons', tableJad2.table().container()));


        var tableJad6 = $('#listJad6').DataTable({
            ordering: true,
            columns: [
                {
                    data: null,
                    name: 'DT_RowIndex2',
                    orderable: false,
                    searchable: false,
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // This will render the index starting from 1
                    }
                },
                { data: 'time', name: 'time', class: 'text-center' },
                { data: 'received', name: 'received', class: 'text-center' },
                { data: 'delivered', name: 'delivered', class: 'text-center' },
                { data: 'deferred', name: 'deferred', class: 'text-center' },
                { data: 'bounced', name: 'bounced', class: 'text-center' },
                { data: 'rejected', name: 'rejected', class: 'text-center' },
            ],
            dom: '<"top"fBl>rt<"bottom"ip>',
            buttons: [
                {
                    extend: 'copy',
                    text: 'Copy',
                    title: 'Table of Per-Hour Traffic Summary',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    title: 'Table of Per-Hour Traffic Summary',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    title: 'Table of Per-Hour Traffic Summary',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: 'Table of Per-Hour Traffic Summary',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'Table of Per-Hour Traffic Summary',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            data: [], // Initialize with empty data
            drawCallback: function(settings) {
                var api = this.api();
                api.column(0, {search:'applied', order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }
        });
        $('.dataTables_filter', tableJad6.table().container()).addClass('d-inline-block mr-2').append($('.dt-buttons', tableJad6.table().container()));

        var tableJad10 = $('#listJad10').DataTable({
            ordering: true,
            columns: [
                {
                    data: null,
                    name: 'DT_RowIndex2',
                    orderable: false,
                    searchable: false,
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // This will render the index starting from 1
                    }
                },
                { data: 'email', name: 'email', class: 'text-center' },
                { data: 'total_email', name: 'total_email', class: 'text-center' },
            ],
            dom: '<"top"fBl>rt<"bottom"ip>',
            buttons: [
                {
                    extend: 'copy',
                    text: 'Copy',
                    title: 'Table of Top 20 Email Recipients',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    title: 'Table of Top 20 Email Recipients',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    title: 'Table of Top 20 Email Recipients',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: 'Table of Top 20 Email Recipients',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'Table of Top 20 Email Recipients',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            data: [], // Initialize with empty data
            drawCallback: function(settings) {
                var api = this.api();
                api.column(0, {search:'applied', order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }
        });
        $('.dataTables_filter', tableJad10.table().container()).addClass('d-inline-block mr-2').append($('.dt-buttons', tableJad10.table().container()));

        var tableJad14 = $('#listJad14').DataTable({
            ordering: true,
            columns: [
                {
                    data: null,
                    name: 'DT_RowIndex2',
                    orderable: false,
                    searchable: false,
                    class: 'text-center',
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // This will render the index starting from 1
                    }
                },
                { data: 'email', name: 'email', class: 'text-center' },
                { data: 'total_email', name: 'total_email', class: 'text-center' },
            ],
            dom: '<"top"fBl>rt<"bottom"ip>',
            buttons: [
                {
                    extend: 'copy',
                    text: 'Copy',
                    title: 'Table of Top 20 Email Senders',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    title: 'Table of Top 20 Email Senders',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    title: 'Table of Top 20 Email Senders',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: 'Table of Top 20 Email Senders',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'Table of Top 20 Email Senders',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            data: [], // Initialize with empty data
            drawCallback: function(settings) {
                var api = this.api();
                api.column(0, {search:'applied', order:'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }
        });
        $('.dataTables_filter', tableJad14.table().container()).addClass('d-inline-block mr-2').append($('.dt-buttons', tableJad14.table().container()));


        $('#searchForm').submit(function (e) {
            e.preventDefault();

          var formData = $(this).serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content');

            if (formData === 'server=') {
                alert("Please Choose Server in options field.");
            } else {
                let timerInterval;

                Swal.fire({
                    title: "Please wait...",
                    //html: "Please wait...",
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        const timer = Swal.getPopup().querySelector("b");
                        timerInterval = setInterval(() => {
                            if (timer) {
                                timer.textContent = `${Swal.getTimerLeft()}`;
                            }
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: "{{ route('monthlyReport.index.search') }}",
                    data: formData,
                    success: function (response) {
                        // Hide the SweetAlert timer
                        Swal.close();

                        // Add index to each row for jadual2
                        $.each(response.dataTbl2, function (index, row) {
                            row['DT_RowIndex'] = index + 1;
                        });
                        // Clear existing data and add new data to the DataTable
                        tableJad2.clear().rows.add(response.dataTbl2).draw();

                        const serverName = response.serverName; // Assuming you are sending serverName from the backend
                        document.getElementById('totalDailyEmel').innerHTML = `Total Daily Email - ${serverName}`;
                        document.getElementById('trafficSummaryHour').innerHTML = `Chart of Per-Hour Traffic Summary - ${serverName}`;
                        document.getElementById('toprecipient').innerHTML = `Chart of Top 20 Email Recipients - ${serverName}`;
                        document.getElementById('topsenders').innerHTML = `Chart of Top 20 Email Senders - ${serverName}`;

                        // linechartJadual2 start
                        if (response.chartJadual2) {
                            const responseData = response.chartJadual2;
                            $('#downloadButtonJadual2').fadeIn();

                            // Extract the dates and values for each category
                            const dates = responseData.map(item => item.date);
                            const receivedData = responseData.map(item => item.received);
                            const deliveredData = responseData.map(item => item.delivered);
                            const deferredData = responseData.map(item => item.deferred);
                            const bouncedData = responseData.map(item => item.bounced);
                            const rejectedData = responseData.map(item => item.rejected);

                            // Create the chart
                            const ctx = document.getElementById('dailyEmel').getContext('2d');
                            const lineChart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: dates,
                                    datasets: [
                                        {
                                            label: 'Received',
                                            data: receivedData,
                                            borderColor: 'rgba(75, 192, 192, 1)',
                                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                            fill: false
                                        },
                                        {
                                            label: 'Delivered',
                                            data: deliveredData,
                                            borderColor: 'rgba(54, 162, 235, 1)',
                                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                            fill: false
                                        },
                                        {
                                            label: 'Deferred',
                                            data: deferredData,
                                            borderColor: 'rgba(255, 206, 86, 1)',
                                            backgroundColor: 'rgba(255, 206, 86, 0.2)',
                                            fill: false
                                        },
                                        {
                                            label: 'Bounced',
                                            data: bouncedData,
                                            borderColor: 'rgba(255, 99, 132, 1)',
                                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                            fill: false
                                        },
                                        {
                                            label: 'Rejected',
                                            data: rejectedData,
                                            borderColor: 'rgba(153, 102, 255, 1)',
                                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                            fill: false
                                        }
                                    ]
                                },
                                options: {
                                    scales: {
                                        x: {
                                            type: 'time',
                                            time: {
                                                unit: 'day',
                                                tooltipFormat: 'MMM D, YYYY'
                                            },
                                            title: {
                                                display: true,
                                                text: 'Date'
                                            }
                                        },
                                        y: {
                                            title: {
                                                display: true,
                                                text: 'Count'
                                            },
                                            beginAtZero: true
                                        }
                                    },
                                    plugins: {
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    return context.dataset.label + ': ' + context.raw;
                                                }
                                            }
                                        }
                                    }
                                }
                            });

                            function downloadChartJadual2() {
                            const canvas = document.getElementById('dailyEmel');
                            const context = canvas.getContext('2d');
                            const currentCanvasContent = context.getImageData(0, 0, canvas.width, canvas.height);
                            context.globalCompositeOperation = 'destination-over';
                            context.fillStyle = 'white';
                            context.fillRect(0, 0, canvas.width, canvas.height);
                            const url = canvas.toDataURL('image/jpeg', 1.0);
                            context.putImageData(currentCanvasContent, 0, 0);

                            const link = document.createElement('a');
                            link.download = 'Total Daily Emel-'+serverName+'.jpg';
                            link.href = url;
                            link.click();
                            }

                            // Attach event listener for jadual6 download button
                            document.getElementById('downloadButtonJadual2').addEventListener('click', downloadChartJadual2);

                            }
                        // linechartJadual2 end


                        // Add index to each row for jadual6
                        $.each(response.dataTbl6, function (index, row) {
                            row['DT_RowIndex'] = index + 1;
                        });
                        // Clear existing data and add new data to the DataTable
                        tableJad6.clear().rows.add(response.dataTbl6).draw();

                        // lineChartJadual6 start
                        if (response.chartJadual6) {
                            const data = response.chartJadual6.map(item => ({
                                    time: item.time,
                                    received: item.received,
                                    delivered: item.delivered,
                                    deferred: item.deferred,
                                    bounced: item.bounced,
                                    rejected: item.rejected
                                }));


                            $('#downloadButtonJadual6').fadeIn();

                                // time format H:m:s
                                // const labels = data.map(d => {
                                //     const date = new Date(`1970-01-01T${d.time.split('+')[0]}`);
                                //     return date.toTimeString().slice(0, 8); // H:M:S format
                                // });
                                const labels = data.map(d => d.time);
                                // time format H:m-H:m
                                // const labels = data.map((d, index) => {
                                    // const startDate = new Date(`1970-01-01T${d.time.split('+')[0]}`);
                                    // const endDate = new Date(`1970-01-01T${data[index + 1] ? data[index + 1].time.split('+')[0] : d.time.split('+')[0]}`);
                                    // const startTime = startDate.getHours() + ':' + ('0' + startDate.getMinutes()).slice(-2);
                                    // const endTime = endDate.getHours() + ':' + ('0' + endDate.getMinutes()).slice(-2);
                                    // return `${startTime}-${endTime}`;
                                // }).slice(0, -1); // Remove last interval, assuming it's incomplete or unwanted

                                const receivedData = data.map(d => d.received);
                                const deliveredData = data.map(d => d.delivered);
                                const deferredData = data.map(d => d.deferred);
                                const bouncedData = data.map(d => d.bounced);
                                const rejectedData = data.map(d => d.rejected);

                                const ctx = document.getElementById('trafficSummary').getContext('2d');
                                new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: labels,
                                        datasets: [
                                            {
                                                label: 'Received',
                                                data: receivedData,
                                                borderColor: 'rgba(0, 123, 255, 1)', // Bold Blue
                                                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                                                borderWidth: 3, // Make the line thicker
                                                fill: false
                                            },
                                            {
                                                label: 'Delivered',
                                                data: deliveredData,
                                                borderColor: 'rgba(40, 167, 69, 1)', // Bold Green
                                                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                                                borderWidth: 3, // Make the line thicker
                                                fill: false
                                            },
                                            {
                                                label: 'Deferred',
                                                data: deferredData,
                                                borderColor: 'rgba(255, 193, 7, 1)', // Bold Yellow
                                                backgroundColor: 'rgba(255, 193, 7, 0.2)',
                                                borderWidth: 3, // Make the line thicker
                                                fill: false
                                            },
                                            {
                                                label: 'Bounced',
                                                data: bouncedData,
                                                borderColor: 'rgba(220, 53, 69, 1)', // Bold Red
                                                backgroundColor: 'rgba(220, 53, 69, 0.2)',
                                                borderWidth: 3, // Make the line thicker
                                                fill: false
                                            },
                                            {
                                                label: 'Rejected',
                                                data: rejectedData,
                                                borderColor: 'rgba(108, 117, 125, 1)', // Bold Gray
                                                backgroundColor: 'rgba(108, 117, 125, 0.2)',
                                                borderWidth: 3, // Make the line thicker
                                                fill: false
                                            }
                                        ]
                                    },
                                    options: {
                                        responsive: true,
                                        scales: {
                                            x: {
                                                type: 'category',
                                                title: {
                                                    display: true,
                                                    text: 'Time'
                                                }
                                            },
                                            y: {
                                                title: {
                                                    display: true,
                                                    text: 'Count'
                                                }
                                            }
                                        }
                                    }
                                });

                                function downloadChartJadual6() {
                                    const canvas = document.getElementById('trafficSummary');
                                    const context = canvas.getContext('2d');
                                    const currentCanvasContent = context.getImageData(0, 0, canvas.width, canvas.height);
                                    context.globalCompositeOperation = 'destination-over';
                                    context.fillStyle = 'white';
                                    context.fillRect(0, 0, canvas.width, canvas.height);
                                    const url = canvas.toDataURL('image/jpeg', 1.0);
                                    context.putImageData(currentCanvasContent, 0, 0);
                                    const link = document.createElement('a');
                                    link.download = 'Chart of Per-Hour Traffic Summary-'+serverName+'.jpg';
                                    link.href = url;
                                    link.click();
                                }

                                    // Attach event listener for jadual6 download button
                                    document.getElementById('downloadButtonJadual6').addEventListener('click', downloadChartJadual6);

                        }
                        // lineChartJadual6 end


                        // Add index to each row for jadual10
                        $.each(response.dataTbl10, function (index, row) {
                            row['DT_RowIndex'] = index + 1;
                        });
                        // Clear existing data and add new data to the DataTable
                        tableJad10.clear().rows.add(response.dataTbl10).draw();

                        // barChartJadual10 start
                        if (response.chartJadual10) {
                            const ctx = document.getElementById('topEmel').getContext('2d');
                             $('#downloadButtonJadual10').fadeIn();
                            const responseData10 = response.chartJadual10;

                            // Extracting emails and total_emails for the chart
                            const labels = responseData10.map(item => item.email);
                            const totals = responseData10.map(item => item.total_email);

                            const myBarTopEmelChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Total Emails',
                                        data: totals,
                                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                                        borderColor: 'rgba(54, 162, 235, 0.8)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });

                            function downloadChartJadual10() {
                                    const canvas = document.getElementById('topEmel');
                                    const context = canvas.getContext('2d');
                                    const currentCanvasContent = context.getImageData(0, 0, canvas.width, canvas.height);
                                    context.globalCompositeOperation = 'destination-over';
                                    context.fillStyle = 'white';
                                    context.fillRect(0, 0, canvas.width, canvas.height);
                                    const url = canvas.toDataURL('image/jpeg', 1.0);
                                    context.putImageData(currentCanvasContent, 0, 0);
                                    const link = document.createElement('a');
                                    link.download = 'Chart of Top 20 Email Recipients-'+serverName+'.jpg';
                                    link.href = url;
                                    link.click();
                                    }

                                    // Attach event listener for jadual6 download button
                                    document.getElementById('downloadButtonJadual10').addEventListener('click', downloadChartJadual10);

                        }
                        // barChartJadual10 end

                        // Add index to each row for jadual14
                        $.each(response.dataTbl14, function (index, row) {
                            row['DT_RowIndex'] = index + 1;
                        });
                        // Clear existing data and add new data to the DataTable
                        tableJad14.clear().rows.add(response.dataTbl14).draw();

                         // barChartJadual14 start
                         if (response.chartJadual14) {
                            const ctxtop = document.getElementById('topEmel14').getContext('2d');
                            $('#downloadButtonJadual14').fadeIn();

                            const responseData10 = response.chartJadual14;

                            // Extracting emails and total_emails for the chart
                            const labels = responseData10.map(item => item.email);
                            const totals = responseData10.map(item => item.total_email);

                            const myBarTopEmel14Chart = new Chart(ctxtop, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Total Emails',
                                        data: totals,
                                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                                        borderColor: 'rgba(54, 162, 235, 0.8)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        }
                        // barChartJadual14 end

                        function downloadChartJadual14() {
                                    const canvas = document.getElementById('topEmel14');
                                    const context = canvas.getContext('2d');
                                    const currentCanvasContent = context.getImageData(0, 0, canvas.width, canvas.height);
                                    context.globalCompositeOperation = 'destination-over';
                                    context.fillStyle = 'white';
                                    context.fillRect(0, 0, canvas.width, canvas.height);
                                    const url = canvas.toDataURL('image/jpeg', 1.0);
                                    context.putImageData(currentCanvasContent, 0, 0);
                                    const link = document.createElement('a');
                                    link.download = 'Chart of Top 20 Email Senders-'+serverName+'.jpg';
                                    link.href = url;
                                    link.click();
                                    }

                                    // Attach event listener for jadual6 download button
                                    document.getElementById('downloadButtonJadual14').addEventListener('click', downloadChartJadual14);


                    },
                    error: function (error) {
                        // Hide the SweetAlert timer
                        Swal.close();

                        // Handle the error
                        console.log(error);
                    }
                });
            }
        });


        $('#reset').on('click', function() {
            location.reload();
        });

        // Event listener for select change
        $('#server').on('change', function() {
            var selectedServerId = $(this).val();

            // AJAX request to fetch data based on the selected server ID
            $.ajax({
                url: "{!! route('monthlyReport.api') !!}",
                method: 'GET',
                data: {
                    serverId: selectedServerId
                },
                success: function(response) {
                    // Handle success response and update UI
                    //console.log(response);
                },
                error: function(xhr, status, error) {
                    // Handle error
                    //console.error(xhr.responseText);
                }
            });
        });
    });


</script>
@endpush

