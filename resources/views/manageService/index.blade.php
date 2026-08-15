@extends('layouts.app')
@section('content')
<main>
  <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
      <div class="page-header-content">
        <div class="row align-items-center justify-content-between pt-3">
          <div class="col-auto mb-3">
            <h1 class="page-header-title">
              <div class="page-header-icon">
                <i data-feather="target"></i>
              </div> Manage Services
            </h1>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- Main page content-->
  <div class="container-fluid px-4">
          <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                    <div style="width: 100%; overflow-x: auto;">
                    <table id="spmrDatatable" class="display table table-striped table-bordered" cellspacing="0" width="60%">
                        @foreach ($serverList as $server)
                            @php
                                $col = (auth()->user()->can('manage_service_update')) ? 3 : 2;
                                $serverConfigCount = count($serverconfig);
                            @endphp
                            <thead >
                                @if($loop->index != 0)
                                <tr><th colspan="2">&nbsp;</th></tr>@endif
                                <tr  style="background-color: rgb(80, 160, 230);">
                                    <th colspan="{{ $col }}">
                                        <h5 style="text-align:left;">
                                            {{ $server['hostname']}}
                                            <p>{{ $server['ipaddress'] }}</p>
                                        </h5>
                                    </th>
                                </tr>
                            </thead>


                                @foreach ($server['subsDataHostname'] as $serverSubs)
                                @php
                                    $cleanSubhostname = preg_replace('/[^a-zA-Z0-9]/', '', $serverSubs['subhostname']);
                                @endphp
                                <tr>
                                        <thead>
                                        @if($loop->index != 0)
                                            <tr><th colspan="2">&nbsp;</th></tr>@endif
                                        <tr  style="background-color: rgb(197, 204, 210);">
                                            <th colspan="{{ $col }}">
                                                <h5 style="text-align:left;">
                                                    {{ $serverSubs['subhostname']}}
                                                    <p>{{ $serverSubs['subIpaddress'] }}</p>
                                                </h5>
                                            </th>
                                        </tr>
                                        </thead>
                                    <tbody>
                                    <tr class="postfix {{ $serverSubs['subsid'] }}">
                                        <th width="25%">
                                            <p class="mService" href="{{ route('manageService.index', ['server_id' => $serverSubs['subsid'], 'server_type' => 'postfix']) }}">
                                            <h6><b>Postfix</b></h6>
                                            </p>
                                        </th>
                                        <td width="35%">
                                            <h6 id="statusPostfix{{ $cleanSubhostname }}">--</h6>
                                            <img id="statusPostfixImage{{ $cleanSubhostname }}" src="{{ asset('assets/img/demo/offline.png') }}" alt="Postfix is down" style="width: 3rem">
                                        </td>
                                        @can('manage_service_update')
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                    <button type="button" id="postfixStart{{ $cleanSubhostname }}" class="btn btn-primary" data-value="{{ $serverSubs['subhostname'] }}"  onclick="startPostfixServices('{{ $cleanSubhostname }}')">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                    <button type="button" id="postfixStop{{ $cleanSubhostname }}" class="btn btn-danger" data-value="{{ $serverSubs['subhostname'] }}"  onclick="stopPostfixServices('{{ $cleanSubhostname }}')">
                                                        <i class="fas fa-stop"></i>
                                                    </button>
                                                    <button type="button" id="postfixRestart{{ $cleanSubhostname }}" class="btn btn-warning" data-value="{{ $serverSubs['subhostname'] }}"  onclick="restartPostfixServices('{{ $cleanSubhostname }}')">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                    <button type="button" id="shutdownPostfix{{ $cleanSubhostname }}" class="btn shutdown-btn" style="background: linear-gradient(90deg, #dc3545, #ff4d4d); color: white;" data-subhostname="{{ $serverSubs['subhostname'] }}">
                                                        <i class="fas fa-power-off me-1"></i>
                                                    </button>
                                                    <button type="button" id="rebootPostfix{{ $cleanSubhostname }}" class="btn reboot-btn" style="background: linear-gradient(90deg, #28a745, #34ce57); color: white;" data-subhostname="{{ $serverSubs['subhostname'] }}">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        @endcan
                                    </tr>
                                    <tr class="apache {{ $serverSubs['subsid'] }}">
                                        <th width="25%">
                                            <p class="mService" href="{{ route('manageService.index', ['server_id' => $serverSubs['subsid'], 'server_type' => 'apache']) }}">
                                            <h6><b>Apache</b></h6>
                                            </p>
                                        </th>
                                        <td width="35%">
                                            <h6 id="statusApache2{{ $cleanSubhostname }}">--</h6>
                                            <img id="statusApache2Image{{ $cleanSubhostname }}" src="{{ asset('assets/img/demo/offline.png') }}" alt="Apache2 is down" style="width: 3rem">
                                        </td>
                                        @can('manage_service_update')
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                    <button type="button" id="apacheStart{{ $cleanSubhostname }}" class="btn btn-primary" value="{{ $serverSubs['subhostname'] }}"  onclick="startApacheServices('{{ $cleanSubhostname }}')">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                    <button type="button" id="apacheStop{{ $cleanSubhostname }}" class="btn btn-danger" value="{{ $serverSubs['subhostname'] }}"  onclick="stopApacheServices('{{ $cleanSubhostname }}')">
                                                        <i class="fas fa-stop"></i>
                                                    </button>
                                                    <button type="button" id="apacheRestart{{ $cleanSubhostname }}" class="btn btn-warning" value="{{ $serverSubs['subhostname'] }}"  onclick="restartApacheServices('{{ $cleanSubhostname }}')">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                    <button type="button" id="shutdownApache{{ $cleanSubhostname }}" class="btn shutdown-btn" style="background: linear-gradient(90deg, #dc3545, #ff4d4d); color: white;" data-subhostname="{{ $serverSubs['subhostname'] }}">
                                                        <i class="fas fa-power-off me-1"></i>
                                                    </button>
                                                    <button type="button" id="rebootApache{{ $cleanSubhostname }}" class="btn reboot-btn" style="background: linear-gradient(90deg, #28a745, #34ce57); color: white;" data-subhostname="{{ $serverSubs['subhostname'] }}">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        @endcan
                                    </tr>
                                    <tr class="keepalived {{ $serverSubs['subsid'] }}">
                                        <th width="25%">
                                            <p class="mService" href="{{ route('manageService.index', ['server_id' => $serverSubs['subsid'], 'server_type' => 'keepalived']) }}">
                                            <h6><b>Keepalived</b></h6>
                                            </p>
                                        </th>
                                        <td width="35%">
                                            <h6 id="statusKeepalived{{ $cleanSubhostname }}">--</h6>
                                            <img id="statusKeepalivedImage{{ $cleanSubhostname }}" src="{{ asset('assets/img/demo/offline.png') }}" alt="Keepalived is down" style="width: 3rem">
                                        </td>
                                        @can('manage_service_update')
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                    <button type="button" id="keepalivedStart{{ $cleanSubhostname }}" class="btn btn-primary" value="{{ $serverSubs['subhostname'] }}"  onclick="startKeepalivedServices('{{ $cleanSubhostname }}')">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                    <button type="button" id="keepalivedStop{{ $cleanSubhostname }}" class="btn btn-danger" value="{{ $serverSubs['subhostname'] }}"  onclick="stopKeepalivedServices('{{ $cleanSubhostname }}')">
                                                        <i class="fas fa-stop"></i>
                                                    </button>
                                                    <button type="button" id="keepalivedRestart{{ $cleanSubhostname }}" class="btn btn-warning" value="{{ $serverSubs['subhostname'] }}"  onclick="restartKeepalivedServices('{{ $cleanSubhostname }}')">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                    <button type="button" id="shutdownKeepalived{{ $cleanSubhostname }}" class="btn shutdown-btn" style="background: linear-gradient(90deg, #dc3545, #ff4d4d); color: white;" data-subhostname="{{ $serverSubs['subhostname'] }}">
                                                        <i class="fas fa-power-off me-1"></i>
                                                    </button>
                                                    <button type="button" id="rebootKeepalived{{ $cleanSubhostname }}" class="btn reboot-btn" style="background: linear-gradient(90deg, #28a745, #34ce57); color: white;" data-subhostname="{{ $serverSubs['subhostname'] }}">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        @endcan
                                    </tr>
                            </tbody>
                                </tr>
                                @endforeach
                            </tbody>

                        @endforeach
                    </table>
                    </div>
                </div>
              </div>
          </div>
          <br>
        </div>
