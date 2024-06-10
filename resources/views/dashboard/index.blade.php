@extends('layouts.app')
@section('content')
<header class="py-10 mb-4 bg-gradient-primary-to-secondary">
  <div class="container-xl px-4">
    <div class="text-center">
      <h1 class="text-white">{{ trans('indexMessage.dashboard.welcome') }}</h1>
      <p class="lead mb-0 text-white-50">{{ trans('indexMessage.dashboard.dashboarMailRelay') }}</p>
    </div>
  </div>
</header>
<!-- Main page content-->
<div class="container-fluid px-4 mt-n10">
  <div class="row mb-4">
    <div class="col-xxl-12">
      <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <div style="width: 100%; overflow-x: auto; height: 355px;">
                <table id="spmrDatatable" class="display table table-striped table-bordered" cellspacing="0" width="60%">
                    @foreach ($serverList as $server)
                    @php
                        $col = (auth()->user()->can('manage_service_update')) ? 3 : 2;
                    @endphp
                    <thead>
                        @if($loop->index != 0)
                        <tr><th colspan="2">&nbsp;</th></tr>@endif
                        <tr  style="background-color: rgb(11, 135, 244);">
                            <th colspan="2">
                                <h5 style="text-align:left; color:white; font-weight: bold;">
                                   Virtual Server: {{ $server['hostname']}}
                                    <p>{{ $server['ipaddress'] }}</p>
                                </h5>
                            </th>
                        </tr>
                    </thead>
                    @foreach ($server['subsDataHostname'] as $serverSubs)
                    <tr>
                        <thead>
                            @if($loop->index != 0)
                            <tr><th colspan="2">&nbsp;</th></tr>@endif
                            <tr  style="background-color: rgb(179, 189, 183);">
                                <th colspan="{{ $col }}">
                                    <h5 style="text-align:left; font-weight: bold;">
                                        Primary Server: {{ $serverSubs['subhostname']}}
                                        <p>{{ $serverSubs['subIpaddress'] }}</p>
                                    </h5>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @can('total_application_registered')
                                <tr>
                                    <th width="45%"><h6><b>Total Application Registered</b></h6></th>
                                    <td width="15%"><h6>{{ $serverSubs['subTotalApplicationRegistered'] ?? '0' }}</h6></td>
                                </tr>
                            @endcan
                            @can('total_domain_registered')
                                <tr>
                                    <th width="45%"><h6><b>Total Domain Registered</b></h6>
                                        <div class="small text-muted">Updated every 30 minutes</div>
                                    </th>
                                    <td width="15%"><h6>{{ $serverSubs['subTotalDomain'] ?? '0' }}</h6></td>
                                </tr>
                            @endcan
                            @can('number_of_registered_sasl')
                                <tr>
                                    <th width="45%"><h6><b>Total SASL based (SMTP relay request form registered)</b></h6></th>
                                    {{-- <td width="15%"><h6>{{$server['totalSasl'] ?? '0'}}</h6></td> --}}
                                    <td width="15%"><h6>{{ $serverSubs['subTotalSasl'] ?? '0' }}</h6></td>
                                </tr>
                            @endcan
                            @can('total_ip_based_registered')
                                <tr>
                                    <th width="45%"><h6><b>Total IP based (SMTP relay request form registered)</b></h6></th>
                                    {{-- <td width="15%"><h6>{{$server['totalIpBased'] ?? '0'}}</h6></td> --}}
                                    <td width="15%"><h6>{{ $serverSubs['subTotalIpBased'] ?? '0' }}</h6></td>
                                </tr>
                            @endcan
                        </tbody>
                    </tr>
                    @endforeach


                    @endforeach
                </table>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    @can('top_5_list_ministry')
    <div class="col-xxl-6 mb-4">
        <div class="card" style="height: 455px;">
            <div class="card-header text-center p-3">
                <h5>TOP 5 LIST OF MINISTRY/AGENCY/SUK</h5>
            </div>
            <div class="card-body" style="overflow-y: auto;">
                @foreach($serverListAgencies as $server)
                    <div class="mb-4">
                        <h6>{{ $server['hostname'] }}</h6>
                        <p>{{ $server['ipAddress'] }}</p>
                        <canvas id="chart-{{ $loop->index }}"  width="729" height="217"></canvas>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endcan

    @can('view_current_mail_queue_count')
    <div class="col-xxl-6 mb-4">
        <div class="card">
            <div class="card-header text-center p-3">
                <h5>View Current MailQueue Count</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div style="width: 100%; overflow-x: auto; height: 355px;">
                    <table id="spmrDatatable" class="display table table-striped table-bordered" cellspacing="0" width="60%">
                        <tbody>
                            @foreach ($serverconfig as $index => $server)
                                <tr>
                                    <th width="45%"><h6>{{ $server->hostname }}</h6></th>
                                    <td id="mailQueueCount{{ $index }}"  width="15%"><h6>--</h6></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

  </div>
  <div class="row">
    @can('serial_mail_queue_chart')
    <div class="col-xxl-12">
        <div class="card">
            <div class="card-header text-center p-3">
                <h5>Graph Serial Mail Queue vs Time</h5>
            </div>
            <div class="card-body">
                <canvas id="mailQueueChart"></canvas>
            </div>
        </div>
    </div>
    @endcan


