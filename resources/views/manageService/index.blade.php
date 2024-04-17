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
  <!-- Start Loop By Server Here -->
    <div class="container-fluid px-4">
        @foreach ($serverconfig as $server)
        <div class="card">
            <div class="card-header text-center p-5">
                <h5 style="text-align:left;">
                    {{ $server->hostname }}
                    <p>{{ $server->ipaddress }}</p>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Start Loop Server Here --}}
                    <div class="col-xxl-12 ">
                        @php $status = 'Online'; @endphp
                        <div class="card-body mainRealtime" style="min-height: auto; max-height: 800px;overflow-y: auto;">
                            <div class="row">
                                <div class="col-xl-6 mb-4 selected-item postfix serverId-1">
                                    <a class="card lift h-100">
                                        <div class="card-body cardService d-flex justify-content-center flex-column">
                                            <div class="d-flex align-items-center justify-content-between">
                                              <div class="me-3">
                                                <h5>Postfix</h5>
                                              </div>
                                                <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">
                                            </div>
                                            <p id="statusPostfix" style="display: inline-block; margin-left: 5px;">--</p>
                                        </div>
                                        {{-- @can('manage_service_update') --}}
                                        <div class="col-md-6 d-flex justify-content-center" style="padding-left: 150px;padding-bottom: 15px;">
                                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                <button type="button" id="postfixStart" class="btn btn-primary" value="{{ $server->hostname }}">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <button type="button" id="postfixStop" class="btn btn-danger" value="{{ $server->hostname }}">
                                                    <i class="fas fa-stop"></i>
                                                </button>
                                                <button type="button" id="postfixRestart" class="btn btn-warning" value="{{ $server->hostname }}">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                                <button type="button" id="postfixReload" class="btn btn-info" value="{{ $server->hostname }}">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- @endcan  --}}
                                    </a>
                                </div>
                                {{--<div class="col-xl-6 mb-4 selected-item fail2ban serverId-1">
                                    <a class="card lift h-100">
                                        <div class="card-body cardService d-flex justify-content-center flex-column">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="me-3">
                                                    <h5>Fail-To-Ban</h5>
                                                </div>
                                                <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">
                                            </div>
                                            <p id="statusFail2ban" style="display: inline-block; margin-left: 5px;">--</p>
                                        </div>
                                        --}}{{-- @can('manage_service_update') --}}{{--
                                        <div class="col-md-6 d-flex justify-content-center" style="padding-left: 150px;padding-bottom: 15px;">
                                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                <button type="button" class="btn btn-primary">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger">
                                                    <i class="fas fa-stop"></i>
                                                </button>
                                                <button type="button" class="btn btn-warning">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                                <button type="button" class="btn btn-info">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                        --}}{{-- @endcan  --}}{{--
                                    </a>
                                </div>--}}
                                <div class="col-xl-6 mb-4 selected-item apache serverId-1">
                                    <a class="card lift h-100">
                                        <div class="card-body cardService d-flex justify-content-center flex-column">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="me-3">
                                                    <h5>Apache</h5>
                                                </div>
                                                <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">
                                            </div>
                                            <p id="statusApache2" style="display: inline-block; margin-left: 5px;">--</p>
                                        </div>
                                        {{-- @can('manage_service_update') --}}
                                        <div class="col-md-6 d-flex justify-content-center" style="padding-left: 150px;padding-bottom: 15px;">
                                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                <button type="button" id="apacheStart" class="btn btn-primary" value="{{ $server->hostname }}">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <button type="button" id="apacheStop" class="btn btn-danger" value="{{ $server->hostname }}">
                                                    <i class="fas fa-stop"></i>
                                                </button>
                                                <button type="button" id="apacheRestart" class="btn btn-warning" value="{{ $server->hostname }}">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                                <button type="button" id="apacheReload" class="btn btn-info" value="{{ $server->hostname }}">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- @endcan --}}
                                    </a>
                                </div>
                                <div class="col-xl-6 mb-4 selected-item webmin serverId-1">
                                    <a class="card lift h-100">
                                        <div class="card-body cardService d-flex justify-content-center flex-column">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="me-3">
                                                    <h5>Webmin</h5>
                                                </div>
                                                <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">
                                            </div>
                                            <p id="statusWebmin" style="display: inline-block; margin-left: 5px;">--</p>
                                        </div>
                                        {{-- @can('manage_service_update') --}}
                                        <div class="col-md-6 d-flex justify-content-center" style="padding-left: 150px;padding-bottom: 15px;">
                                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                <button type="button" id="webminStart" class="btn btn-primary" value="{{ $server->hostname }}">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <button type="button" id="webminStop" class="btn btn-danger" value="{{ $server->hostname }}">
                                                    <i class="fas fa-stop"></i>
                                                </button>
                                                <button type="button" id="webminRestart" class="btn btn-warning" value="{{ $server->hostname }}">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                                <button type="button" id="webminReload" class="btn btn-info" value="{{ $server->hostname }}">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- @endcan --}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End Loop Server Here --}}
                </div>
            </div>
        </div>
            <br>
        @endforeach
    </div>

    <!-- End Loop By Server Here -->
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
            blink('.' + serverType + '.serverId-' + serverId);
        }, 600); // Blink every 600 milliseconds


        //console.log(serverType);
        //console.log(serverId);

        // Stop blinking after 10 seconds
        setTimeout(function() {
            clearInterval(interval);
        }, duration);



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
        getStatusServicesMailServer(socket);
        socket.addEventListener('open', (event) => {

        });

        socket.addEventListener('close', (event) => {

        });

        socket.addEventListener('error', (event) => {

        });

        socket.addEventListener('message', (event) => {

        });
    });

    function getStatusServicesMailServer(socket) {

        const commands = [
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

                if (outputs.length === commands.length) {

                    $('#statusPostfix').text(outputs[0]);
                    $('#statusFail2ban').text(outputs[1]);
                    $('#statusWebmin').text(outputs[2]);
                    $('#statusApache2').text(outputs[3]);

                }
            }
        });

        function extractOutput(response) {

            const cleanResponse = response.replace(/\[\d;?\d*m/g, '').trim();

            const outputMatch = cleanResponse.match(/command: (.*)/);

            const outputs = [];

            if (outputMatch && outputMatch.length > 1) {

                outputs.push(outputMatch[1]);

                return outputs;
            } else {
                return null;
            }
        }
    }

    // Postfix Controller //
    // Start //
    $('#postfixStart').click(function(e) {
        let text = "Are you sure you want to START Postfix services?";
        if (confirm(text)) {
            startPostfixServices();
        }
    });
    function startPostfixServices() {
        const selectedServer = $('#postfixStart').val();
        const serverAddress = 'wss://' + selectedServer + '/wss/';
        console.log(serverAddress);
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = 'systemctl --no-pager start postfix';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            //alert("Postfix services successfully started on " + selectedServer);
            const response = event.data;
            console.log(response);
            if (response.includes('Failed')) {
                alert("Failed to start Postfix services on " + selectedServer);
            } else {
                alert("Postfix services successfully started on " + selectedServer);
            }
            socket.close();
            location.reload();
        });
    }
    // Stop //
    $('#postfixStop').click(function(e) {
        let text = "Are you sure you want to STOP Postfix services?";
        if (confirm(text)) {
            stopPostfixServices();
        }
    });
    function stopPostfixServices() {
        const selectedServer = $('#postfixStop').val();
        const serverAddress = 'wss://' + selectedServer + '/wss/';
        console.log(serverAddress);
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = 'systemctl --no-pager stop postfix';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            //alert("Postfix services successfully stopped on " + selectedServer);
            const response = event.data;
            console.log(response);
            if (response.includes('Failed')) {
                alert("Failed to stop Postfix services on " + selectedServer);
            } else {
                alert("Postfix services successfully stopped on " + selectedServer);
            }
            socket.close();
            location.reload();
        });
    }
    // Restart //
    $('#postfixRestart').click(function(e) {
        let text = "Are you sure you want to RESTART Postfix services?";
        if (confirm(text)) {
            restartPostfixServices();
        }
    });
    function restartPostfixServices() {
        const selectedServer = $('#postfixRestart').val();
        const serverAddress = 'wss://' + selectedServer + '/wss/';
        console.log(serverAddress);
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = 'systemctl --no-pager restart postfix';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            //alert("Postfix services successfully restarting on " + selectedServer);
            const response = event.data;
            console.log(response);
            if (response.includes('Failed')) {
                alert("Failed to restart Postfix services on " + selectedServer);
            } else {
                alert("Postfix services successfully restarting on " + selectedServer);
            }
            socket.close();
            location.reload();
        });
    }
    // Reload //
    $('#postfixReload').click(function(e) {
        let text = "Are you sure you want to RELOAD Postfix services?";
        if (confirm(text)) {
            reloadPostfixServices();
        }
    });
    function reloadPostfixServices() {
        const selectedServer = $('#postfixReload').val();
        const serverAddress = 'wss://' + selectedServer + '/wss/';
        console.log(serverAddress);
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = 'systemctl --no-pager reload postfix';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            //alert("Postfix services successfully reloading on " + selectedServer);
            const response = event.data;
            console.log(response);
            if (response.includes('Failed')) {
                alert("Failed to reload Postfix services on " + selectedServer);
            } else {
                alert("Postfix services successfully reloading on " + selectedServer);
            }
            socket.close();
            location.reload();
        });
    }

    // Fail2ban Controller //
    //to do


    // Apache2 Controller //
    // Start //
    $('#apacheStart').click(function(e) {
        let text = "Are you sure you want to START Apache2 services?";
        if (confirm(text)) {
            startApacheServices();
        }
    });
    function startApacheServices() {
        const selectedServer = $('#apacheStart').val();
        const serverAddress = 'wss://' + selectedServer + '/wss/';
        console.log(serverAddress);
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = 'systemctl --no-pager start apache2';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            //alert("Apache2 services successfully started on " + selectedServer);
            const response = event.data;
            console.log(response);
            if (response.includes('Failed')) {
                alert("Failed to start Apache2 services on " + selectedServer);
            } else {
                alert("Apache2 services successfully started on " + selectedServer);
            }
            socket.close();
            location.reload();
        });
    }
    // Stop //
    $('#apacheStop').click(function(e) {
        let text = "Are you sure you want to STOP Apache2 services?";
        if (confirm(text)) {
            stopApacheServices();
        }
    });
    function stopApacheServices() {
        const selectedServer = $('#apacheStop').val();
        const serverAddress = 'wss://' + selectedServer + '/wss/';
        console.log(serverAddress);
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = 'systemctl --no-pager stop apache2';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            //alert("Apache2 services successfully stopped on " + selectedServer);
            const response = event.data;
            console.log(response);
            if (response.includes('Failed')) {
                alert("Failed to stop Apache2 services on " + selectedServer);
            } else {
                alert("Apache2 services successfully stopped on " + selectedServer);
            }
            socket.close();
            location.reload();
        });
    }
    // Restart //
    $('#apacheRestart').click(function(e) {
        let text = "Are you sure you want to RESTART Apache2 services?";
        if (confirm(text)) {
            restartApacheServices();
        }
    });
    function restartApacheServices() {
        const selectedServer = $('#apacheRestart').val();
        const serverAddress = 'wss://' + selectedServer + '/wss/';
        console.log(serverAddress);
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = 'systemctl --no-pager restart apache2';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            //alert("Apache2 services successfully restarting on " + selectedServer);
            const response = event.data;
            console.log(response);
            if (response.includes('Failed')) {
                alert("Failed to restart Apache2 services on " + selectedServer);
            } else {
                alert("Apache2 services successfully restarting on " + selectedServer);
            }
            socket.close();
            location.reload();
        });
    }
    // Reload //
    $('#apacheReload').click(function(e) {
        let text = "Are you sure you want to RELOAD Apache2 services?";
        if (confirm(text)) {
            reloadApacheServices();
        }
    });
    function reloadApacheServices() {
        const selectedServer = $('#apacheReload').val();
        const serverAddress = 'wss://' + selectedServer + '/wss/';
        console.log(serverAddress);
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = 'systemctl --no-pager reload apache2';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            //alert("Apache2 services successfully reloading on " + selectedServer);
            const response = event.data;
            console.log(response);
            if (response.includes('Failed')) {
                alert("Failed to reload Apache2 services on " + selectedServer);
            } else {
                alert("Apache2 services successfully reloading on " + selectedServer);
            }
            socket.close();
            location.reload();
        });
    }


    // Webmin Controller //
    // Start //
    $('#webminStart').click(function(e) {
        let text = "Are you sure you want to START Webmin services?";
        if (confirm(text)) {
            startWebminServices();
        }
    });
    function startWebminServices() {
        const selectedServer = $('#webminStart').val();
        const serverAddress = 'wss://' + selectedServer + '/wss/';
        console.log(serverAddress);
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = 'systemctl --no-pager start webmin';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            //alert("Webmin services successfully started on " + selectedServer);
            const response = event.data;
            console.log(response);
            if (response.includes('Failed')) {
                alert("Failed to start Webmin services on " + selectedServer);
            } else {
                alert("Webmin services successfully started on " + selectedServer);
            }
            socket.close();
            location.reload();
        });
    }
    // Stop //
    $('#webminStop').click(function(e) {
        let text = "Are you sure you want to STOP Webmin services?";
        if (confirm(text)) {
            stopWebminServices();
        }
    });
    function stopWebminServices() {
        const selectedServer = $('#webminStop').val();
        const serverAddress = 'wss://' + selectedServer + '/wss/';
        console.log(serverAddress);
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = 'systemctl --no-pager stop webmin';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            //alert("Webmin services successfully stopped on " + selectedServer);
            const response = event.data;
            console.log(response);
            if (response.includes('Failed')) {
                alert("Failed to stop Webmin services on " + selectedServer);
            } else {
                alert("Webmin services successfully stopped on " + selectedServer);
            }
            socket.close();
            location.reload();
        });
    }
    // Restart //
    $('#webminRestart').click(function(e) {
        let text = "Are you sure you want to RESTART Webmin services?";
        if (confirm(text)) {
            restartWebminServices();
        }
    });
    function restartWebminServices() {
        const selectedServer = $('#webminRestart').val();
        const serverAddress = 'wss://' + selectedServer + '/wss/';
        console.log(serverAddress);
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = 'systemctl --no-pager restart webmin';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            //alert("Webmin services successfully restarting on " + selectedServer);
            const response = event.data;
            console.log(response);
            if (response.includes('Failed')) {
                alert("Failed to restart Webmin services on " + selectedServer);
            } else {
                alert("Webmin services successfully restarting on " + selectedServer);
            }
            socket.close();
            location.reload();
        });
    }
    // Reload //
    $('#webminReload').click(function(e) {
        let text = "Are you sure you want to RELOAD Webmin services?";
        if (confirm(text)) {
            reloadWebminServices();
        }
    });
    function reloadWebminServices() {
        const selectedServer = $('#webminReload').val();
        const serverAddress = 'wss://' + selectedServer + '/wss/';
        console.log(serverAddress);
        const socket = new WebSocket(serverAddress);

        socket.addEventListener('open', (event) => {
            const command = 'systemctl --no-pager reload webmin';
            socket.send(command);
        });

        socket.addEventListener('message', (event) => {
            //alert("Webmin services successfully reloading on " + selectedServer);
            const response = event.data;
            console.log(response);
            if (response.includes('Failed')) {
                alert("Failed to reload Webmin services on " + selectedServer);
            } else {
                alert("Webmin services successfully reloading on " + selectedServer);
            }
            socket.close();
            location.reload();
        });
    }




</script>
@endpush
