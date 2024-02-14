@extends('layouts.app')
@push('css')
<style>
    .tableFixHead {
        overflow-y: auto; /* make the table scrollable if height is more than 200 px */
        height: 291px; /* gives an initial height of 200px to the table */
        position: relative; /* ensure the tableFixHead acts as a containing block */
    }
    .tableFixHead thead th,
    .tableFixHead tfoot th {
        position: sticky; /* make the table heads and foots sticky */
        top: 0px; /* table head and foot will be placed from the top of the table and stick to it */
        background: #eee; /* background color for header and footer cells */
    }
    .tableFixHead thead th {
        z-index: 2; /* ensure the header is above the body when scrolling */
    }
    .tableFixHead tfoot th {
        z-index: 2; /* ensure the footer is above the body when scrolling */
        top: auto; /* reset the top position */
        bottom: 0; /* align the footer to the bottom of the tableFixHead */
    }
    .tableFixHead table {
        border-collapse: collapse; /* make the table borders collapse to each other */
        width: 100%;
    }
    .tableFixHead th,
    .tableFixHead td {
        padding: 8px 16px;
        border: 1px solid #ccc;
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
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                                        <li class="breadcrumb-item">Report</li>
                                    </ol>
                                </nav>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="container-xl px-4 mt-4" id="searchingBox">
            <div class="row">
                <div class="col-xl-12">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">Report Statistic Summary</div>

                        
                        <div class="card-body mt-1 mb-4">
                        <form id="report-form">
                        <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="serverName">Server Name:</label>
                                        {!! Form::select('serverName', $serverName, null, ['id'=>'serverName', 'name'=>'serverName', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
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

                    </div>
                </div>
            </div>
        </div>
        
        @include('reports.trafficSummary.report')

 @endsection
 @push('script')
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
 <!-- Include the xlsx library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.0/xlsx.full.min.js"></script>

 <script src="{{asset('assets/demo/chart-bar-demo.js')}}"></script>
 <script>
    $('#back').on('click', function (e) {
        $('#reportBox').fadeOut();
       $('#searchingBox').fadeIn();
    });

    $('form').on('submit', function (e) {
				e.preventDefault();
				$(".form-group").removeClass("has-error");
				$(".form-control").removeClass("was-validated invalid is-invalid custom-select.is-invalid valid is-valid custom-select.is-valid");
				$(".form-group").children("span.help-block").remove();
             
				toggleLoader();

				$.ajax({
					type: 'POST',
					url: "{!! route('report.statistic.validation') !!}",
					data: $('form').serialize(),
					success: function ($response) {
                        console.log($response);
                        $('#reportBox').fadeIn();
                        $('#searchingBox').fadeOut();

                        var serverName = $response.serverName; // Assuming server name is stored in $response.serverName

                    // Add data-server attribute to each button
                    $('#exportExcel').attr('data-server', serverName);
                    $('#exportCSV').attr('data-server', serverName);
                    $('#exportPDF').attr('data-server', serverName);

                    // Update reported period
                    $('#spmrTrafficDatatable thead tr:eq(0) td:eq(1)').text($response.reportPeriod);

                    // Update first timestamp
                    $('#spmrTrafficDatatable thead tr:eq(1) td:eq(1)').text($response.first);

                    // Update last timestamp
                    $('#spmrTrafficDatatable thead tr:eq(2) td:eq(1)').text($response.last);

                    // Update number of mails successfully sent
                    $('#spmrTrafficDatatable tbody tr:eq(1) td:eq(1)').text($response.mailSuccessSend.mails);

                    // Update size of mails successfully sent
                    $('#spmrTrafficDatatable tbody tr:eq(1) td:eq(2)').text($response.mailSuccessSend.size);

                    // Update number of mails failed/refused
                    $('#spmrTrafficDatatable tbody tr:eq(2) td:eq(1)').text($response.mailFailed.mails);

                    // Update size of mails failed/refused
                    $('#spmrTrafficDatatable tbody tr:eq(2) td:eq(2)').text($response.mailFailed.size);


                   // Assuming $response contains the data for the daily history chart
                    var dailyData = $response.dailyHistory;

                    // Update labels and data for the chart
                    myBarChartDaily.data.labels = dailyData.map(entry => entry.day);
                    myBarChartDaily.data.datasets[0].data = dailyData.map(entry => entry.mail);

                   // Update the chart
                    myBarChartDaily.update();

                    // Clear existing table data
                    $('#dailyHistoryDatatable tbody').empty();

                    // Initialize variables to calculate average and total
                    var totalMail = 0;
                    var totalSize = 0;

                    // Loop through the received data and append rows to the table body
                    $.each($response.dailyHistory, function(index, entry) {
                        $('#dailyHistoryDatatable tbody').append('<tr><td>' + entry.day + '</td><td>' + entry.mail + '</td><td>' + entry.size + '</td></tr>');

                        // Add mail count and size to totals
                        totalMail += parseInt(entry.mail);
                        totalSize += parseFloat(entry.size); // Assuming size is in MB
                    });

                    // Calculate average
                    var averageMail = totalMail / $response.dailyHistory.length;
                    var averageSize = totalSize / $response.dailyHistory.length;

                    // Update the average row in the table footer
                    $('#dailyHistoryDatatable tfoot tr:eq(0) th:eq(1)').text(averageMail.toFixed(2)); // Round to 2 decimal places
                    $('#dailyHistoryDatatable tfoot tr:eq(0) th:eq(2)').text(averageSize.toFixed(2) + ' MB'); // Round to 2 decimal places and append 'MB'

                    // Update the total row in the table footer
                    $('#dailyHistoryDatatable tfoot tr:eq(1) th:eq(1)').text(totalMail);
                    $('#dailyHistoryDatatable tfoot tr:eq(1) th:eq(2)').text(totalSize.toFixed(2) + ' MB'); // Round to 2 decimal places and append 'MB'


                   // Assuming response contains the data for the monthly history chart
                    var monthlyData = $response.monthlyHistory;

                    // Update labels and data for the chart
                    if (myBarChartMonthly) {
                        myBarChartMonthly.data.labels = monthlyData.map(entry => entry.month);
                        myBarChartMonthly.data.datasets[0].data = monthlyData.map(entry => entry.mail);

                        // Update the chart
                        myBarChartMonthly.update();
                    }
                        // Clear existing table data
                    $('#monthlyHistoryDatatable tbody').empty();

                    // Initialize variables for total mail and total size
                    var totalMail = 0;
                    var totalSize = 0;

                    // Loop through the received data and append rows to the table body
                    $.each($response.monthlyHistory, function(index, entry) {
                        $('#monthlyHistoryDatatable tbody').append('<tr><td>' + entry.month + '</td><td>' + entry.mail + '</td><td>' + entry.size + '</td></tr>');

                        // Add mail count and size to totals
                        totalMail += parseInt(entry.mail);
                        totalSize += parseFloat(entry.size); // Assuming size is in MB
                    });

                    // Update the total row in the table footer
                    $('#monthlyHistoryDatatable tfoot tr th:eq(1)').text(totalMail);
                    $('#monthlyHistoryDatatable tfoot tr th:eq(2)').text(totalSize.toFixed(2) + ' MB'); // Round to 2 decimal places and append 'MB'


                    // Assuming response contains the data for the yearly history chart
                    var yearlyData = $response.yearlyHistory;

                    // Update labels and data for the chart
                    if (myBarChartYearly) {
                        myBarChartYearly.data.labels = yearlyData.map(entry => entry.year);
                        myBarChartYearly.data.datasets[0].data = yearlyData.map(entry => entry.mail);

                        // Update the chart
                        myBarChartYearly.update();
                    }

                   // Clear existing table data
                    $('#yearlyHistoryDatatable tbody').empty();

                    // Initialize variables for total mail and total size
                    var totalMail = 0;
                    var totalSize = 0;

                    // Loop through the received data and append rows to the table body
                    $.each($response.yearlyHistory, function(index, entry) {
                        $('#yearlyHistoryDatatable tbody').append('<tr><td>' + entry.year + '</td><td>' + entry.mail + '</td><td>' + entry.size + '</td></tr>');

                        // Add mail count and size to totals
                        totalMail += parseInt(entry.mail);
                        totalSize += parseFloat(entry.size); // Assuming size is in MB
                    });

                    // Update the total row in the table footer
                    $('#yearlyHistoryDatatable tfoot tr th:eq(1)').text(totalMail);
                    $('#yearlyHistoryDatatable tfoot tr th:eq(2)').text(totalSize.toFixed(2) + ' MB'); // Round to 2 decimal places and append 'MB'


						toggleLoader();
					},
		            error: function (xhr, status) {
		                toggleLoader();
		                var response = xhr.responseJSON;

		                if ( $.isEmptyObject(response.errors) ){
		                    var message = response.message;

		                    if (! message.length && response.exception){
		                        message = response.exception;
		                    }

		                    swal("Warning", message, "warning");
		                } else {
		                    swal('Amaran', 'Invalid Information.', 'warning');

		                    $.each(response.errors, function (key, message) {
		                        if (key.indexOf('.') !== -1) {
		                            var splits = key.split('.');
		                            key = '';

		                            $.each(splits, function(i, val) {
		                                if (i === 0) {
		                                    key = val;
		                                } else {
		                                    key += '[' + val + ']';
		                                }
		                            });
		                        }

		                        $('[name="' + key + '"]').closest('.form-group').addClass("has-error");
		                        $('[name="' + key + '"]').addClass("was-validated is-invalid invalid custom-select.is-invalid");
		                        $('[name="' + key + '"]').closest('.form-group').append("<span class=\"help-block\" style='color:red; font-family:Nunito, sans-serif;'>" + message[0] + "</span>");
		                    });
		                    $('html, body').animate({
		                        scrollTop: ($(".has-error").first().offset().top) - 200
		                    }, 500);
		                }
		            }
				});
			});

            // $('#exportExcel').click(function() {
            //     var server = $(this).data('server');
            //     // Make AJAX call to retrieve data
            //     $.ajax({
            //         type: 'POST',
            //         url: "{!! route('report.statistic.validation') !!}",
            //         data: { serverName: server },
            //         success: function(response) {
            //             // Trigger Excel download
            //             console.log(response);
            //         },
            //         error: function(xhr, status, error) {
            //             // Handle error if any
            //             console.error(xhr.responseText);
            //         }
            //     });



 </script>
 @endpush