</div>
@can('realtime_service_status')
<div class="row  mt-5">
    <div class="col-xxl-12">
      <div class="card">
        <div class="card-header text-center p-3">
          <h5>RealTime Service Status</h5> @php $status = 'Online'; @endphp
        </div>
        <div class="card-body mainRealtime"  style="overflow-y: auto;">

            <div class="table-responsive">
                <div style="width: 100%; overflow-x: auto; height: 355px;">
                <table id="spmrDatatable" class="display table table-striped table-bordered" cellspacing="0" width="60%">
                    @foreach ($serverconfig as $server)
                    <thead>
                        @if($loop->index != 0)
                        <tr><th colspan="2">&nbsp;</th></tr>@endif
                        <tr  style="background-color: rgb(197, 204, 210);">
                            <th colspan="2">
                                <h5 style="text-align:left;">
                                    {{ $server['hostname']}}
                                    <p>{{ $server['ipAddress'] }}</p>
                                </h5>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                <th width="25%"><a class="mService" href="{{ route('manageService.index', ['server_id' => $server->id, 'server_type' => 'postfix']) }}">
                                    <h6><b>Postfix</b></h6>
                                </a></th>
                                <td width="35%"><h6 id="statusPostfix{{ $loop->index }}">--</h6></td>
                            </tr>
                            <tr>
                                <th width="25%"><a class="mService" href="{{ route('manageService.index', ['server_id' => $server->id , 'server_type' => 'apache']) }}">
                                    <h6><b>Apache</b></h6>
                                    </a>
                                </th>
                                <td width="35%"><h6 id="statusApache2{{ $loop->index }}">--</h6></td>
                            </tr>
                            <tr>
                                <th width="25%"><a class="mService" href="{{ route('manageService.index', ['server_id' => $server->id, 'server_type' => 'webmin']) }}">
                                    <h6><b>Webmin</b></h6>
                                </a></th>
                                <td width="35%"><h6 id="statusWebmin{{ $loop->index }}">--</h6></td>
                            </tr>
                    </tbody>
                    @endforeach
                </table>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endcan

@endsection
@push('script')
<script src="{{ asset('js/vendor/Chart.min.js') }}"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    $('#spmrDatatable').DataTable({
        "order": [[1, 'desc']], // Initial sorting on the second column (Mail Queue Count) in descending order
        "language": {
            "emptyTable": "No data available in table" // Custom message when table is empty
        }
    });

    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    const serverListAgencies = @json($serverListAgencies);

    serverListAgencies.forEach((server, index) => {
        const chartId = `chart-${index}`;
        const canvasElement = document.getElementById(chartId);

        // Ensure the canvas element exists
        if (!canvasElement) {
            console.error(`Canvas element with id ${chartId} not found.`);
            return;
        }

        const ctx = canvasElement.getContext('2d');
        const labels = server.agencies.map(item => item.agency); // Using 'agency' for x-axis labels

        const datasets = server.agencies.map(item => ({
            label: item.agencies_name, // Using 'agencies_name' for legend
            data: labels.map((label, j) => label === item.agency ? item.totalAgencies : 0),
            backgroundColor: getRandomColor(),
            borderColor: getRandomColor(),
            borderWidth: 1,
            barPercentage: 1.0,
            categoryPercentage: 0.5
        }));

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            scales: {
                y: {
                    ticks: {
                        beginAtZero: true
                    }
                },
                x: {
                    display: false, // Hide x-axis
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        display: false // Ensure ticks are also hidden
                    }
                }
            },
                responsive: true,
                plugins: {
                    legend: {
                        display: true // Hide legend
                    },
                    title: {
                        display: true,
                        text: 'Agency Data'
                    }
                }

        });
    });


    });
</script>

<script>

