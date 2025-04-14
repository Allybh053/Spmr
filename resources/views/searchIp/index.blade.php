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
        .beautiful-btn {
            background-color: #28a745;
            color: white;
            font-size: 16px;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-transform: uppercase;
        }
        .beautiful-btn:hover {
            background-color: #218838;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            transform: translateY(-3px);
        }
        .beautiful-btn:active {
            transform: translateY(1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }
        .beautiful-btn:focus {
            outline: none;
            border: 2px solid #FFD700;
        }
        .beautiful-btn i {
            font-size: 18px;
            margin-right: 8px;
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
        #reset {
            background: linear-gradient(90deg, #dc3545, #ff4d4d);
        }
        #reset:hover {
            background: linear-gradient(90deg, #c82333, #e60000);
        }
        #searchIp {
            background: linear-gradient(90deg, #007bff, #00bfff);
        }
        #searchIp:hover {
            background: linear-gradient(90deg, #0056b3, #0096e6);
        }
    </style>
@endpush
@section('content')
    <header class="page-header page-header-compact border-bottom shadow-sm" style="background-color: #e3f2fd; color: #0d47a1; border-bottom: 3px solid #2196f3;">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title text-primary" style=" font-weight: bold; ">
                            <div class="page-header-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center">
                                <i class="fas fa-search"></i>
                            </div>
                            SEARCH IP & STATUS
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container-xl px-4 mt-4">
        <div class="row">
            <div class="col-xl-12">
                <!-- REGISTERED IP $ STATUS -->
                <div class="card mb-4">
                    <div class="card-header" style=" font-weight: bold">SEARCH IP & STATUS</div>
                    <div class="card-body mt-1 mb-4">
                        <div class="p-4 rounded" style="background-color: #e3f2fd;">
                            <form id="searchForm" class="ajax-form-search vertical-form" novalidate action="{{ route('searchIp.api.getServerName') }}" method="POST">
                                @csrf
                                <!-- Form Group -->
                                <div class="form-group mb-5">
                                    <label class="small mb-1" for="serverName" style=" font-weight: bold">SELECT SERVER:</label>
                                    {!! Form::select('serverName', ['all' => 'All'] + collect($serverName->toArray())
                                                ->mapWithKeys(fn($value, $key) => [$key => ucwords(strtolower($value))])
                                                ->toArray(), 'all', ['id' => 'serverName', 'class' => 'form-control select2']) !!}
                                    <small class="text-muted">Select At Least One Server</small>
                                </div>
                                <div class="form-group mb-5">
                                    <label class="small mb-1" for="ip-address-list" style=" font-weight: bold">IP ADDRESS LIST:</label>
                                    <textarea id="ip-address-list" class="form-control border-primary" rows="5" placeholder="Enter one IP address per line (maximum 10)."></textarea>
                                    <small class="text-muted">Example: 192.168.1.1</small>
                                </div>
                                <!-- Submit Button -->
                                <div class="d-flex justify-content-start gap-3 p-2">
                                    <button type="button" id="reset" class="btn beautiful-btn" style="background: linear-gradient(90deg, #dc3545, #ff4d4d); color: white; width: 150px; height: 50px;">
                                        <i class="me-2 fa fa-sync fa-spin"></i> Reset
                                    </button>
                                    <button type="button" id="searchIp" class="btn beautiful-btn" style="background: linear-gradient(90deg, #007bff, #00bfff); color: white; width: 150px; height: 50px;">
                                        <i class="me-2 fas fa-search"></i> Search
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- New Table for Server Response Details -->
                <div class="card border-light shadow-sm mt-4 d-none" id="response-details-card">
                    <div class="card-header bg-secondary text-white text-center fs-5">RESULTS</div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Server Name</th>
                                <th>IP Address</th>
                                <th>SPMR Registration Status</th>
                                <th>Internal IPS Status</th>
                                <th>Control List</th>
                                <th>Remarks</th>
                            </tr>
                            </thead>
                            <tbody id="response-details-body">
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

                const selectedServer = $('#serverName').val();
                var formData = $('#searchForm').serialize();
                $('#data-table-body').html('');
                $('#response-details-body').html('');
                $.ajax({
                    type: 'POST',
                    url: $('#searchForm').attr('action'),
                    data: formData,
                    success: function (response) {
                        // if (Array.isArray(response)) {
                        //     websocket(response);
                        // } else {
                        //     websocket([response]);
                        // }
                        if (selectedServer === 'all') {
                            initializeMultipleWebSockets('cmd16');
                        } else {
                            websocket(response.subhostname ?? '', 'cmd16');
                        }
                        $('#data-table-card').removeClass('d-none');
                    },
                    error: function (xhr) {
                        console.error("AJAX Error: ", xhr.responseText);
                        // handleSessionExpiry(xhr);
                    }
                });
            });
            //1 server//
            function websocket(hostname, commandType) {
                const ipList = $('#ip-address-list').val().replace(/\n/g, " ");
                const ipAddresses = ipList.split(/\s+/);
                $('#data-table-body').html('');
                $('#data-table-card').removeClass('d-none');
                const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/' + hostname;
                const socket = new WebSocket(serverAddress);
                socket.addEventListener('open', () => {
                    const userEmail = "{{ $user->email }}";
                    const command = `/opt/scripts/websocket/${commandType} -v ${userEmail} ${ipList}`;
                    socket.send(command);
                });
                socket.addEventListener('message', (event) => {
                    const response = event.data;
                    //processResponse(response, hostname, ipAddresses);
                    processResponse(event.data, hostname, ipList.split(/\s+/));
                });
            }
        });
        //1 server//
        {{--//All server--}}
        function initializeMultipleWebSockets(commandType) {
            //const ipList = $('#ip-address-list').val().replace(/\n/g, " ");
            const ipList = $('#ip-address-list').val().replace(/\n/g, " ").trim();
            const ipAddresses = ipList ? ipList.split(/\s+/) : [];
            const serverIpAddresses = [];
            $('#data-table-body').html('');
            $('#data-table-card').removeClass('d-none');
            @foreach ($serverconfig as $server)
            let serverMailRelay{{$loop->iteration}} = "{{ $server->subhostname }}";
            serverIpAddresses.push(serverMailRelay{{$loop->iteration}});
            //console.log("Added server hostname:", serverMailRelay{{$loop->iteration}});
            @endforeach
            if (serverIpAddresses.length === 0) {
                return;
            }
            {{--serverIpAddresses.forEach((hostname) => {--}}
            {{--    websocket("{{ $server->subhostname }}", 'cmd16a');--}}
            {{--});--}}
            serverIpAddresses.forEach((hostname, index) => {
                const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/' + hostname;
                const socket = new WebSocket(serverAddress);

                socket.addEventListener('open', () => {
                    const userEmail = "{{ $user->email }}";
                    const command = `/opt/scripts/websocket/${commandType} -v ${userEmail} ${ipList}`;
                    socket.send(command);
                });
                socket.addEventListener('message', (event) => {
                    const response = event.data;
                    processResponse(response, hostname, ipAddresses);
                });
                socket.addEventListener('error', (event) => {
                });
            });
        }
        function processResponse(response, hostname, ipAddresses) {
            response = response.trim();
            let lines = response.split('\n');
            let tableBody = $('#response-details-body');
            let rowCount = tableBody.children().length;

            for (let line of lines) {
                line = line.trim();
                if (!line) continue;

                let ip = '-';
                let registrationStatus = '-';
                let displayStatus = '-';
                let internalIpsStatus = '-';
                let controlList = '-';
                let remarks = 'Active';
                let serialNumber = '';
                let hyperlink = '';

                try {
                    if (line.startsWith('"') && line.endsWith('"')) {
                        line = line.replace(/^"|"$/g, '');
                        let parts = line.split('" "').map(p => p.trim());

                        if (parts.length >= 4) {
                            ip = parts[0];
                            registrationStatus = parts[1];
                            internalIpsStatus = parts[2];
                            controlList = parts[3];
                        } else if (parts.length >= 1) {
                            ip = parts[0];
                            if (parts.length >= 2) registrationStatus = parts[1];
                            if (parts.length >= 3) internalIpsStatus = parts[2];
                            if (parts.length >= 4) controlList = parts[3];
                        }
                    } else {
                        let hashParts = line.split('#').filter(p => p.trim());
                        let ipMatch = line.match(/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/);
                        if (ipMatch) {
                            ip = ipMatch[1];
                        } else if (hashParts.length > 0) {
                            ip = hashParts[0].trim().split(/\s+/)[0];
                        }
                        if (line.includes('#')) {
                            let statusParts = line.split('#').map(p => p.trim()).filter(p => p);
                            if (statusParts.length > 1) {
                                registrationStatus = statusParts.slice(1).join('#');
                            }
                        }
                        let statusMatch = line.match(/"\s*"([^"]*)"\s*"([^"]*)"/);
                        if (statusMatch) {
                            internalIpsStatus = statusMatch[1] || '-';
                            controlList = statusMatch[2] || '-';
                        }
                    }
                    ip = ip.replace(/^#/, '').trim();

                    if (!ip || ip === '""') ip = '-';
                    let statusParts = registrationStatus.split('#').map(p => p.trim()).filter(p => p);
                    if (statusParts.length > 1) {
                        serialNumber = statusParts[1];
                    } else if (registrationStatus.includes('NOT-FOUND')) {
                        serialNumber = 'NOT-FOUND';
                    } else {
                        serialNumber = registrationStatus;
                    }

                    serialNumber = serialNumber.replace(/:.*$/, '').replace(/".*$/, '').trim();
                    displayStatus = serialNumber;
                    let isTerminated = false;
                    let isActive = false;

                    if (registrationStatus.startsWith(ip)) {
                        isActive = true;
                        isTerminated = false;
                    } else if (registrationStatus.startsWith(`#${ip}`)) {
                        isTerminated = true;
                        isActive = false;
                    } else if (registrationStatus === 'NOT-FOUND') {
                        isActive = false;
                        isTerminated = false;
                    }

                    // if (serialNumber && serialNumber !== 'NOT-FOUND' && serialNumber !== '-') {
                    {{--if (serialNumber && serialNumber !== 'NOT-FOUND') {--}}
                        {{--    @foreach($smtpRequest as $smtpReq)--}}
                        {{--    if (serialNumber.includes("{{ $smtpReq->form_id }}")) {--}}
                        {{--        hyperlink = `https://spmr.mygovuc.gov.my/smtp_relay/view/${serialNumber}/{{ $smtpReq->types_req }}`;--}}
                        {{--    }--}}
                        {{--    @endforeach--}}

                        {{--    if (hyperlink) {--}}
                        {{--        displayStatus = `<a href="${hyperlink}" target="_blank">${serialNumber}</a>`;--}}
                        {{--    }--}}
                        {{--}--}}
                    if (serialNumber && serialNumber !== 'NOT-FOUND' && serialNumber !== '-') {
                        @foreach($smtpRequest as $smtpReq)
                        if (serialNumber.includes("{{ $smtpReq->form_id }}"))
                            hyperlink = `https://spmr.mygovuc.gov.my/smtp_relay/view/${serialNumber}/{{ $smtpReq->types_req }}`;
                        @endforeach
                            displayStatus = `<a href="${hyperlink}" target="_blank">${serialNumber}</a>`;
                    }

                    if (serialNumber === "NOT-FOUND" && internalIpsStatus === "NOT-BANNED" && controlList === "Graylisted") {
                        remarks = "Not-Applicable";
                    } else if (isTerminated) {
                        remarks = "Terminated";
                    } else if (isActive) {
                        remarks = "Active";
                    } else {
                        remarks = "-";
                    }


                    let newRow = `<tr>
                <td>${++rowCount}</td>
                <td>${hostname}</td>
                <td>${ip}</td>
                <td>${displayStatus}</td>
                <td>${internalIpsStatus}</td>
                <td>${controlList}</td>
                <td>${remarks}</td>
            </tr>`;
                    tableBody.append(newRow);

                } catch (e) {
                    console.error("Error processing line:", line, e);
                    let newRow = `<tr>
                <td>${++rowCount}</td>
                <td>${hostname}</td>
                <td colspan="5" style="color: red;">${line}</td>
            </tr>`;
                    tableBody.append(newRow);
                }
            }
            $('#response-details-card').removeClass('d-none');
        }
    </script>
@endpush