</main>
@endsection
@push('script')
<script>
    $(document).ready(function() {
        $('.selected-item').hide().fadeIn(500);

        // Blink animation for the selected item
        function blink(element) {
            $(element).fadeOut(300).fadeIn(300);
        }

        // Extract server type and server ID from the URL
        var url = window.location.href;
        var serverType = url.split('/').pop(); // Extract the last part of the URL
        var serverId = url.split('/')[url.split('/').length - 2]; // Extract the second last part of the URL

         // Call blink animation only on the selected item for 10 seconds
        var duration = 2000; // 10 seconds
        var interval = setInterval(function() {
            blink('.' + serverType + '.' + serverId);
        }, 600); // Blink every 600 milliseconds
        // Stop blinking after 10 seconds
        setTimeout(function() {
            clearInterval(interval);
        }, duration);
});
let sockets = [];
let serverIpaddresses = [];
let cleanlSubhostnames = [];

@foreach ($serverconfig as $server)
@php
    // Remove special characters from subhostname
    $cleanSubhostnamex = preg_replace('/[^a-zA-Z0-9]/', '', $server->subhostname);
@endphp

let serverMailRelay{{ $loop->index }} = "{{ $server->subhostname }}";
let cleanSubhostname{{ $loop->index }} = "{{ $cleanSubhostnamex }}";

