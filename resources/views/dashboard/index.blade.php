@extends('layouts.app')
@section('content')
<header class="py-10 mb-4 bg-gradient-primary-to-secondary">
  <div class="container-xl px-4">
    <div class="text-center">
      <h1 class="text-white">Welcome to SPMR System</h1>
      <p class="lead mb-0 text-white-50">Dashboard For Mail Relay Server</p>
    </div>
  </div>
</header>
<!-- Main page content-->
<div class="container-xl px-4 mt-n10">
  <!-- <a class="card card-icon lift lift-sm mb-4" href="#"><div class="row"><div class="col-auto card-icon-aside bg-primary"><i class="text-white-50" data-feather="server"></i></div><div class="col"><div class="card-body py-4">
                                        {!! Form::select('server', $serverconfig,  $defaultServerId,  ['id'=>'server', 'name'=>'server', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                        <p class="card-text mt-1 mb-1" id="ipAddress"></p><div class="small text-muted"></div></div></div></div></a> -->

<!-- Start Loop By Server Here -->
    @foreach ($serverconfig as $server)
  <div class="row mb-4">
    <div class="col-xxl-12">
      <div class="card">
        <div class="card-header text-center p-5">
          <h5 style="text-align:left;">
              {{ $server->hostname }}
              <p>{{ $server->ipaddress }}</p>
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-xl-4 mb-4">
              <a class="card lift h-100" href="#!">
                <div class="card-body d-flex justify-content-center flex-column">
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="me-3">
                      <i class="feather-xl text-primary mb-3" data-feather="activity"></i>
                      <h5>Total Application Registered</h5>
                      <div class="inline">
                        <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                        <h2 id="totalApplications" style="display: inline-block; margin-left: 5px;">-</h2>
                      </div>
                    </div>
                    <img src="{{asset ('assets/img/illustrations/dash_app.svg')}}" alt="..." style="width: 8rem" />
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-4 mb-4">
              <a class="card lift h-100" href="#!">
                <div class="card-body d-flex justify-content-center flex-column">
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="me-3">
                      <i class="feather-xl text-secondary mb-3" data-feather="zap"></i>
                      <h5>Total Domain Registered</h5>
                      <div class="inline">
                        <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                        <h2 id="totalDomains" style="display: inline-block; margin-left: 5px;">-</h2>
                      </div>
                    </div>
                    <img src="{{asset ('assets/img/illustrations/dash_domain.svg')}}" alt="..." style="width: 8rem" />
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-4 mb-4">
              <a class="card lift h-100" href="#!">
                <div class="card-body d-flex justify-content-center flex-column">
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="me-3">
                      <i class="feather-xl text-green mb-3" data-feather="shield"></i>
                      <h5>Numbers of Registered SASL</h5>
                      <div class="inline">
                        <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                        <h2 id="totalSASL" style="display: inline-block; margin-left: 5px;">-</h2>
                      </div>
                    </div>
                    <img src="{{asset ('assets/img/illustrations/dash_sasl.svg')}}" alt="..." style="width: 8rem" />
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
    @endforeach
<!-- End Loop By Server Here -->

  <div class="row mb-4">
    <div class="col-xxl-6">
      <div class="card">
        <div class="card-header text-center p-5">
          <h5>Top List Ministry/Agency/SUK by Server</h5>
        </div>
        <div class="card-body">
          <!-- <label for="limitSelectKementerian">Select Limit:</label><select name="limitKementerian" id="limitSelectKementerian"><option value="all" {{ $limit === 'all' ? 'selected' : '' }}>All</option><option value="10" {{ $limit == 10 ? 'selected' : '' }}>Top 10</option><option value="20" {{ $limit == 20 ? 'selected' : '' }}>Top 20</option><option value="50" {{ $limit == 50 ? 'selected' : '' }}>Top 50</option></select> -->
          <canvas id="kementerianChart"></canvas>
        </div>
      </div>
    </div>
    <div class="col-xxl-6">
      <div class="card">
        <div class="card-header text-center p-5">
          <h5>Top List Department/Division/Unit by Server</h5>
        </div>
        <div class="card-body">
          <!-- <label for="limitSelect">Select Limit:</label><select name="limit" id="limitSelect"><option value="all" {{ $limit === 'all' ? 'selected' : '' }}>All</option><option value="10" {{ $limit == 10 ? 'selected' : '' }}>Top 10</option><option value="20" {{ $limit == 20 ? 'selected' : '' }}>Top 20</option><option value="50" {{ $limit == 50 ? 'selected' : '' }}>Top 50</option></select> -->
          <canvas id="departmentChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="row mb-4"><div class="col-xxl-4"><div class="card"><div class="card-header text-center p-5"><h5>Graf serial apps vs day</h5></div><div class="card-body"><canvas id="appsChart"></canvas></div></div></div><div class="col-xxl-4"><div class="card"><div class="card-header text-center p-5"><h5>Graf serial domain vs day</h5></div><div class="card-body"><canvas id="domainChart"></canvas></div></div></div><div class="col-xxl-4"><div class="card"><div class="card-header text-center p-5"><h5>Graf serial sasl vs day</h5></div><div class="card-body"><canvas id="saslChart"></canvas></div></div></div></div> -->
  <div class="row">
    <div class="col-xxl-12">
      <div class="card">
        <div class="card-header text-center p-5">
          <h5>View MailQueue Count (last 5 minutes)</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="spmrDatatable" class="display table table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr>
                    @foreach ($serverconfig as $server)
                        <th>{{ $server->hostname }}</th>
                    @endforeach
                </tr>
              </thead>
              <tbody>
                <tr>
                    @foreach ($serverconfig as $server)
                        <td id="mailQueueCount">--</td>
                    @endforeach
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="col-xxl-6"><div class="card"><div class="card-header text-center p-5"><h5>Graf serial Mail Queue vs Time</h5></div><div class="card-body"><canvas id="mailQueueChart"></canvas></div></div></div> -->
  </div>
  <div class="row  mt-5">
    <div class="col-xxl-12">
      <div class="card">
        <div class="card-header text-center p-5">
          <h5>RealTime Service Status</h5> @php $status = 'Online'; @endphp
        </div>
        {{--<div class="card-body mainRealtime" style="min-height: auto; max-height: 800px;overflow-y: auto;">--}}
        <div class="card-body mainRealtime">
            @foreach ($serverconfig as $server)
                <div class="card" style="margin: 15px">
                    <div class="card-header">{{ $server->hostname }}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 mb-4">
                                <a class="card lift h-100 mService" href="{{ route('manageService.index', ['server_id' => '1', 'server_type' => 'postfix']) }}">
                                    <div class="card-body cardService d-flex justify-content-center flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="me-3">
                                                <h5>Postfix</h5>
                                            </div>
                                            <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">
                                        </div>
                                        <p id="statusPostfix" style="display: inline-block; margin-left: 5px;">--</p>
                                    </div>
                                    <!-- <div class="col-md-6 d-flex justify-content-center" style="padding-left: 150px;padding-bottom: 15px;"><div class="btn-group" role="group" aria-label="Button group with nested dropdown"><button type="button" class="btn btn-primary"><i class="fas fa-play"></i></button><button type="button" class="btn btn-danger"><i class="fas fa-stop"></i></button><button type="button" class="btn btn-warning"><i class="fas fa-redo"></i></button><button type="button" class="btn btn-info"><i class="fas fa-sync-alt"></i></button></div></div> -->
                                </a>
                            </div>
                            {{--<div class="col-xl-4 mb-4">
                              <a class="card lift h-100" href="#!">
                                <div class="card-body cardService d-flex justify-content-center flex-column">
                                  <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                      <h5>Fail-To-Ban</h5>
                                    </div>
                                    <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">
                                  </div>
                                    <p id="statusFail2ban" style="display: inline-block; margin-left: 5px;">--</p>
                                </div>
                              </a>
                            </div>--}}
                            <div class="col-xl-4 mb-4">
                                <a class="card lift h-100 mService" href="{{ route('manageService.index', ['server_id' => '1', 'server_type' => 'apache']) }}">
                                    <div class="card-body cardService d-flex justify-content-center flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="me-3">
                                                <h5>Apache</h5>
                                            </div>
                                            <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">
                                        </div>
                                        <p id="statusApache2" style="display: inline-block; margin-left: 5px;">--</p>
                                    </div>
                                    <!-- <div class="col-md-6 d-flex justify-content-center" style="padding-left: 150px;padding-bottom: 15px;"><div class="btn-group" role="group" aria-label="Button group with nested dropdown"><button type="button" class="btn btn-primary"><i class="fas fa-play"></i></button><button type="button" class="btn btn-danger"><i class="fas fa-stop"></i></button><button type="button" class="btn btn-warning"><i class="fas fa-redo"></i></button><button type="button" class="btn btn-info"><i class="fas fa-sync-alt"></i></button></div></div> -->
                                </a>
                            </div>
                            <div class="col-xl-4 mb-4">
                                <a class="card lift h-100" href="#!">
                                    <div class="card-body cardService d-flex justify-content-center flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="me-3">
                                                <h5>Webmin</h5>
                                            </div>
                                            <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">
                                        </div>
                                        <p id="statusWebmin" style="display: inline-block; margin-left: 5px;">--</p>
                                    </div>
                                </a>
                            </div>
                            {{--<div class="col-xl-4 mb-4">
                              <a class="card lift h-100" href="#!">
                                <div class="card-body cardService d-flex justify-content-center flex-column">
                                  <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                      <h5>Replicate Mail Relay</h5>
                                    </div>
                                    <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">
                                  </div>
                                </div>
                              </a>
                            </div>--}}
                            {{--<div class="col-xl-4 mb-4">
                              <a class="card lift h-100" href="#!">
                                <div class="card-body cardService d-flex justify-content-center flex-column">
                                  <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                      <h5>Policy Server</h5>
                                    </div>
                                    <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">
                                  </div>
                                </div>
                              </a>
                            </div>--}}
                            {{--<div class="col-xl-4 mb-4">
                              <a class="card lift h-100" href="#!">
                                <div class="card-body cardService d-flex justify-content-center flex-column">
                                  <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                      <h5>Keep Alive</h5>
                                    </div>
                                    <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">
                                  </div>
                                </div>
                              </a>
                            </div>--}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
<script>
// Set new default font family and font color to mimic Bootstrap's default styling
(Chart.defaults.global.defaultFontFamily = "Metropolis"),
'-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#858796";

function number_format(number, decimals, dec_point, thousands_sep) {
    // *     example: number_format(1234.56, 2, ',', ' ');
    // *     return: '1 234,56'
    number = (number + "").replace(",", "").replace(" ", "");
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
        dec = typeof dec_point === "undefined" ? "." : dec_point,
        s = "",
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return "" + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || "").length < prec) {
        s[1] = s[1] || "";
        s[1] += new Array(prec - s[1].length + 1).join("0");
    }
    return s.join(dec);
}

</script>
<script>

  $(document).ready(function() {

        $('a.mService').click(function(e) {
            e.preventDefault(); // Prevent default link behavior
            var href = $(this).attr('href'); // Get the href attribute
            // Perform animation here (e.g., fadeOut, slideUp)
            $('body').fadeOut(300, function() {
                // Redirect to the selected page after animation
                window.location.href = href;
            });
        });

        renderChart();
        function renderChart() {
            var departmentChart = document.getElementById('departmentChart').getContext('2d');
            window.chart = new Chart(departmentChart, {
                type: 'bar',
                data: {
                labels: <?php echo json_encode($departmentCounts->pluck('ministry_name')); ?>,
                datasets: [{
                    label: 'Top Department/Division/Unit',
                    data: <?php echo json_encode($departmentCounts->pluck('count')); ?>,
                    backgroundColor: "rgba(0, 97, 242, 1)",
                    hoverBackgroundColor: "rgba(0, 97, 242, 0.9)",
                    borderColor: "#4e73df",
                    maxBarThickness: 25
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
        // $('#limitSelect').change(function() {
        //   var limit = $(this).val();
        //   // Check if there's an existing chart instance
        //   if (window.chart) {
        //     window.chart.destroy(); // Destroy the existing chart instance
        //   }
        //   // Send AJAX request to fetch data based on selected limit
        //   $.ajax({
        //     url: "{{ route('dashboard.index') }}",
        //     method: 'GET',
        //     data: {
        //       limit: limit
        //     },
        //     success: function(response) {
        //       console.log(response); // Add this line to debug the response
        //       if (response && response.departmentCounts) {
        //         updateChart(response.departmentCounts);
        //       } else {
        //         console.error("Invalid response format");
        //       }
        //     },
        //     error: function(xhr, status, error) {
        //       console.error(error);
        //     }
        //   });
        // });
        // Function to update the chart
        function updateChart(data) {
          var ctx = document.getElementById('departmentChart').getContext('2d');
          // Create new chart instance
          window.chart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: data.map(d => d.ministry_name),
              datasets: [{
                label: 'Top Department/Division/Unit',
                data: data.map(d => d.count),
                backgroundColor: "rgba(0, 97, 242, 1)",
                hoverBackgroundColor: "rgba(0, 97, 242, 0.9)",
                borderColor: "#4e73df",
                maxBarThickness: 25
              }]
            },
            options: {
              scales: {
                y: {
                  beginAtZero: true // Example y-axis configuration, adjust as needed
                }
              }
            }
          });
        }
        renderChartKementerian();

        function renderChartKementerian() {
          var ctx = document.getElementById('kementerianChart').getContext('2d');
          window.kementerianChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($departmentCounts->pluck('ministry_name')); ?>,
              datasets: [{
                label: 'Top Kementerian/Agensi/SUK',
                data: <?php echo json_encode($departmentCounts->pluck('count')); ?>,
                backgroundColor: "rgba(0, 97, 242, 1)",
                hoverBackgroundColor: "rgba(0, 97, 242, 0.9)",
                borderColor: "#4e73df",
                maxBarThickness: 25
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
        // $('#limitSelectKementerian').change(function() {
        //   var limitK = $(this).val();
        //   // Check if there's an existing chart instance
        //   if (window.kementerianChart) {
        //     window.kementerianChart.destroy(); // Destroy the existing chart instance
        //   }
        //   // Send AJAX request to fetch data based on selected limit
        //   $.ajax({
        //     url: "{{ route('dashboard.index') }}",
        //     method: 'GET',
        //     data: {
        //       limit: limitK
        //     },
        //     success: function(response) {
        //       console.log(response); // Add this line to debug the response
        //       if (response && response.departmentCounts) {
        //         updateChartKementerian(response.departmentCounts);
        //       } else {
        //         console.error("Invalid response format");
        //       }
        //     },
        //     error: function(xhr, status, error) {
        //       console.error(error);
        //     }
        //   });
        // });
        // Function to update the chart
        // function updateChartKementerian(data) {
        //   var ctx = document.getElementById('kementerianChart').getContext('2d');
        //   // Create new chart instance
        //   window.kementerianChart = new Chart(ctx, {
        //     type: 'bar',
        //     data: {
        //       labels: data.map(d => d.ministry_name),
        //       datasets: [{
        //         label: 'Top Kementerian/Agensi/SUK',
        //         data: data.map(d => d.count),
        //         backgroundColor: "rgba(0, 97, 242, 1)",
        //         hoverBackgroundColor: "rgba(0, 97, 242, 0.9)",
        //         borderColor: "#4e73df",
        //         maxBarThickness: 25
        //       }]
        //     },
        //     options: {
        //       scales: {
        //         y: {
        //           beginAtZero: true // Example y-axis configuration, adjust as needed
        //         }
        //       }
        //     }
        //   });
        // }
        // mailq
        // Extract data passed from the controller
        // var timestamps = @php echo json_encode($timestamps) @endphp;
        // var mailCounts = @php echo json_encode($mailCounts) @endphp;
        // // Create a new Chart.js instance
        // var ctx = document.getElementById('mailQueueChart').getContext('2d');
        // var mailQueueChart = new Chart(ctx, {
        //   type: 'line',
        //   data: {
        //     labels: timestamps,
        //     datasets: [{
        //       label: 'Mail Queue',
        //       data: mailCounts,
        //       backgroundColor: 'rgba(255, 0, 0, 0.05)', // Fill color
        //       borderColor: 'rgba(255, 0, 0, 1)', // Border color
        //       borderWidth: 1
        //     }]
        //   },
        //   options: {
        //     scales: {
        //       x: {
        //         type: 'time', // Use time scale for x-axis
        //         time: {
        //           unit: 'day' // Display unit as day
        //         },
        //         title: {
        //           display: true,
        //           text: 'Time'
        //         }
        //       },
        //       y: {
        //         title: {
        //           display: true,
        //           text: 'Mail Count'
        //         },
        //         beginAtZero: true
        //       }
        //     }
        //   }
        // });

        // function updateMailQueueChart(timestamps, mailCounts) {
        //   var ctx = document.getElementById('mailQueueChart').getContext('2d');
        //   var mailQueueChart = new Chart(ctx, {
        //     type: 'line',
        //     data: {
        //       labels: timestamps,
        //       datasets: [{
        //         label: 'Mail Queue',
        //         data: mailCounts,
        //         backgroundColor: 'rgba(255, 0, 0, 0.05)', // Fill color
        //         borderColor: 'rgba(255, 0, 0, 1)', // Border color
        //         borderWidth: 1
        //       }]
        //     },
        //     options: {
        //       scales: {
        //         x: {
        //           type: 'time', // Use time scale for x-axis
        //           time: {
        //             unit: 'day' // Display unit as day
        //           },
        //           title: {
        //             display: true,
        //             text: 'Time'
        //           }
        //         },
        //         y: {
        //           title: {
        //             display: true,
        //             text: 'Mail Count'
        //           },
        //           beginAtZero: true
        //         }
        //       }
        //     }
        //   });
        //   return mailQueueChart;
        // }
        // // line series
        // renderChartLineApps();
        // var appsChart;
        // var domainChart;
        // var saslChart;
        // function renderChartLineApps() {
        //     var ctx = document.getElementById('appsChart').getContext('2d');
        //     var labels = @json($days); // Blade directive to encode PHP array to JSON
        // var p1Data = @json($p1Data); // Blade directive to encode PHP array to JSON
        // var p2Data = @json($p2Data); // Blade directive to encode PHP array to JSON
        // var p3Data = @json($p3Data); // Blade directive to encode PHP array to JSON
        // appsChart = new Chart(ctx, {
        //     type: 'line',
        //     data: {
        //         labels: labels,
        //         datasets: [
        //             {
        //                 label: 'P1',
        //                 data: p1Data,
        //                 lineTension: 0.3,
        //                 backgroundColor: "rgba(0, 97, 242, 0.05)",
        //                 borderColor: "rgba(0, 97, 242, 1)",
        //                 pointRadius: 3,
        //                 pointBackgroundColor: "rgba(0, 97, 242, 1)",
        //                 pointBorderColor: "rgba(0, 97, 242, 1)",
        //                 pointHoverRadius: 3,
        //                 pointHoverBackgroundColor: "rgba(0, 97, 242, 1)",
        //                 pointHoverBorderColor: "rgba(0, 97, 242, 1)",
        //                 pointHitRadius: 10,
        //                 pointBorderWidth: 2,
        //             },
        //             {
        //                 label: 'P2',
        //                 data: p2Data,
        //                 backgroundColor: "rgba(255, 0, 0, 0.05)",
        //                 borderColor: "rgba(255, 0, 0, 1)",
        //                 pointRadius: 3,
        //                 pointBackgroundColor: "rgba(255, 0, 0, 1)",
        //                 pointBorderColor: "rgba(255, 0, 0, 1)",
        //                 pointHoverRadius: 3,
        //                 pointHoverBackgroundColor: "rgba(255, 0, 0, 1)",
        //                 pointHoverBorderColor: "rgba(255, 0, 0, 1)",
        //                 pointHitRadius: 10,
        //                 pointBorderWidth: 2,
        //             },
        //             {
        //                 label: 'P3',
        //                 data: p3Data,
        //                 backgroundColor: "rgba(255, 255, 0, 0.05)",
        //                 borderColor: "rgba(255, 255, 0, 1)",
        //                 pointRadius: 3,
        //                 pointBackgroundColor: "rgba(255, 255, 0, 1)",
        //                 pointBorderColor: "rgba(255, 255, 0, 1)",
        //                 pointHoverRadius: 3,
        //                 pointHoverBackgroundColor: "rgba(255, 255, 0, 1)",
        //                 pointHoverBorderColor: "rgba(255, 255, 0, 1)",
        //                 pointHitRadius: 10,
        //                 pointBorderWidth: 2,
        //             }
        //         ]
        //     },
        //     options: {
        //         scales: {
        //             y: {
        //                 beginAtZero: true
        //             }
        //         }
        //     }
        //     });
        // }
        // function renderAppsChart(labels, p1Data, p2Data, p3Data) {
        //     var ctx = document.getElementById('appsChart').getContext('2d');
        //     appsChart = new Chart(ctx, {
        //         type: 'line',
        //         data: {
        //             labels: labels,
        //             datasets: [
        //                 {
        //                     label: 'P1',
        //                     data: p1Data,
        //                     lineTension: 0.3,
        //                     backgroundColor: "rgba(0, 97, 242, 0.05)",
        //                     borderColor: "rgba(0, 97, 242, 1)",
        //                     pointRadius: 3,
        //                     pointBackgroundColor: "rgba(0, 97, 242, 1)",
        //                     pointBorderColor: "rgba(0, 97, 242, 1)",
        //                     pointHoverRadius: 3,
        //                     pointHoverBackgroundColor: "rgba(0, 97, 242, 1)",
        //                     pointHoverBorderColor: "rgba(0, 97, 242, 1)",
        //                     pointHitRadius: 10,
        //                     pointBorderWidth: 2,
        //                 },
        //                 {
        //                     label: 'P2',
        //                     data: p2Data,
        //                     backgroundColor: "rgba(255, 0, 0, 0.05)",
        //                     borderColor: "rgba(255, 0, 0, 1)",
        //                     pointRadius: 3,
        //                     pointBackgroundColor: "rgba(255, 0, 0, 1)",
        //                     pointBorderColor: "rgba(255, 0, 0, 1)",
        //                     pointHoverRadius: 3,
        //                     pointHoverBackgroundColor: "rgba(255, 0, 0, 1)",
        //                     pointHoverBorderColor: "rgba(255, 0, 0, 1)",
        //                     pointHitRadius: 10,
        //                     pointBorderWidth: 2,
        //                 },
        //                 {
        //                     label: 'P3',
        //                     data: p3Data,
        //                     backgroundColor: "rgba(255, 255, 0, 0.05)",
        //                     borderColor: "rgba(255, 255, 0, 1)",
        //                     pointRadius: 3,
        //                     pointBackgroundColor: "rgba(255, 255, 0, 1)",
        //                     pointBorderColor: "rgba(255, 255, 0, 1)",
        //                     pointHoverRadius: 3,
        //                     pointHoverBackgroundColor: "rgba(255, 255, 0, 1)",
        //                     pointHoverBorderColor: "rgba(255, 255, 0, 1)",
        //                     pointHitRadius: 10,
        //                     pointBorderWidth: 2,
        //                 }
        //             ]
        //         },
        //         options: {
        //             scales: {
        //                 y: {
        //                     beginAtZero: true
        //                 }
        //             }
        //         }
        //     });
        //     return appsChart;
        // }
        // renderChartLineDomain();
        // function renderChartLineDomain() {
        //     var ctx = document.getElementById('domainChart').getContext('2d');
        //     var labels = @json($days); // Blade directive to encode PHP array to JSON
        // var p1Data = @json($p1Data); // Blade directive to encode PHP array to JSON
        // var p2Data = @json($p2Data); // Blade directive to encode PHP array to JSON
        // var p3Data = @json($p3Data); // Blade directive to encode PHP array to JSON
        // domainChart = new Chart(ctx, {
        //     type: 'line',
        //     data: {
        //         labels: labels,
        //         datasets: [
        //             {
        //                 label: 'P1',
        //                 data: p1Data,
        //                 lineTension: 0.3,
        //                 backgroundColor: "rgba(0, 97, 242, 0.05)",
        //                 borderColor: "rgba(0, 97, 242, 1)",
        //                 pointRadius: 3,
        //                 pointBackgroundColor: "rgba(0, 97, 242, 1)",
        //                 pointBorderColor: "rgba(0, 97, 242, 1)",
        //                 pointHoverRadius: 3,
        //                 pointHoverBackgroundColor: "rgba(0, 97, 242, 1)",
        //                 pointHoverBorderColor: "rgba(0, 97, 242, 1)",
        //                 pointHitRadius: 10,
        //                 pointBorderWidth: 2,
        //             },
        //             {
        //                 label: 'P2',
        //                 data: p2Data,
        //                 backgroundColor: "rgba(255, 0, 0, 0.05)",
        //                 borderColor: "rgba(255, 0, 0, 1)",
        //                 pointRadius: 3,
        //                 pointBackgroundColor: "rgba(255, 0, 0, 1)",
        //                 pointBorderColor: "rgba(255, 0, 0, 1)",
        //                 pointHoverRadius: 3,
        //                 pointHoverBackgroundColor: "rgba(255, 0, 0, 1)",
        //                 pointHoverBorderColor: "rgba(255, 0, 0, 1)",
        //                 pointHitRadius: 10,
        //                 pointBorderWidth: 2,
        //             },
        //             {
        //                 label: 'P3',
        //                 data: p3Data,
        //                 backgroundColor: "rgba(255, 255, 0, 0.05)",
        //                 borderColor: "rgba(255, 255, 0, 1)",
        //                 pointRadius: 3,
        //                 pointBackgroundColor: "rgba(255, 255, 0, 1)",
        //                 pointBorderColor: "rgba(255, 255, 0, 1)",
        //                 pointHoverRadius: 3,
        //                 pointHoverBackgroundColor: "rgba(255, 255, 0, 1)",
        //                 pointHoverBorderColor: "rgba(255, 255, 0, 1)",
        //                 pointHitRadius: 10,
        //                 pointBorderWidth: 2,
        //             }
        //         ]
        //     },
        //     options: {
        //         scales: {
        //             y: {
        //                 beginAtZero: true
        //             }
        //         }
        //     }
        //     });
        // }
        // function renderDomainChart(labels, p1Data, p2Data, p3Data) {
        //     var ctx = document.getElementById('domainChart').getContext('2d');
        //    domainChart = new Chart(ctx, {
        //         type: 'line',
        //         data: {
        //             labels: labels,
        //             datasets: [
        //                 {
        //                     label: 'P1',
        //                     data: p1Data,
        //                     lineTension: 0.3,
        //                     backgroundColor: "rgba(0, 97, 242, 0.05)",
        //                     borderColor: "rgba(0, 97, 242, 1)",
        //                     pointRadius: 3,
        //                     pointBackgroundColor: "rgba(0, 97, 242, 1)",
        //                     pointBorderColor: "rgba(0, 97, 242, 1)",
        //                     pointHoverRadius: 3,
        //                     pointHoverBackgroundColor: "rgba(0, 97, 242, 1)",
        //                     pointHoverBorderColor: "rgba(0, 97, 242, 1)",
        //                     pointHitRadius: 10,
        //                     pointBorderWidth: 2,
        //                 },
        //                 {
        //                     label: 'P2',
        //                     data: p2Data,
        //                     backgroundColor: "rgba(255, 0, 0, 0.05)",
        //                     borderColor: "rgba(255, 0, 0, 1)",
        //                     pointRadius: 3,
        //                     pointBackgroundColor: "rgba(255, 0, 0, 1)",
        //                     pointBorderColor: "rgba(255, 0, 0, 1)",
        //                     pointHoverRadius: 3,
        //                     pointHoverBackgroundColor: "rgba(255, 0, 0, 1)",
        //                     pointHoverBorderColor: "rgba(255, 0, 0, 1)",
        //                     pointHitRadius: 10,
        //                     pointBorderWidth: 2,
        //                 },
        //                 {
        //                     label: 'P3',
        //                     data: p3Data,
        //                     backgroundColor: "rgba(255, 255, 0, 0.05)",
        //                     borderColor: "rgba(255, 255, 0, 1)",
        //                     pointRadius: 3,
        //                     pointBackgroundColor: "rgba(255, 255, 0, 1)",
        //                     pointBorderColor: "rgba(255, 255, 0, 1)",
        //                     pointHoverRadius: 3,
        //                     pointHoverBackgroundColor: "rgba(255, 255, 0, 1)",
        //                     pointHoverBorderColor: "rgba(255, 255, 0, 1)",
        //                     pointHitRadius: 10,
        //                     pointBorderWidth: 2,
        //                 }
        //             ]
        //         },
        //         options: {
        //             scales: {
        //                 y: {
        //                     beginAtZero: true
        //                 }
        //             }
        //         }
        //     });
        //     return domainChart;
        // }
        // renderChartSaslDomain();
        // function renderChartSaslDomain() {
        //     var ctx = document.getElementById('saslChart').getContext('2d');
        //     var labels = @json($days); // Blade directive to encode PHP array to JSON
        // var p1Data = @json($p1Data); // Blade directive to encode PHP array to JSON
        // var p2Data = @json($p2Data); // Blade directive to encode PHP array to JSON
        // var p3Data = @json($p3Data); // Blade directive to encode PHP array to JSON
        // saslChart = new Chart(ctx, {
        //     type: 'line',
        //     data: {
        //         labels: labels,
        //         datasets: [
        //             {
        //                 label: 'P1',
        //                 data: p1Data,
        //                 lineTension: 0.3,
        //                 backgroundColor: "rgba(0, 97, 242, 0.05)",
        //                 borderColor: "rgba(0, 97, 242, 1)",
        //                 pointRadius: 3,
        //                 pointBackgroundColor: "rgba(0, 97, 242, 1)",
        //                 pointBorderColor: "rgba(0, 97, 242, 1)",
        //                 pointHoverRadius: 3,
        //                 pointHoverBackgroundColor: "rgba(0, 97, 242, 1)",
        //                 pointHoverBorderColor: "rgba(0, 97, 242, 1)",
        //                 pointHitRadius: 10,
        //                 pointBorderWidth: 2,
        //             },
        //             {
        //                 label: 'P2',
        //                 data: p2Data,
        //                 backgroundColor: "rgba(255, 0, 0, 0.05)",
        //                 borderColor: "rgba(255, 0, 0, 1)",
        //                 pointRadius: 3,
        //                 pointBackgroundColor: "rgba(255, 0, 0, 1)",
        //                 pointBorderColor: "rgba(255, 0, 0, 1)",
        //                 pointHoverRadius: 3,
        //                 pointHoverBackgroundColor: "rgba(255, 0, 0, 1)",
        //                 pointHoverBorderColor: "rgba(255, 0, 0, 1)",
        //                 pointHitRadius: 10,
        //                 pointBorderWidth: 2,
        //             },
        //             {
        //                 label: 'P3',
        //                 data: p3Data,
        //                 backgroundColor: "rgba(255, 255, 0, 0.05)",
        //                 borderColor: "rgba(255, 255, 0, 1)",
        //                 pointRadius: 3,
        //                 pointBackgroundColor: "rgba(255, 255, 0, 1)",
        //                 pointBorderColor: "rgba(255, 255, 0, 1)",
        //                 pointHoverRadius: 3,
        //                 pointHoverBackgroundColor: "rgba(255, 255, 0, 1)",
        //                 pointHoverBorderColor: "rgba(255, 255, 0, 1)",
        //                 pointHitRadius: 10,
        //                 pointBorderWidth: 2,
        //             }
        //         ]
        //     },
        //     options: {
        //         scales: {
        //             y: {
        //                 beginAtZero: true
        //             }
        //         }
        //     }
        //     });
        // }
        // function renderSaslChart(labels, p1Data, p2Data, p3Data) {
        //     var ctx = document.getElementById('saslChart').getContext('2d');
        //      saslChart = new Chart(ctx, {
        //         type: 'line',
        //         data: {
        //             labels: labels,
        //             datasets: [
        //                 {
        //                     label: 'P1',
        //                     data: p1Data,
        //                     lineTension: 0.3,
        //                     backgroundColor: "rgba(0, 97, 242, 0.05)",
        //                     borderColor: "rgba(0, 97, 242, 1)",
        //                     pointRadius: 3,
        //                     pointBackgroundColor: "rgba(0, 97, 242, 1)",
        //                     pointBorderColor: "rgba(0, 97, 242, 1)",
        //                     pointHoverRadius: 3,
        //                     pointHoverBackgroundColor: "rgba(0, 97, 242, 1)",
        //                     pointHoverBorderColor: "rgba(0, 97, 242, 1)",
        //                     pointHitRadius: 10,
        //                     pointBorderWidth: 2,
        //                 },
        //                 {
        //                     label: 'P2',
        //                     data: p2Data,
        //                     backgroundColor: "rgba(255, 0, 0, 0.05)",
        //                     borderColor: "rgba(255, 0, 0, 1)",
        //                     pointRadius: 3,
        //                     pointBackgroundColor: "rgba(255, 0, 0, 1)",
        //                     pointBorderColor: "rgba(255, 0, 0, 1)",
        //                     pointHoverRadius: 3,
        //                     pointHoverBackgroundColor: "rgba(255, 0, 0, 1)",
        //                     pointHoverBorderColor: "rgba(255, 0, 0, 1)",
        //                     pointHitRadius: 10,
        //                     pointBorderWidth: 2,
        //                 },
        //                 {
        //                     label: 'P3',
        //                     data: p3Data,
        //                     backgroundColor: "rgba(255, 255, 0, 0.05)",
        //                     borderColor: "rgba(255, 255, 0, 1)",
        //                     pointRadius: 3,
        //                     pointBackgroundColor: "rgba(255, 255, 0, 1)",
        //                     pointBorderColor: "rgba(255, 255, 0, 1)",
        //                     pointHoverRadius: 3,
        //                     pointHoverBackgroundColor: "rgba(255, 255, 0, 1)",
        //                     pointHoverBorderColor: "rgba(255, 255, 0, 1)",
        //                     pointHitRadius: 10,
        //                     pointBorderWidth: 2,
        //                 }
        //             ]
        //         },
        //         options: {
        //             scales: {
        //                 y: {
        //                     beginAtZero: true
        //                 }
        //             }
        //         }
        //     });
        //     return saslChart;
        // }
        // Event listener for select change
        // $('#server').on('change', function() {
        //       var selectedServerId = $(this).val();
        //       console.log(selectedServerId);
        //       // AJAX request to fetch data based on the selected server ID
        //       $.ajax({
        //             url: "{!! route('dashboard.api') !!}",
        //             method: 'GET',
        //             data: {
        //               serverId: selectedServerId
        //             },
        //             success: function(response) {
        //                 // Handle success response and update UI
        //                 console.log(response);
        //                 $('#ipAddress').text(response.ipAddress);
        //                 $('#totalApplications').text(response.totalApplications);
        //                 $('#totalDomains').text(response.totalDomains);
        //                 $('#totalSASL').text(response.totalSASL);
        //                 var tbody = $('#spmrDatatable tbody');
        //                 tbody.empty(); // Clear existing rows
        //                 // Iterate through the received data and populate the table
        //                 $.each(response.data, function(index, item) {
        //                       var row = $(' < tr > ');
        //                           row.append($(' < td > ').text(index + 1));
        //                               row.append($(' < td > ').text(item.columnA));
        //                                   row.append($(' < td > ').text(item.columnB));
        //                                       row.append($(' < td > ').text(item.columnC));
        //                                           row.append($(' < td > ').text(item.columnD));
        //                                             tbody.append(row);
        //                                           });
        //                                           var status = response.status;
        //                                           // Update the status of each service
        //                                           $('.cardService').each(function() {
        //                                             var service = $(this).find('h5').text().trim();
        //                                             var serviceStatus = response.services[service];
        //                                             var img = $(this).find('img');
        //                                             if (serviceStatus === 'Online') {
        //                                               img.attr('src', '{{ asset("assets/img/demo/online.png") }}');
        //                                               img.attr('alt', 'Online');
        //                                             } else {
        //                                               img.attr('src', '{{ asset("assets/img/demo/offline.png") }}');
        //                                               img.attr('alt', 'Offline');
        //                                             }
        //                                           });
        //                                           // barchart
        //                                           if (window.chart) {
        //                                             window.chart.destroy(); // Destroy the existing chart instance
        //                                           }
        //                                           updateChart(response.departmentCounts);
        //                                           if (window.kementerianChart) {
        //                                             window.kementerianChart.destroy(); // Destroy the existing chart instance
        //                                           }
        //                                           updateChartKementerian(response.departmentCounts);
        //                                           //     if (appsChart) {
        //                                           //     appsChart.destroy();
        //                                           // }
        //                                           // // Render the SASL chart with new data
        //                                           // appsChart = renderAppsChart(response.days, response.p1Data, response.p2Data, response.p3Data);
        //                                           // if (domainChart) {
        //                                           //     domainChart.destroy();
        //                                           // }
        //                                           // // Render the SASL chart with new data
        //                                           // domainChart = renderDomainChart(response.days, response.p1Data, response.p2Data, response.p3Data);
        //                                           // if (saslChart) {
        //                                           //     saslChart.destroy();
        //                                           // }
        //                                           // // Render the SASL chart with new data
        //                                           // saslChart = renderSaslChart(response.days, response.p1Data, response.p2Data, response.p3Data);
        //                                           if (mailQueueChart) {
        //                                             mailQueueChart.destroy();
        //                                           }
        //                                           // Update the line chart with new data
        //                                           mailQueueChart = updateMailQueueChart(response.timestamps, response.mailCounts);
        //                                         }, error: function(xhr, status, error) {
        //                                           // Handle error
        //                                           console.error(xhr.responseText);
        //                                         }
        //                                       });
        //                                   });
                                      });



  //TEST GET DATA FROM SERVER MAIL RELAY
  let sockets = [];
  let serverIpaddresses = [];

  @foreach ($serverconfig as $server)
  let serverMailRelay{{$loop->iteration}} = "{{ $server->ipaddress }}";
  serverIpaddresses.push(serverMailRelay{{$loop->iteration}});
  @endforeach

  // Connect to each server IP address
  serverIpaddresses.forEach(ip => {
      const serverAddress = 'ws://' + ip + ':8000/';
      //console.log('Connecting to WebSocket server:', serverAddress);
      const socket = new WebSocket(serverAddress);
      sockets.push(socket); // Store the socket object in an array
  });

  // Handle events for each socket if needed
  sockets.forEach((socket, index) => {
      getDataMailServer1(socket);
      socket.addEventListener('open', (event) => {
          //console.log('Connected to WebSocket server:', serverIpaddresses[index]);
      });

      socket.addEventListener('close', (event) => {
          //console.log('WebSocket connection closed:', serverIpaddresses[index]);
      });

      socket.addEventListener('error', (event) => {
          //console.error('WebSocket error occurred:', event);
      });

      socket.addEventListener('message', (event) => {
          //console.log('Message received from WebSocket server:', event.data);
      });
  });


  //let socket;
  //const serverAddress1 = 'ws://20.6.33.199:8000/';
  // Create a new WebSocket connection
  //socket = new WebSocket(serverAddress1);

  //getDataMailServer1();
  function getDataMailServer1(socket) {
      // Define commands to execute
      const commands = [
          'wc -l /opt/cluster/postfix/access_list | cut -d " " -f1',
          'wc -l /opt/cluster/postfix/sender_relay_maps | cut -d " " -f1',
          '/usr/sbin/sasldblistusers2 | wc -l',
          'mailq | grep -c "^[A-F0-9]"',
          'status=$(systemctl --no-pager status postfix | grep Active:); if echo "$status" | grep -q "active (exited) since"; then echo "Postfix is running"; elif echo "$status" | grep -q "inactive (dead) since"; then echo "Postfix is down"; else echo "Something is wrong"; fi',
          'status=$(systemctl --no-pager status fail2ban | grep Active:); if echo "$status" | grep -q "active (running) since"; then echo "Fail2ban is running"; elif echo "$status" | grep -q "inactive (dead) since"; then echo "Fail2ban is down"; else echo "Something is wrong"; fi',
          'status=$(systemctl --no-pager status webmin | grep Active:); if echo "$status" | grep -q "active (running) since"; then echo "Webmin is running"; elif echo "$status" | grep -q "inactive (dead) since"; then echo "Webmin is down"; else echo "Something is wrong"; fi',
          'status=$(systemctl --no-pager status apache2 | grep Active:); if echo "$status" | grep -q "active (running) since"; then echo "Apache2 is running"; elif echo "$status" | grep -q "inactive (dead) since"; then echo "Apache2 is down"; else echo "Something is wrong"; fi',
          // Add more commands as needed
      ];

      // Store the output of each command
      const outputs = [];

      // Create a new WebSocket connection
      //socket = new WebSocket(serverAddress);

      socket.addEventListener('open', (event) => {
          // Once connected, send commands to execute
          commands.forEach(command => {
              socket.send(command);
          });
      });

      socket.addEventListener('message', (event) => {
          // Handle messages received from the server
          const response = event.data;

          // Extract the output
          const output = extractOutput(response);
          if (output !== null) {
              //console.log(output);
              outputs.push(output); // Store the output in the array

              // If all commands have been executed and their outputs are received
              if (outputs.length === commands.length) {
                  // Process the outputs accordingly
                  $('#totalApplications').text(outputs[0]);
                  $('#totalDomains').text(outputs[1]);
                  $('#totalSASL').text(outputs[2]);
                  $('#mailQueueCount').text(outputs[3]);
                  $('#statusPostfix').text(outputs[4]);
                  $('#statusFail2ban').text(outputs[5]);
                  $('#statusWebmin').text(outputs[6]);
                  $('#statusApache2').text(outputs[7]);
                  // Add cases for other commands as needed
              }
          }
      });

      function extractOutput(response) {
          // Remove color codes and extra characters
          const cleanResponse = response.replace(/\[\d;?\d*m/g, '').trim();

          // Extract the outputs
          const outputMatch = cleanResponse.match(/command: (.*)/);

          // Initialize an array to store the outputs
          const outputs = [];

          // Check if both matches were successful and extract the outputs
          if (outputMatch && outputMatch.length > 1) {
              // Add the matched values to the array
              outputs.push(outputMatch[1]);

              // Return the array containing the outputs
              return outputs;
          } else {
              return null; // Or handle the case where output extraction fails
          }
      }
  }

</script>
@endpush
