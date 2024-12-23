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
                                    {!! Form::select('serverName', $serverName, $defaultServerId ?? 1, ['id'=>'serverName', 'name'=>'serverName', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
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

        $(document).ready(function () {
            $('#reset').on('click', function() {
                location.reload();
            });
            let ipStatusMap = new Map();
            $('#searchIp').click(function (e) {
                e.preventDefault();
                var formData = $('#searchForm').serialize();
                $('#data-table-body').html('');
                $.ajax({
                    type: 'POST',
                    url: $('#searchForm').attr('action'),
                    data: formData,
                    success: function (response) {
                        //console.log("AJAX Response:", response);
                        if (Array.isArray(response)) {
                            response.forEach((item, index) => {
                                let ipaddress = item.ipaddress.match(/\d+\.\d+\.\d+\.\d+/) ? item.ipaddress.match(/\d+\.\d+\.\d+\.\d+/)[0] : item.ipaddress;
                                let serverName = item.subhostname.split(' ')[0];
                                let additionalInfo = item.ipaddress.split(' ')[1] ? item.ipaddress.split(' ')[1] : 'No additional info available';
                                if (!ipStatusMap.has(ipaddress)) {
                                    ipStatusMap.set(ipaddress, { status: 'Not Found', serial: null });

                                    $('#data-table-body').append(
                                        `<tr>
                                        <td>${index + 1}</td>
                                        <td>${serverName}</td>
                                        <td>${ipaddress}</td>
                                        <td>Not Found</td>
                                        <td>${additionalInfo}</td>
                                    </tr>`
                                    );
                                }
                            });
                        } else {
                            let ipaddress = response.ipaddress.match(/\d+\.\d+\.\d+\.\d+/) ? response.ipaddress.match(/\d+\.\d+\.\d+\.\d+/)[0] : response.ipaddress;
                            let serverName = response.subhostname.split(' ')[0];
                            let additionalInfo = response.ipaddress.split(' ')[1] ? response.ipaddress.split(' ')[1] : 'No additional info available';
                            if (!ipStatusMap.has(ipaddress)) {
                                ipStatusMap.set(ipaddress, { status: 'Not Found', serial: null });

                                $('#data-table-body').append(
                                    `<tr>
                                    <td>#</td>
                                    <td>${serverName}</td>
                                    <td>${ipaddress}</td>
                                    <td>Not Found</td>
                                    <td>${additionalInfo}</td>
                                </tr>`
                                );
                            }
                        }
                        $('#data-table-card').removeClass('d-none');
                        websocket(response.subhostname ?? '');
                    },
                    error: function (xhr) {
                        console.error("AJAX Error: ", xhr.responseText);
                    }
                });
            });
            function websocket(hostname) {
                const ipList = $('#ip-address-list').val().replace(/\n/g, " ");
                const ipAddresses = ipList.split(/\s+/);
                //console.log("IP List for WebSocket:", ipAddresses);
                $('#data-table-body').html('');

                const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/' + hostname;
                //console.log("WebSocket Server Address:", serverAddress);

                const socket = new WebSocket(serverAddress);

                socket.addEventListener('open', () => {
                    const userEmail = "{{ $user->email }}";
                    const command = `/opt/scripts/websocket/cmd16 -v {{ $user->email }} ${ipList}`;
                    //console.log("WebSocket Command Sent:", command);
                    socket.send(command);
                });

                socket.addEventListener('message', (event) => {
                    const response = event.data;
                   // console.log("WebSocket Response:", response);

                    const regex = /(?<ip>\d+\.\d+\.\d+\.\d+)\s+#(?<serial>[A-Za-z0-9-]+)/g;
                    //const regex1 = /#([A-Z-]+)/g;
                    let match;
                    let processedIps = new Set();
                    let index = 1;

                    while ((match = regex.exec(response)) !== null) {
                        const ip = match.groups.ip;
                        const serialNumber = match.groups.serial;
                        let hyperlink = '';

                        /*console.log(serialNumber.match(regex1));

                        if (serialNumber.match(regex1) !== null) {
                            hyperlink = `${serialNumber}`;
                        } else {
                            hyperlink = `https://spmr.mygovuc.gov.my/smtp_relay/view/${serialNumber}/1`;
                        }*/
                        //const hyperlink = `https://spmr.mygovuc.gov.my/smtp_relay/view/${serialNumber}/1`;
                        if (!ipStatusMap.has(ip)) {
                            ipStatusMap.set(ip, { status: 'Found', serial: serialNumber });

                            $('#data-table-body').append(
                                `<tr>
                                <td>${index}</td>
                                <td>${hostname}</td>
                                <td>${ip}</td>
                                <td>Found</td>
                                <td><a href="${hyperlink}" target="_blank" class="text-primary">${serialNumber}</a></td>
                            </tr>`);
                            index++;
                        } else {
                            let ipData = ipStatusMap.get(ip);
                            if (ipData.status === 'Not Found') {
                                ipData.status = 'Found';
                                ipData.serial = serialNumber;

                                // Update the row
                                $('#data-table-body').find(`tr:contains(${ip})`).each(function () {
                                    $(this).find('td').eq(3).text('Found');
                                    $(this).find('td').eq(4).html(`<a href="https://spmr.mygovuc.gov.my/smtp_relay/view/${serialNumber}/1" target="_blank" class="text-primary">${serialNumber}</a>`);
                                });
                            }
                        }
                    }
                    ipAddresses.forEach((ip, idx) => {
                        if (!ipStatusMap.has(ip)) {
                            ipStatusMap.set(ip, { status: 'Not Found', serial: null });

                            $('#data-table-body').append(
                                `<tr>
                                <td>${index + idx}</td>
                                <td>${hostname}</td>
                                <td>${ip}</td>
                                <td>Not Found</td>
                                <td>No serial data found</td>
                            </tr>`);
                        }
                    });
                });
                socket.addEventListener('error', (event) => {
                    console.error("WebSocket Error: ", event);
                });
            }
        });
    </script>
@endpush
