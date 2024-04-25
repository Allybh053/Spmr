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
                                        {{-- {!! Form::select('server', $serverconfig,  $defaultServerId,  ['id'=>'server', 'name'=>'server', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!} --}}
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
          <h5>Top 5 List Ministry/Agency/SUK</h5>
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
          <h5>Top 5 List Department/Division/Unit</h5>
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
    <div class="col-xxl-12"><div class="card"><div class="card-header text-center p-5"><h5>Graf serial Mail Queue vs Time</h5></div><div class="card-body"><canvas id="mailQueueChart"></canvas></div></div></div>

    <div class="col-xxl-12 mt-5">
      <div class="card">
        <div class="card-header text-center p-5">
          <h5>View Current MailQueue Count</h5>
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
                                <a class="card lift h-100 mService" href="{{ route('manageService.index', ['server_id' => $server->id, 'server_type' => 'postfix']) }}">
                                    <div class="card-body cardService d-flex justify-content-center flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="me-3">
                                                <h5>Postfix</h5>
                                            </div>
                                            <img id="statusPostfixImage" src="{{ asset('assets/img/demo/offline.png') }}" alt="Postfix is down" style="width: 5rem">
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
                                <a class="card lift h-100 mService" href="{{ route('manageService.index', ['server_id' => $server->id , 'server_type' => 'apache']) }}">
                                    <div class="card-body cardService d-flex justify-content-center flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="me-3">
                                                <h5>Apache</h5>
                                            </div>
                                            <img id="statusApache2Image" src="{{ asset('assets/img/demo/offline.png') }}" alt="Apache2 is down" style="width: 5rem">
                                        </div>
                                        <p id="statusApache2" style="display: inline-block; margin-left: 5px;">--</p>
                                    </div>
                                    <!-- <div class="col-md-6 d-flex justify-content-center" style="padding-left: 150px;padding-bottom: 15px;"><div class="btn-group" role="group" aria-label="Button group with nested dropdown"><button type="button" class="btn btn-primary"><i class="fas fa-play"></i></button><button type="button" class="btn btn-danger"><i class="fas fa-stop"></i></button><button type="button" class="btn btn-warning"><i class="fas fa-redo"></i></button><button type="button" class="btn btn-info"><i class="fas fa-sync-alt"></i></button></div></div> -->
                                </a>
                            </div>
                            <div class="col-xl-4 mb-4">
                                <a class="card lift h-100 mService" href="{{ route('manageService.index', ['server_id' => $server->id, 'server_type' => 'webmin']) }}">
                                    <div class="card-body cardService d-flex justify-content-center flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="me-3">
                                                <h5>Webmin</h5>
                                            </div>
                                            <img id="statusWebminImage" src="{{ asset('assets/img/demo/offline.png') }}" alt="Webmin is down" style="width: 5rem">
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

    (Chart.defaults.global.defaultFontFamily = "Metropolis"),
    '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = "#858796";
    </script>
    <script>

$(document).ready(function() {

    $('a.mService').click(function(e) {
        e.preventDefault();
        var href = $(this).attr('href');

        $('body').fadeOut(300, function() {

            window.location.href = href;
        });
    });

    function getRandomColor() {
    var red = Math.floor(Math.random() * 128) + 128; // Range: 128-255
    var green = Math.floor(Math.random() * 128) + 128; // Range: 128-255
    var blue = Math.floor(Math.random() * 128) + 128; // Range: 128-255
    return 'rgb(' + red + ',' + green + ',' + blue + ')';
    }


    renderChartKementerian();
    function renderChartKementerian() {
        var ctx = document.getElementById('kementerianChart').getContext('2d');
        window.kementerianChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($filteredMinistries); ?>,
                datasets: <?php
                          $datasets = [];
                          foreach ($serverList as $server) {
                              $serverHostname = $server['hostname'];
                              $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
                              $data = collect($departmentCounts)->map(function ($data) use ($serverHostname) {
                                  return $data['postmasters'] === $serverHostname ? $data['count'] : 0;
                              })->values()->toArray(); // Convert to array
                              $datasets[] = [
                                  'label' => $serverHostname,
                                  'data' => $data,
                                  'backgroundColor' => $color, // Corrected syntax
                                  'borderColor' => '#4e73df',
                                  'borderWidth' => 1
                              ];
                      }
                          echo json_encode($datasets);
                          ?>
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    }
                }
            }
        });
    }

    renderChart();
    function renderChart() {
        var departmentChart = document.getElementById('departmentChart').getContext('2d');
        window.chart = new Chart(departmentChart, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($filteredDept); ?>,
                datasets: <?php
                          $datasets = [];
                          foreach ($serverList as $server) {
                              $serverHostname = $server['hostname'];
                              $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
                              $data = collect($departmentCounts2)->map(function ($data) use ($serverHostname) {
                                  return $data['postmasters'] === $serverHostname ? $data['count'] : 0;
                              })->values()->toArray(); // Convert to array
                              $datasets[] = [
                                  'label' => $serverHostname,
                                  'data' => $data,
                                  'backgroundColor' => $color, // Corrected syntax
                                  'borderColor' => '#4e73df',
                                  'borderWidth' => 1
                              ];
                          }
                          echo json_encode($datasets);
                          ?>
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    }
                }
            }
        });
    }

    // mailq
       // Extract data passed from the controller
       // Generate random color function
       function randomColor() {
    return '#' + Math.floor(Math.random() * 16777215).toString(16);
}

        // Generate random data for a server
        function generateServerData(serverName) {
            var data = [];
            var currentDate = new Date();
            currentDate.setHours(0, 0, 0, 0); // Set time to midnight

            // Generate data for each minute until current time
            for (var i = 0; i <= 10; i++) {
                    var timestamp = new Date(currentDate);
                    timestamp.setMinutes(i);
                    var formattedTimestamp = formatDate(timestamp); // Format timestamp
                    data.push({
                        x: formattedTimestamp,
                        y: Math.floor(Math.random() * 10) // Random count value
                    });
            }

            return {
                label: serverName,
                borderColor: randomColor(),
                backgroundColor: 'rgba(0, 0, 0, 0)', // Transparent background
                borderWidth: 1,
                data: data
            };
        }