serverIpaddresses.push(serverMailRelay{{ $loop->index }});
cleanlSubhostnames.push(cleanSubhostname{{ $loop->index }});
@endforeach
var commands = [
{
    command: '/opt/scripts/websocket/cmd2' + ' ' + '{{ $user->email }}' + ' ' + 'postfix',
    id: 'statusPostfix'
},
{
    command: '/opt/scripts/websocket/cmd2' + ' ' + '{{ $user->email }}' + ' ' + 'keepalived',
    id: 'statusWebmin'
},
{
    command: '/opt/scripts/websocket/cmd2' + ' ' + '{{ $user->email }}' + ' ' + 'apache2',
    id: 'statusApache2'
},
            {
                command: '/opt/scripts/websocket/cmd2 {{ $user->email }} shutdown',
                id: 'statusShutdown'
            },
            {
                command: '/opt/scripts/websocket/cmd2 {{ $user->email }} reboot',
                id: 'statusReboot'
            }
        ];

serverIpaddresses.forEach((subhostname, index) => {
const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/' + subhostname;
const socket = new WebSocket(serverAddress);
sockets.push(socket);

socket.addEventListener('open', () => {
    commands.forEach((commandObj, commandIndex) => {
        setTimeout(() => {
            socket.send(commandObj.command);
        }, commandIndex * 1000); // Delay each command by 1 second
    });
});

var outputs = [];
socket.addEventListener('message', (event) => {
    const response = event.data;
    if (response.includes('Postfix')) {
       updateStatus(response, 'statusPostfixImage'+cleanlSubhostnames[index],'statusPostfix'+cleanlSubhostnames[index]);
   } else if (response.includes('Keepalived')) {
    updateStatus(response, 'statusKeepalivedImage'+cleanlSubhostnames[index], 'statusKeepalived'+cleanlSubhostnames[index]);
    }
    else if (response.includes('Apache2')) {
        updateStatus(response, 'statusApache2Image'+cleanlSubhostnames[index], 'statusApache2'+cleanlSubhostnames[index]);
    }
});

socket.addEventListener('close', () => {
    // console.log('WebSocket connection closed with ' + subhostname);
});

socket.addEventListener('error', (error) => {
    console.error('WebSocket error with ' + subhostname, error);
});
});

