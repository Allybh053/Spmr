@extends('layouts.app')
@push('styles')
    <style>
        .btn-4x4 {
            width: 60px;
            height: 30px;
            font-size: 18px;
            padding: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        /* General Button Styles */
        .beautiful-btn {
            background-color: #28a745; /* Green color for success buttons */
            color: white;              /* White text */
            font-size: 16px;           /* Font size */
            padding: 12px 24px;        /* Padding for more comfortable click area */
            border: none;              /* No borders */
            border-radius: 8px;        /* Slightly rounded corners */
            font-weight: 600;          /* Bold text */
            display: flex;
            align-items: center;      /* Vertically center icon and text */
            justify-content: center;  /* Horizontally center icon and text */
            cursor: pointer;          /* Pointer cursor on hover */
            transition: all 0.3s ease; /* Smooth transition for hover effects */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Light shadow for depth */
            text-transform: uppercase; /* Uppercase text */
        }

        /* Hover effect */
        .beautiful-btn:hover {
            background-color: #218838; /* Darker green on hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); /* Darker shadow */
            transform: translateY(-3px); /* Slight "lift" effect */
        }

        /* Active (clicked) effect */
        .beautiful-btn:active {
            transform: translateY(1px); /* Slight "press" effect */
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2); /* Subtle shadow */
        }

        /* Focus effect */
        .beautiful-btn:focus {
            outline: none;             /* Remove default focus outline */
            border: 2px solid #FFD700; /* Gold border on focus */
        }

        /* Optional: Small adjustments for better icon placement */
        .beautiful-btn i {
            font-size: 18px; /* Icon size */
            margin-right: 8px; /* Space between icon and text */
        }
        .centered {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .custom-form-background {
            background-color: #e3f2fd;
            padding: 16px;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .btn-4x4 {
                height: 50px;
                font-size: 16px;
                padding: 10px;
            }
        }

        @media (max-width: 576px) {
            .btn-4x4 {
                height: 40px;
                font-size: 14px;
                padding: 8px;
            }
        }

        .vertical-form .form-group {
            margin-bottom: 1rem;
        }
    </style>
@endpush

@section('content')
    <header class="page-header page-header-compact page-header-light border-bottom bg-light mb-4 shadow-sm">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title text-primary" style=" font-weight: bold; ">
                            <div class="page-header-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center">
                                <i class="fas fa-search"></i>
                            </div>
                            Search Registered IP
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4">
        <div class="row">
            <div class="col-xl-12">
                <!-- REGISTERED IP -->
                <div class="card mb-4">
                    <div class="card-header" style=" font-weight: bold">Search Registered IP</div>
                    <div class="card-body mt-1 mb-4">
                        <div class="p-4 rounded" style="background-color: #e3f2fd;">
                            <form id="searchForm" class="ajax-form-search vertical-form" novalidate action="{{ route('searchIp.api.getServerName') }}" method="POST">
                                @csrf
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="small mb-1" for="serverName" style=" font-weight: bold">SELECT SERVER:</label>
                                    {{--                                    {!! Form::select('serverName', $serverName, $defaultServerId ?? 1, ['id'=>'serverName', 'name'=>'serverName', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}--}}
                                    {!! Form::select('serverName', ['all' => 'All'] + $serverName->toArray(), $defaultServerId ?? 'all', ['id' => 'serverName', 'class' => 'form-control select2', 'placeholder' => config('params.dropdown_default')]) !!}
                                    <small class="text-muted">Select At Least One Server</small>
                                </div>
                                <div class="form-group">
                                    <label class="card-body mt-1 mb-4" for="ip-address-list" style=" font-weight: bold">IP Address List:</label>
                                    <textarea id="ip-address-list" class="form-control border-primary" rows="5" placeholder="Enter one IP address per line (maximum 10)."></textarea>
                                    <small class="text-muted">Example: 192.168.1.1</small>
                                </div>
                                <!-- Submit Button -->
                                <div style="padding: 10px;">
                                    <button type="button" class="btn btn-success btn-4x4 beautiful-btn" style="width: 10%; height: 50px;" id="reset">
                                        Reset <i class="me-2 fas fa-paper-plane"></i>
                                    </button>
                                    <button type="button" id="searchIp" class="btn btn-success btn-4x4 beautiful-btn" style="width: 10%; height: 50px;">
                                        <i class="me-2 fas fa-paper-plane"></i> Search
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- Submitted Data Table -->
                <div class="card border-light shadow-sm mt-4 d-none" id="data-table-card">
                    <div class="card-header bg-primary text-white text-center fs-5">Result</div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Server Name</th>
                                <th>IP Address</th>
                                <th>Status</th>
                                <th>Additional Info</th>
                            </tr>
                            </thead>
                            <tbody id="data-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>

        // var sockets = [];
        // var serverIpaddresses = [];
        //console.log("Code Execution Starts");
        $(document).ready(function () {
            $('#reset').on('click', function() {
                location.reload();
            });
            let ipStatusMap = new Map();
            $('#searchIp').click(function (e) {
                e.preventDefault();

                const selectedServer = $('#serverName').val();
                //console.log(selectedServer);
                var formData = $('#searchForm').serialize();
                $('#data-table-body').html('');
                $.ajax({
                    type: 'POST',
                    url: $('#searchForm').attr('action'),
                    data: formData,
                    success: function (response) {
                        //console.log("AJAX Response:", response);
                        // console.log("TEST: " + Array.isArray(response));
                        // if (Array.isArray(response)) {
                        //     websocket(response);
                        // } else {
                        //     websocket([response]);
                        // }
                        if (selectedServer === 'all') {
                            initializeMultipleWebSockets();
                        } else {
                            // websocket([response]);
                            websocket(response.subhostname ?? '');
                            // console.log("cuba lagi");
                        }
                        $('#data-table-card').removeClass('d-none');
                    },
                    error: function (xhr) {
                        console.error("AJAX Error: ", xhr.responseText);
                    }
                });
            });

            //1 server//
            function websocket(hostname) {
                const ipList = $('#ip-address-list').val().replace(/\n/g, " ");
                const ipAddresses = ipList.split(/\s+/);
                // for (let i = 0; i < 1; i++) {
                //     console.log("IP List for WebSocket:", ipList);
                // }
                //console.log("IP List for WebSocket:", ipAddresses);
                $('#data-table-body').html('');
                $('#data-table-card').removeClass('d-none');

                const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/' + hostname;
                //console.log("WebSocket Server Address:", serverAddress);
                const socket = new WebSocket(serverAddress);

                socket.addEventListener('open', () => {
                    const userEmail = "{{ $user->email }}";
                    const command = `/opt/scripts/websocket/cmd16 -v {{ $user->email }} ${ipList}`;
                    //console.log("WebSocket Command Sent:", command);
                    //console.log(`WebSocket connection established with ${hostname}`);
                    socket.send(command);
                });

                socket.addEventListener('message', (event) => {
                    const response = event.data;
                    //console.log("WebSocket Response:", response);
                    processResponse(response, hostname, ipAddresses);

                    // socket.addEventListener('error', (event) => {
                    //     //console.error("WebSocket Error: ", event);
                    // });
                });
            }
        });
        //1 server//


        //ALL Server
        function initializeMultipleWebSockets() {

            const ipList = $('#ip-address-list').val().replace(/\n/g, " ");
            const ipAddresses = ipList ? ipList.split(/\s+/) : [];
            const serverIpAddresses = [];

            $('#data-table-body').html('');
            $('#data-table-card').removeClass('d-none');

            // Collect all server IDs
            @foreach ($serverconfig as $server)
            let serverMailRelay{{$loop->iteration}} = "{{ $server->subhostname }}";
            serverIpAddresses.push(serverMailRelay{{$loop->iteration}});
            @endforeach

            if (serverIpAddresses.length === 0) {
                //console.warn("No server configurations found.");
                return;
            }

            serverIpAddresses.forEach((hostname, index) => {
                const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/' + hostname;
                const socket = new WebSocket(serverAddress);

                socket.addEventListener('open', () => {
                    const userEmail = "{{ $user->email }}";
                    const command = `/opt/scripts/websocket/cmd16 -v ${userEmail} ${ipList}`;
                    //console.log(`Sending command to ${hostname}:`, command);
                    //console.log(`WebSocket connection established with ${hostname}`);
                    socket.send(command);
                });

                socket.addEventListener('message', (event) => {
                    const response = event.data;
                    //console.log(`Response from ${hostname}:`, response);
                    processResponse(response, hostname, ipAddresses);
                });

                socket.addEventListener('error', (event) => {
                    //console.error(`WebSocket Error for ${hostname}:`, event);
                });

                // socket.addEventListener('close', () => {
                //     console.log(`WebSocket connection to ${hostname} closed.`);
                // });
            });
        }
        let currentIndex = 1;

        function processResponse(response, hostname, ipAddresses, startIndex = currentIndex) {
            const regex = /(?<ip>\d+\.\d+\.\d+\.\d+)\s+#(?<serial>[A-Za-z0-9-]+)/g;
            let ipStatusMap = new Map();
            let match;
            let index = startIndex;
            while ((match = regex.exec(response)) !== null) {
                const ip = match.groups.ip;
                const serialNumber = match.groups.serial;
                let hyperlink = `https://spmr.mygovuc.gov.my/smtp_relay/view/${serialNumber}/1`;
                if (!ipStatusMap.has(ip)) {
                    ipStatusMap.set(ip, []);
                    //console.log("IP Status Map:", Array.from(ipStatusMap.entries()));
                }
                if (!ipStatusMap.get(ip).includes(serialNumber)) {
                    ipStatusMap.get(ip).push(serialNumber);
                    //console.log("Processed IPs:", Array.from(ipStatusMap));
                }
            }
            ipStatusMap.forEach((serials, ip) => {
                serials.forEach(serialNumber => {
                    let hyperlink = `https://spmr.mygovuc.gov.my/smtp_relay/view/${serialNumber}/1`;

                    $('#data-table-body').append(
                        `<tr>
                    <td>${index}</td>
                    <td>${hostname}</td>
                    <td>${ip}</td>
                    <td>Found</td>
                    <td><a href="${hyperlink}" target="_blank" class="text-primary">${serialNumber}</a></td>
                </tr>`
                    );
                    index++;
                });
            });

            // ipAddresses.forEach(ip => {
            //     if (!ipStatusMap.has(ip)) {
            //         $('#data-table-body').append(
            //             `<tr>
            //         <td>${index}</td>
            //         <td>${hostname}</td>
            //         <td>${ip}</td>
            //         <td>Not Found</td>
            //         <td>No serial data found</td>
            //     </tr>`
            //         );
            //         index++;
            //     }
            // });

            // ipAddresses.forEach(ip => {
            //     if (ipStatusMap.has(ip)) {
            //         // If IP exists in the response, iterate over its serial numbers
            //         const serials = ipStatusMap.get(ip);
            //         serials.forEach(serialNumber => {
            //             const hyperlink = `https://spmr.mygovuc.gov.my/smtp_relay/view/${serialNumber}/1`;
            //             $('#data-table-body').append(
            //                 `<tr>
            //         <td>${index}</td>
            //         <td>${hostname}</td>
            //         <td>${ip}</td>
            //         <td>Found</td>
            //         <td><a href="${hyperlink}" target="_blank" class="text-primary">${serialNumber}</a></td>
            //     </tr>`
            //             );
            //             index++;
            //         });
            //     } else {
            //         // If IP is not found, add a single "Not Found" row
            //         $('#data-table-body').append(
            //             `<tr>
            //     <td>${index}</td>
            //     <td>${hostname}</td>
            //     <td>${ip}</td>
            //     <td>Not Found</td>
            //     <td>No serial data found</td>
            // </tr>`
            //         );
            //         index++;
            //     }
            // });

            currentIndex = index;
        }
    </script>
@endpush