// Function to format timestamp as "dd/mm/yyyy HH:mm:ss"
function formatDate(date) {
    var day = ('0' + date.getDate()).slice(-2);
    var month = ('0' + (date.getMonth() + 1)).slice(-2);
    var year = date.getFullYear();
    var hours = ('0' + date.getHours()).slice(-2);
    var minutes = ('0' + date.getMinutes()).slice(-2);
    var seconds = ('0' + date.getSeconds()).slice(-2);

    return day + '/' + month + '/' + year + ' ' + hours + ':' + minutes + ':' + seconds;
}

// Generate random data for multiple servers
var servers = {
    server1: generateServerData('Server 1'),
    server2: generateServerData('Server 2'),
    // Add more servers if needed
};

// Prepare datasets for Chart.js
var datasets = Object.values(servers);

// Create a new Chart.js instance
var ctx = document.getElementById('mailQueueChart').getContext('2d');
var labels = Array.from(Array(60).keys()).map(minute => {
    var timestamp = new Date();
    timestamp.setHours(0, minute, 0, 0);
    return formatDate(timestamp); // Format timestamp
});

var mailQueueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: datasets // Pass the formatted datasets array
    },
    options: {
        scales: {
            x: [{
                type: 'time',
                time: {
                    parser: 'DD/MM/YYYY HH:mm:ss', // Custom parser for the format
                    unit: 'minute', // Display unit as minute
                    displayFormats: {
                        minute: 'HH:mm' // Format for minute display
                    }
                },
                title: {
                    display: true,
                    text: 'Time'
                }
            }],
            y: [{
                title: {
                    display: true,
                    text: 'Mail Count'
                },
                beginAtZero: true
            }]
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom', // Adjust legend position if needed
                onHover: function (event, legendItem) {
                    event.target.style.cursor = 'pointer';
                }
            }
        }
    }
});



    });

      let sockets = [];
      let serverIpaddresses = [];

      @foreach ($serverconfig as $server)
      //let serverMailRelay{{$loop->iteration}} = "{{ $server->ipaddress }}";
      let serverMailRelay{{$loop->iteration}} = "{{ $server->hostname }}";
      serverIpaddresses.push(serverMailRelay{{$loop->iteration}});
      @endforeach

      serverIpaddresses.forEach(hostname => {
          //const serverAddress = 'ws://' + ip + ':8000/';
          const serverAddress = 'wss://' + hostname + '/wss/';

          const socket = new WebSocket(serverAddress);
          sockets.push(socket);
      });

      sockets.forEach((socket, index) => {
          getDataMailServer1(socket);
          socket.addEventListener('open', (event) => {

          });

          socket.addEventListener('close', (event) => {

          });

          socket.addEventListener('error', (event) => {

          });

          socket.addEventListener('message', (event) => {

          });
      });

      function getDataMailServer1(socket) {

          const commands = [
              'wc -l /opt/cluster/postfix/access_list | cut -d " " -f1',
              'wc -l /opt/cluster/postfix/sender_relay_maps | cut -d " " -f1',
              '/usr/sbin/sasldblistusers2 | wc -l',
              'mailq | grep -c "^[A-F0-9]"',
              'status=$(systemctl --no-pager status postfix | grep Active:); if echo "$status" | grep -q "active (exited) since"; then echo "Postfix is running"; elif echo "$status" | grep -q "inactive (dead) since"; then echo "Postfix is down"; else echo "Something is wrong"; fi',
              'status=$(systemctl --no-pager status fail2ban | grep Active:); if echo "$status" | grep -q "active (running) since"; then echo "Fail2ban is running"; elif echo "$status" | grep -q "inactive (dead) since"; then echo "Fail2ban is down"; else echo "Something is wrong"; fi',
              'status=$(systemctl --no-pager status webmin | grep Active:); if echo "$status" | grep -q "active (running) since"; then echo "Webmin is running"; elif echo "$status" | grep -q "inactive (dead) since"; then echo "Webmin is down"; else echo "Something is wrong"; fi',
              'status=$(systemctl --no-pager status apache2 | grep Active:); if echo "$status" | grep -q "active (running) since"; then echo "Apache2 is running"; elif echo "$status" | grep -q "inactive (dead) since"; then echo "Apache2 is down"; else echo "Something is wrong"; fi',

          ];

          const outputs = [];

          socket.addEventListener('open', (event) => {

              commands.forEach(command => {
                  socket.send(command);
              });
          });

          socket.addEventListener('message', (event) => {

              const response = event.data;

              const output = extractOutput(response);
              if (output !== null) {

                  outputs.push(output);

                  if (outputs.length > commands.length) {

                      $('#totalApplications').text(outputs[1]);
                      $('#totalDomains').text(outputs[3]);
                      $('#totalSASL').text(outputs[5]);
                      $('#mailQueueCount').text(outputs[7]);
                      $('#statusPostfix').text(outputs[9]);
                      $('#statusFail2ban').text(outputs[11]);
                      $('#statusWebmin').text(outputs[13]);
                      $('#statusApache2').text(outputs[15]);

                      var postfixStatus = outputs[9];
                      var postfixAlt;
                      if(postfixStatus == "Postfix is running"){
                        postfixStatus = "Online";
                        postfixAlt = "Postfix is running";
                      }else{
                        postfixStatus = "Offline";
                        postfixAlt = "Postfix is down";
                      }
                      var postfixImageSrc = postfixStatus === 'Online' ? 'assets/img/demo/online.png' : 'assets/img/demo/offline.png';
                      $('#statusPostfixImage').attr('src', postfixImageSrc).attr('alt', postfixAlt);

                      var Apache2Status = outputs[15];
                      var Apache2Alt;
                      if(Apache2Status == "Apache2 is running"){
                        Apache2Status = "Online";
                        Apache2Alt = "Apache2 is running";
                      }else{
                        Apache2Status = "Offline";
                        Apache2Alt = "Apache2 is down";
                      }
                      var Apache2ImageSrc = Apache2Status === 'Online' ? 'assets/img/demo/online.png' : 'assets/img/demo/offline.png';
                      $('#statusApache2Image').attr('src', Apache2ImageSrc).attr('alt', Apache2Alt);

                      var WebminStatus = outputs[13];
                      var WebminAlt;
                      if(WebminStatus == "Webmin is running"){
                        WebminStatus = "Online";
                        WebminAlt = "Webmin is running";
                      }else{
                        WebminStatus = "Offline";
                        WebminAlt = "Webmin is down";
                      }
                      var WebminImageSrc = WebminStatus === 'Online' ? 'assets/img/demo/online.png' : 'assets/img/demo/offline.png';
                      $('#statusWebminImage').attr('src', WebminImageSrc).attr('alt', WebminAlt);

                  }
              }
          });

          function extractOutput(response) {

              const cleanResponse = response.replace(/\[\d;?\d*m/g, '').trim();

              //const outputMatch = cleanResponse.match(/command: (.*)/);
              const outputMatch = cleanResponse.match(/\[.*?\]#\S (.*)/);

              const outputs = [];

              if (outputMatch && outputMatch.length > 1) {

                  outputs.push(outputMatch[1]);

                  return outputs;
              } else {
                  return null;
              }
          }
      }

    </script>
@endpush