function updateStatus(response, imageId,cleanlSubhostnames) {
$('#' +cleanlSubhostnames).text(response);
let statusText;
let altText;

if (response.includes('is running')) {
    statusText = 'Online';
    altText = response;
} else {
    statusText = 'Offline';
    altText = response;
}

let imageSrc = statusText === 'Online' ? '/assets/img/demo/online.png' : '/assets/img/demo/offline.png';
$('#' + imageId).attr('src', imageSrc).attr('alt', altText);
}

    // Postfix Controller //
    // Start //
    function startPostfixServices(subhostname) {
        const selectedServer = $('#postfixStart'+subhostname).data('value');
        const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/'+ selectedServer;
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = '/opt/scripts/websocket/cmd15' + ' ' + '{{ $user->email }}' + ' ' + 'start' + ' ' + 'postfix';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            const response = event.data;
            if (response.includes('Failed')) {
                alert("Failed to start Postfix services on " + selectedServer);
            } else {
                Swal.fire({
                    title: 'Succesfully starting Postfix..'+ selectedServer,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                    timer: 100000,
                    });
            }
            socket.close();
            location.reload();
        });
    }

    // Stop //
    function stopPostfixServices(subhostname) {
        const selectedServer = $('#postfixStop'+subhostname).data('value');
        const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/'+ selectedServer;
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = '/opt/scripts/websocket/cmd15' + ' ' + '{{ $user->email }}' + ' ' + 'stop' + ' ' + 'postfix';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            const response = event.data;
            if (response.includes('Failed')) {
                alert("Failed to stop Postfix services on " + selectedServer);
            } else {
                Swal.fire({
                    title: 'Postfix services successfully stopped on'+ selectedServer,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                    timer: 100000,
                    });
            }
            socket.close();
            location.reload();
        });
    }

    // Restart //
    function restartPostfixServices(subhostname) {
        const selectedServer = $('#postfixRestart'+subhostname).data('value');
        const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/'+ selectedServer;
       const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = '/opt/scripts/websocket/cmd15' + ' ' + '{{ $user->email }}' + ' ' + 'restart' + ' ' + 'postfix';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            const response = event.data;
            if (response.includes('Failed')) {
                alert("Failed to restart Postfix services on " + selectedServer);
            } else {
               Swal.fire({
                    title: 'Postfix services successfully restarting on'+ selectedServer,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                    timer: 100000,
                    });
            }
            socket.close();
            location.reload();
        });
        }

    // Apache2 Controller //
    // Start //
    function startApacheServices(subhostname) {
        const selectedServer = $('#apacheStart'+subhostname).val();
        const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/'+ selectedServer;
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = '/opt/scripts/websocket/cmd15' + ' ' + '{{ $user->email }}' + ' ' + 'start' + ' ' + 'apache2';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            const response = event.data;
            if (response.includes('Failed')) {
                alert("Failed to start Apache2 services on " + selectedServer);
            } else {
                Swal.fire({
                    title: 'Apache2 services successfully started on'+ selectedServer,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                    timer: 100000,
                    });
            }
            socket.close();
            location.reload();
        });
    }

    // Stop //
   function stopApacheServices(subhostname) {
        const selectedServer = $('#apacheStop'+subhostname).val();
        const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/'+ selectedServer;
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = '/opt/scripts/websocket/cmd15' + ' ' + '{{ $user->email }}' + ' ' + 'stop' + ' ' + 'apache2';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            const response = event.data;
            if (response.includes('Failed')) {
                alert("Failed to stop Apache2 services on " + selectedServer);
            } else {
                Swal.fire({
                    title: 'Apache2 services successfully stopped on '+ selectedServer,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                    timer: 100000,
                    });
            }
            socket.close();
            location.reload();
        });
        }

    // Restart //
    function restartApacheServices(subhostname) {
        const selectedServer = $('#apacheRestart'+subhostname).val();
        const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/'+ selectedServer;
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = '/opt/scripts/websocket/cmd15' + ' ' + '{{ $user->email }}' + ' ' + 'restart' + ' ' + 'apache2';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            const response = event.data;
            if (response.includes('Failed')) {
                alert("Failed to restart Apache2 services on " + selectedServer);
            } else {
                Swal.fire({
                    title: 'Apache2 services successfully restarting on '+ selectedServer,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                    timer: 100000,
                    });
            }
            socket.close();
            location.reload();
        });
        }

    // Keepalived Controller //
    // Start //
    function startKeepalivedServices(subhostname) {
        const selectedServer = $('#keepalivedStart'+subhostname).val();
        const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/'+ selectedServer;
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', () => {
            const command = '/opt/scripts/websocket/cmd15' + ' ' + '{{ $user->email }}' + ' ' + 'start' + ' ' + 'keepalived';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            const response = event.data;
            if (response.includes('Failed')) {
                alert("Failed to start Keepalived services on " + selectedServer);
            } else {
                Swal.fire({
                   title: 'Keepalived services successfully started on '+ selectedServer,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                    timer: 100000,
                });
            }
            socket.close();
            location.reload();
        });
    }

    // Stop //
    function stopKeepalivedServices(subhostname) {
        const selectedServer = $('#keepalivedStop'+subhostname).val();
        const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/'+ selectedServer;
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', () => {
            const command = '/opt/scripts/websocket/cmd15' + ' ' + '{{ $user->email }}' + ' ' + 'stop' + ' ' + 'keepalived';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            const response = event.data;
            if (response.includes('Failed')) {
                alert("Failed to stop Keepalived services on " + selectedServer);
            } else {
                Swal.fire({
                    title: 'Keepalived services successfully stopped on '+ selectedServer,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                    timer: 100000,
                });
            }
            socket.close();
            location.reload();
        });
    }

    // Restart //
    function restartKeepalivedServices(subhostname) {
        const selectedServer = $('#keepalivedRestart'+subhostname).val();
        const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/'+ selectedServer;
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', () => {
            const command = '/opt/scripts/websocket/cmd15' + ' ' + '{{ $user->email }}' + ' ' + 'restart' + ' ' + 'keepalived';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            const response = event.data;
            if (response.includes('Failed')) {
                alert("Failed to restart Keepalived services on " + selectedServer);
            } else {
                Swal.fire({
                    title: 'Keepalived services successfully restarting on '+ selectedServer,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                    timer: 100000,
                });
            }
            socket.close();
            location.reload();
        });
    }

        // Generic Shutdown Function
        function shutdownServer(subhostname) {
            const selectedServer = subhostname;
            const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/' + selectedServer;
            const socket = new WebSocket(serverAddress);

            socket.addEventListener('open', () => {
                const command = '/opt/scripts/websocket/cmd2' + ' ' + '{{ $user->email }}' + ' ' + 'shutdown';
                socket.send(command);
            });

            socket.addEventListener('message', (event) => {
                const response = event.data;
                if (response.includes('Failed')) {
                    alert("Failed to shutdown server " + selectedServer);
                } else {
                    Swal.fire({
                        title: 'Shutting down server ' + selectedServer,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        onBeforeOpen: () => Swal.showLoading(),
                        timer: 10000
                    });
                }
                socket.close();
                location.reload();
            });
        }

        // Generic Reboot Function
        function rebootServer(subhostname) {
            const selectedServer = subhostname;
            const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/' + selectedServer;
            const socket = new WebSocket(serverAddress);

            socket.addEventListener('open', () => {
                const command = '/opt/scripts/websocket/cmd2' + ' ' + '{{ $user->email }}' + ' ' + 'reboot';
                socket.send(command);
            });

            socket.addEventListener('message', (event) => {
                const response = event.data;
                if (response.includes('Failed')) {
                    alert("Failed to reboot server " + selectedServer);
                } else {
                    Swal.fire({
                        title: 'Rebooting server ' + selectedServer,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        onBeforeOpen: () => Swal.showLoading(),
                        timer: 10000
                    });
                }
                socket.close();
                location.reload();
            });
        }

        // Event listeners for all shutdown buttons
        document.querySelectorAll('.shutdown-btn').forEach(button => {
            button.addEventListener('click', function() {
                const subhostname = this.getAttribute('data-subhostname');
                if (confirm('Are you sure you want to SHUTDOWN this server?')) {
                    shutdownServer(subhostname);
                }
            });
        });

        // Event listeners for all reboot buttons
        document.querySelectorAll('.reboot-btn').forEach(button => {
            button.addEventListener('click', function() {
                const subhostname = this.getAttribute('data-subhostname');
                if (confirm('Are you sure you want to REBOOT this server?')) {
                    rebootServer(subhostname);
                }
            });
        });
    </script>
@endpush