var data = [];
var sockets = [];
var serverIpaddresses = [];
var serverIpaddresses1 = [];
var labels = [];
var datasets = {};
$(document).ready(function() {

$('a.mService').click(function(e) {
    e.preventDefault();
    var href = $(this).attr('href');

    $('body').fadeOut(300, function() {
        window.location.href = href;
    });
});

function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}


function randomColor() {
    return '#' + Math.floor(Math.random() * 16777215).toString(16);
}

function generateServerData(serverName, cleanResponse) {

    if (cleanResponse) {
        const cleanRes = cleanResponse.replace(/(\\.||\[.*?#\d+ )/g, '').trim();
        const lines = cleanRes.split('\n');
        const filteredLines = lines.filter(line => !line.startsWith('[root@postmaster1a:/opt/cluster/postfix]#'));
        const numericLines = filteredLines.filter(line => /^\d/.test(line));

        numericLines.forEach((line, index) => {
            const [timestamp, value] = line.split(',');
            const formattedTimestamp = timestamp.replace(' ', ' ').replace(/-/g, '/');
            data.push({
                x: formattedTimestamp,
                y: parseInt(value)
            });
        });

    } else {
        //console.error("cleanResponse is undefined");
    }

    var currentDate = new Date();
    currentDate.setHours(0, 0, 0, 0); // Set time to midnight

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



@foreach ($serverconfig as $server)
var serverMailRelay1{{$loop->iteration}} = {
    subhostname: "{{ $server->subhostname }}",
    hostname: "{{ $server->hostname }}"};
serverIpaddresses1.push(serverMailRelay1{{$loop->iteration}});
@endforeach

var servers = {};

// Function to handle WebSocket connection and data processing
function connectAndProcess(hostname1, index) {
    //console.log('Connecting to:', hostname1.subhostname, 'with hostname:', hostname1.hostname);

    const serverAddress1 = 'wss://spmr.mygovuc.gov.my/' + hostname1.subhostname;
    const socket1 = new WebSocket(serverAddress1);

    socket1.onopen = function() {
        //console.log("Connected to " + serverAddress1);

        // Send any initial message if needed
        // socket.send("Initial message or request data");
    };

    socket1.onmessage = function(event1) {
        var message = event1.data.trim();

        // Check if the message matches the desired format
        if (!/^\d{4}-\d{2}-\d{2} \d{2}:\d{2},\d+$/.test(message)) {
            // console.log("Skipping message from " + serverAddress1 + ":", message);
            return; // Skip processing if the message does not match the format
        }
        const [timestamp, value] = message.split(',');

         // Push timestamp to labels array if it's not already present
         if (!labels.includes(timestamp)) {
            labels.push(timestamp);
        }

        // Add value to the dataset corresponding to the hostname
        if (!datasets[hostname1.hostname]) {
            datasets[hostname1.hostname] = {
                label: hostname1.hostname,
                data: [],
                borderColor: getRandomColor(), // Random color for the line
                borderWidth: 2, // Bold line
                fill: false
            };
        }

        datasets[hostname1.hostname].data.push({
            x: timestamp,
            y: parseInt(value)
        });

        // Update chart data
        mailQueueChart.data.labels = labels;
        mailQueueChart.data.datasets = Object.values(datasets); // Convert object to array
        mailQueueChart.update();

    };

    socket1.onerror = function(error) {
        //console.error("WebSocket Error for " + serverAddress1 + ": ", error);
    };

    socket1.onclose = function() {
        //console.log("WebSocket connection closed for " + serverAddress1);
    };

    sockets.push(socket1);
}

// Iterate over the serverIpaddresses array and generate server data
serverIpaddresses1.forEach(function(hostname, index) {
    connectAndProcess(hostname, index);
});

var ctx = document.getElementById('mailQueueChart').getContext('2d');
var mailQueueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: datasets
    },
    options: {
        scales: {
            x: [{
                type: 'time',
                time: {
                    parser: 'YYYY-MM-DD HH:mm', // Adjust parser for the format
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
                    text: 'Value'
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

@foreach ($serverconfig as $server)
let serverMailRelay{{$loop->iteration}} = "{{ $server->subhostname }}";
serverIpaddresses.push(serverMailRelay{{$loop->iteration}});
@endforeach

serverIpaddresses.forEach(hostname => {
    // const serverAddress = 'wss://' + hostname + '/wss/';
    const serverAddress = 'wss://spmr.mygovuc.gov.my/' + hostname;

    const socket = new WebSocket(serverAddress);
    // console.log("serverAddress-> "+ serverAddress);
    sockets.push(socket);
});

var commands = [
    {
        command: 'mailq | grep -c "^[A-F0-9]"',
        id: 'mailQueueCount'
    },
    {
        command: 'status=$(systemctl --no-pager status postfix | grep Active:); if echo "$status" | grep -q "active (exited) since"; then echo "Postfix is running"; elif echo "$status" | grep -q "inactive (dead) since"; then echo "Postfix is down"; else echo "Something is wrong"; fi',
        id: 'statusPostfix'
    },
    {
        command: 'status=$(systemctl --no-pager status fail2ban | grep Active:); if echo "$status" | grep -q "active (running) since"; then echo "Fail2ban is running"; elif echo "$status" | grep -q "inactive (dead) since"; then echo "Fail2ban is down"; else echo "Something is wrong"; fi',
        id: 'statusFail2ban'
    },
    {
        command: 'status=$(systemctl --no-pager status webmin | grep Active:); if echo "$status" | grep -q "active (running) since"; then echo "Webmin is running"; elif echo "$status" | grep -q "inactive (dead) since"; then echo "Webmin is down"; else echo "Something is wrong"; fi',
        id: 'statusWebmin'
    },
    {
        command: 'status=$(systemctl --no-pager status apache2 | grep Active:); if echo "$status" | grep -q "active (running) since"; then echo "Apache2 is running"; elif echo "$status" | grep -q "inactive (dead)"; then echo "Apache2 is down"; else echo "Something is wrong"; fi',
        id: 'statusApache2'
    },
    {
        command: '/usr/bin/tail -50 /data/collections/spmr/count_mailq.txt'
    },
];

sockets.forEach((socket, index) => {

    socket.addEventListener('open', (event) => {
        //console.log("WebSocket connection opened to " + socket.url);
        // Function to send each command with delay
        commands.forEach((commandObj, commandIndex) => {
            setTimeout(() => {
                //console.log("Sending command to : " + commandObj.command);
                socket.send(commandObj.command);
            }, commandIndex * 1000); // Delay each command by 1 second
        });

    });

    socket.addEventListener('close', (event) => {
        //console.log("WebSocket connection closed to " + socket.url);
    });

    socket.addEventListener('error', (event) => {
        //console.error("WebSocket error occurred with " + socket.url + ": " + event);
    });
    var outputs = [];
    socket.addEventListener('message', (event) => {
        //console.log("Message received from " + socket.url + ": " + event.data);
        const response = event.data;
        outputs.push(response);
        // Update the corresponding element based on the response
            $('#mailQueueCount' + index).text(outputs[0]);

            $('#statusPostfix' + index).text(outputs[1]);

            var postfixStatus = outputs[1];
            var postfixAlt;
            if(postfixStatus == "Postfix is running"){
                postfixStatus = "Online";
                postfixAlt = "Postfix is running";
            }else{
                postfixStatus = "Offline";
                postfixAlt = "Postfix is down";
            }
            var postfixImageSrc = postfixStatus === 'Online' ? 'assets/img/demo/online.png' : 'assets/img/demo/offline.png';

            $('#statusPostfixImage'+ index).attr('src', postfixImageSrc).attr('alt', postfixAlt);

            $('#statusFail2ban' + index).text(outputs[2]);

            $('#statusWebmin' + index).text(outputs[3]);
            var WebminStatus = outputs[3];
            var WebminAlt;
            if(WebminStatus == "Webmin is running"){
                WebminStatus = "Online";
                WebminAlt = "Webmin is running";
            }else{
                WebminStatus = "Offline";
                WebminAlt = "Webmin is down";
            }
            var WebminImageSrc = WebminStatus === 'Online' ? 'assets/img/demo/online.png' : 'assets/img/demo/offline.png';
            $('#statusWebminImage'+ index).attr('src', WebminImageSrc).attr('alt', WebminAlt);

            $('#statusApache2' + index).text(outputs[4]);

            var Apache2Status = outputs[4];
                var Apache2Alt;
                if(Apache2Status == "Apache2 is running"){
                    Apache2Status = "Online";
                    Apache2Alt = "Apache2 is running";
                }else{
                    Apache2Status = "Offline";
                    Apache2Alt = "Apache2 is down";
                }
                var Apache2ImageSrc = Apache2Status === 'Online' ? 'assets/img/demo/online.png' : 'assets/img/demo/offline.png';
                $('#statusApache2Image'+ index).attr('src', Apache2ImageSrc).attr('alt', Apache2Alt);

                // console.log(outputs[5]);
                extractOutput(response);

    });
});

function extractOutput(response) {

const cleanResponse = response.replace(/\[\d;?\d*m/g, '').trim();
generateServerData('Mailq Server', cleanResponse);

const outputMatch = cleanResponse.match(/\[.*?\]#\S (.*)/);

const outputs = [];

if (outputMatch && outputMatch.length > 1) {

    outputs.push(outputMatch[1]);

    return outputs;
} else {
    return null;
}
}

function updateChart() {
    mailQueueChart.update();
}

});

</script>
@endpush
