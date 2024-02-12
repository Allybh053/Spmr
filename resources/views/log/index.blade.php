@extends('layouts.app')
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
                                        <li class="breadcrumb-item">Log Overview</li>
                                    </ol>
                                </nav>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="container-xl px-4 mt-4">
            <div class="row">
                <div class="col-xl-12">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">Log Overview Server</div>

                        
                        <div class="card-body mt-1 mb-4">
                            <form  id="searchForm"  class="ajax-form-search" novalidate action="{{ route('log.api.getServerName') }}" method="POST">
                                <!-- Form Row-->
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

        @include('log.view')
        

 @endsection

 @push('script')
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>


 <script>
         var myPieChart;

  // Set new default font family and font color to mimic Bootstrap's default styling
  (Chart.defaults.global.defaultFontFamily = "Metropolis"),
    '-apple-system,system-ui,BlinkMacSystemFont,\"Segoe UI\",Roboto,\"Helvetica Neue\",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = "#858796";

    // Dynamic labels and data for the pie chart
    var dynamicLabels = [];
    var dynamicData = [];

    @foreach($serverData['messagesChart'] as $key => $value)
        dynamicLabels.push("{{ $key }}");
        dynamicData.push("{{ $value['data1'] }}");
    @endforeach

    // Pie chart data
    var pieChartData = {
        labels: dynamicLabels,
        datasets: [{
            data: dynamicData,
            backgroundColor: [
                "rgba(0, 97, 242, 1)",
                "rgba(0, 172, 105, 1)",
                "rgba(88, 0, 232, 1)",
                "rgba(255, 193, 7, 1)",
                "rgba(255, 0, 0, 1)",
                "rgba(0, 255, 0, 1)"
            ],
            hoverBackgroundColor: [
                "rgba(0, 97, 242, 0.9)",
                "rgba(0, 172, 105, 0.9)",
                "rgba(88, 0, 232, 0.9)",
                "rgba(255, 193, 7, 0.9)",
                "rgba(255, 0, 0, 0.9)",
                "rgba(0, 255, 0, 0.9)"
            ],
            hoverBorderColor: "rgba(234, 236, 244, 1)"
        }]
    };

    // Get canvas element
    var ctx = document.getElementById("myPieChart");

    // Initialize the pie chart
     myPieChart = new Chart(ctx, {
        type: "doughnut",
        data: pieChartData,
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: "#dddfeb",
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80
        }
    });





    $(document).ready(function() {
        $('#searching').click(function(e) {
            e.preventDefault();
            var formData = $('#searchForm').serialize();
            
            $.ajax({
                type: 'POST',
                url: $('#searchForm').attr('action'),
                data: formData,
                success: function(response) {
                    // Handle the response from the controller
                    console.log(response);

                    $('#card-title-header').text(response.serverName);
                    $('#ip-address').text('IP Address: ' + response.ipAddress);
                    $('#server-status').text(response.serverStatus);
                    $('#service-status').text(response.serviceStatus);
                    $('#sasl').text(response.sasl);
                    $('#smtpd-lost').text(response.smtpdLost);
                    $('#smtpd-timeout').text(response.smtpdTimeOut);
                    $('#bounced').text(response.bounced);

                    if (myPieChart) {
                            myPieChart.destroy();
                        }

                    // Constructing dynamic labels and dataset values
                    var labels = [];
                    var datasetValues = [];

                    Object.keys(response.messagesChart).forEach(function(key) {
                        labels.push(key);
                        datasetValues.push(response.messagesChart[key].data1);
                    });

                    // Update Chart.js pie chart with new data
                    var ctx = document.getElementById("myPieChart");
                     myPieChart = new Chart(ctx, {
                        type: "doughnut",
                        data: {
                            labels: labels,
                            datasets: [{
                                data: datasetValues,
                                backgroundColor: [
                                    "rgba(0, 97, 242, 1)",
                                    "rgba(0, 172, 105, 1)",
                                    "rgba(88, 0, 232, 1)",
                                    "rgba(255, 193, 7, 1)",
                                    "rgba(255, 0, 0, 1)",
                                    "rgba(0, 255, 0, 1)"
                                ],
                                hoverBackgroundColor: [
                                    "rgba(0, 97, 242, 0.9)",
                                    "rgba(0, 172, 105, 0.9)",
                                    "rgba(88, 0, 232, 0.9)",
                                    "rgba(255, 193, 7, 0.9)",
                                    "rgba(255, 0, 0, 0.9)",
                                    "rgba(0, 255, 0, 0.9)"
                                ],
                                hoverBorderColor: "rgba(234, 236, 244, 1)"
                            }]
                        },
                        options: {
                            maintainAspectRatio: false,
                            tooltips: {
                                backgroundColor: "rgb(255,255,255)",
                                bodyFontColor: "#858796",
                                borderColor: "#dddfeb",
                                borderWidth: 1,
                                xPadding: 15,
                                yPadding: 15,
                                displayColors: false,
                                caretPadding: 10
                            },
                            legend: {
                                display: true
                            },
                            cutoutPercentage: 80
                        }
                    });


                    // Update Top 10 Host/Domain table
                    var top10TableData = response.top10Table;
                    var top10TableBody = $('#spmrDatatable tbody');
                    top10TableBody.empty(); // Clear existing rows

                    top10TableData.forEach(function(rowData, index) {
                        var rowHtml = '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + rowData.host + '</td>' +
                            '<td>' + rowData.senderEmail + '</td>' +
                            '<td>' + rowData.receipientEmail + '</td>' +
                            '</tr>';
                        top10TableBody.append(rowHtml);
                    });

                    // Update Traffic Summary table
                    var trafficSummaryData = response.trafficSummary;
                    var trafficSummaryTableBody = $('#spmrTrafficDatatable tbody');
                    trafficSummaryTableBody.empty(); // Clear existing rows

                    trafficSummaryData.forEach(function(rowData, index) {
                        var rowHtml = '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + rowData.message + '</td>' +
                            '<td>' + rowData.data1 + '</td>' +
                            '<td>' + rowData.data2 + '</td>' +
                            '<td>' + rowData.data3 + '</td>' +
                            '</tr>';
                        trafficSummaryTableBody.append(rowHtml);
});

                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>


 @endpush
