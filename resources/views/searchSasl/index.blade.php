@extends('layouts.app')

@push('styles')
    <style>
        /* Button styles */
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
            background-color: #007bff;
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
        }
        .beautiful-btn:hover {
            background-color: #0056b3;
        }
    </style>
@endpush

@section('content')
    <header class="page-header page-header-compact border-bottom shadow-sm" style="background-color: #e3f2fd; color: #0d47a1; border-bottom: 3px solid #2196f3;">
    <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title text-primary" style="font-weight: bold;">
                            <div class="page-header-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center">
                                <i class="fas fa-search"></i>
                            </div>
                            SEARCH SASL & STATUS
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4">
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header" style="font-weight: bold">SEARCH SASL & STATUS</div>
                    <div class="card-body mt-1 mb-4">
                        <div class ="p-4 rounded" style="background-color: #e3f2fd;">
                    <form id="searchForm" class="ajax-form-search" action="{{ route('searchSasl.api.getServerName') }}" method="POST" novalidate>
                            @csrf
                            <!-- Form Group -->
                            <div class="form-group mb-5">
                                <label class="small mb-1" for="serverName" style=" font-weight: bold">SELECT SERVER:</label>
                                {!! Form::select('serverName', ['all' => 'All'] + collect($serverName->toArray())
                                            ->mapWithKeys(fn($value, $key) =>[$key => ucwords(strtolower($value))])
                                            ->toArray(),'all', ['id' => 'serverName', 'class' => 'form-control select2']) !!}
                                <small class="text-muted">Select At Least One Server</small>
                            </div>
                            <div class="form-group mb-5">
                                <label class="small mb-1" for="ip-address-list" style=" font-weight: bold">SMTP Username/Email:</label>
                                <textarea id="smtp-username" class="form-control border-primary" rows="5" placeholder="Enter one email or username per line."></textarea>
                                <small class="text-muted">Example: user@example.com</small>
                            </div>
                            <div class="d-flex justify-content-start gap-3 p-2">
                                <button type="button" id="reset" class="btn beautiful-btn" style="background: linear-gradient(90deg, #dc3545, #ff4d4d); color: white; width: 150px; height: 50px;">
                                    <i class="me-2 fa fa-sync fa-spin"></i> Reset
                                </button>
                                <button type="button" id="searchSasl" class="btn beautiful-btn" style="background: linear-gradient(90deg, #007bff, #00bfff); color: white; width: 150px; height: 50px;">
                                    <i class="me-2 fas fa-search"></i> Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
                <div class="card border-light shadow-sm mt-4 d-none" id="data-table-card">
                    <div class="card-header bg-primary text-white text-center fs-5">Results</div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Matching Username</th>
                                <th>Status</th>
                                <th>SPMR Registration Status</th>
                            </tr>
                            </thead>
                            <tbody id="data-table-body"></tbody>
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
            $('#reset').click(function () {
                location.reload();
                $('#smtp-username').val('');
                $('#data-table-body').empty();
                $('#data-table-card').addClass('d-none');
            });

            $('#searchSasl').click(function (e) {
                e.preventDefault();
                const selectedServer = $('#serverName').val();
                var formData = $('#searchForm').serialize();
                $('#data-table-body').html('');
                const smtpUsernames = $('#smtp-username').val().split(/\n/).map(username => username.trim()).filter(username => username);

                if (smtpUsernames.length === 0) {
                    alert('Please enter at least one SMTP username/email.');
                    return;
                }
                $.ajax({
                    url: $('#searchForm').attr('action'),
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        try {
                            //console.log("Server Response:", response);
                            let servers = Array.isArray(response) ? response : [response];

                            //console.log("Processing servers:", servers);
                            servers.forEach(server => {
                                //console.log("Processing server:", server);
                                if (server.subhostname) {
                                    //console.log("Found subhostname:", server.subhostname);

                                    if (selectedServer === 'all') {
                                        //console.log("Initializing WebSocket for all servers with usernames:", smtpUsernames);
                                        initializeMultipleWebSockets(smtpUsernames);
                                    } else {
                                        websocket(server.subhostname ?? '', smtpUsernames);
                                    }
                                } else {
                                    //console.warn("Server does not have a subhostname:", server);
                                }
                            });

                            $('#data-table-card').removeClass('d-none');
                        } catch (error) {
                            //console.error("Error in processing server response:", error);
                            alert("There was an issue with the server response.");
                        }
                    }
                })
                    function websocket(hostname, smtpUsernames) {
                    const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/' + hostname;
                    const socket = new WebSocket(serverAddress);

                    socket.addEventListener('open', () => {
                        const userEmail = "{{ $user->email }}";
                        const command = `/opt/scripts/websocket/cmd17 -v {{ $user->email }} ${smtpUsernames.join(" ")}`;
                        socket.send(command);
                    });

                    socket.addEventListener('message', (event) => {
                        const response = event.data;
                        processResponse(response, hostname, smtpUsernames);
                    });
                }

                function initializeMultipleWebSockets(smtpUsernames) {
                    const serverIpAddresses = [];
                    @foreach ($serverconfig as $server)
                    serverIpAddresses.push("{{ $server->subhostname }}");
                    @endforeach

                    serverIpAddresses.forEach((hostname) => {
                        const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/' + hostname;
                        const socket = new WebSocket(serverAddress);

                        socket.addEventListener('open', () => {
                            const userEmail = "{{ $user->email }}";
                            const command = `/opt/scripts/websocket/cmd17 -v {{ $user->email }} ${smtpUsernames.join(" ")}`;
                            socket.send(command);
                        });

                        socket.addEventListener('message', (event) => {
                            const response = event.data;
                            processResponse(response, hostname, smtpUsernames);
                        });
                    });
                }
                //v1 prob with no. row
                /*function processResponse(response, hostname, smtpUsernames) {
                    response = response.trim().replace(/^"|"$/g, '');
                    let parts = response.split('" "').map(p => p.trim());
                    let tableBody = $('#data-table-body');

                    if (tableBody.children().length === 0) {
                        tableBody.empty();
                    }

                    let rowCount = 0;
                    let processedUsernames = new Set();

                    for (let i = 0; i < parts.length; i += 3) {
                        let fullUsername = parts[i];
                        let status = parts[i + 1] || '';
                        let registration = parts[i + 2] || '';

                        // Case: registration field contains token like "#email #Token:"
                        if (registration.startsWith('#') && registration.endsWith(':')) {
                            // Extract token part after last space before the colon
                            let match = registration.match(/#.+\s+(#?[A-Za-z0-9]+):$/);
                            if (match) {
                                let rawToken = match[1]; // This may or may not start with #
                                let token = rawToken.replace(/^#/, ''); // Remove leading #
                                let statusCode = rawToken.startsWith('#') ? 2 : 1;
                                let link = `https://spmr.mygovuc.gov.my/smtp_relay/view/${token}/${statusCode}`;

                                let newRow = `<tr>
                    <td>${++rowCount}</td>
                    <td>${fullUsername}</td>
                    <td>${status}</td>
                    <td><a href="${link}" target="_blank">${token}</a></td>
                </tr>`;
                                tableBody.append(newRow);
                                continue;
                            }
                        }

                        // Fallback: handle other cases
                        let cleanReg = registration.replace(/^#|:$/g, '');
                        if (cleanReg !== 'N/A' && cleanReg !== '') {
                            let token = cleanReg;
                            let statusCode = registration.trim().startsWith('#') ? 2 : 1;
                            let link = `https://spmr.mygovuc.gov.my/smtp_relay/view/${token}/${statusCode}`;

                            let newRow = `<tr>
                <td>${++rowCount}</td>
                <td>${fullUsername}</td>
                <td>${status}</td>
                <td><a href="${link}" target="_blank">${token}</a></td>
            </tr>`;
                            tableBody.append(newRow);
                        } else {
                            let newRow = `<tr>
                <td>${++rowCount}</td>
                <td>${fullUsername}</td>
                <td>${status}</td>
                <td>N/A</td>
            </tr>`;
                            tableBody.append(newRow);
                        }
                    }

                    $('#data-table-card').removeClass('d-none');
                }*/



                //v2 the real one
                /*function processResponse(response, hostname, smtpUsernames) {
                    response = response.trim().replace(/^"|"$/g, '');
                    let parts = response.split('" "').map(p => p.trim());
                    let tableBody = $('#data-table-body');

                    if (tableBody.children().length === 0) {
                        tableBody.empty();
                    }

                    let rowCount = tableBody.children().length;

                    for (let i = 0; i < parts.length; i += 3) {
                        let username = parts[i] || '';
                        let status = parts[i + 1] || '';
                        let registration = parts[i + 2] || '';
                        let registrationEntries = registration
                            .split('\n')
                            .map(line => {
                                const tokenMatch = line.match(/#([A-Za-z0-9]+):?/);
                                return tokenMatch ? tokenMatch[1] : null;
                            })
                            .filter(Boolean);

                        if (registrationEntries.length === 0) {
                            tableBody.append(`
                            <tr>
                                <td>${++rowCount}</td>
                                <td>${username}</td>
                                <td>${status}</td>
                                <td>N/A</td>
                            </tr>
                        `);
                        } else {
                            registrationEntries.forEach((token, index) => {
                                let isTerminated = registration.includes(`${token}:`);
                                let statusCode = isTerminated ? 1 : 2;
                                let link = `https://spmr.mygovuc.gov.my/smtp_relay/view/${token}/${statusCode}`;

                                tableBody.append(`
                    <tr>
                        <td>${++rowCount}</td>
                        <td>${index === 0 ? username : ''}</td>
                        <td>${index === 0 ? status : ''}</td>
                        <td>
                            <a href="${link}" target="_blank">${token}</a>
                           <!-- ${isTerminated ? ' (Active)' : ''} -->
                        </td>
                    </tr>
                `);
                            });
                        }
                    }

                    $('#data-table-card').removeClass('d-none');
                }*/

                //the best one
                /*function processResponse(response, hostname, smtpUsernames) {
                    response = response.trim().replace(/^"|"$/g, '');
                    let parts = response.split('" "').map(p => p.trim());
                    let tableBody = $('#data-table-body');

                    if (tableBody.children().length === 0) {
                        tableBody.empty();
                    }

                    let rowCount = tableBody.children().length;

                    for (let i = 0; i < parts.length; i += 3) {
                        let username = parts[i] || '';
                        let status = parts[i + 1] || '';
                        let registration = parts[i + 2] || '';
                        let registrationEntries = registration
                            .split(' ')
                            .map(line => {
                                const tokenMatch = line.match(/#([A-Za-z0-9]+):?/);
                                return tokenMatch ? tokenMatch[1] : null;
                            })
                            .filter(Boolean);

                        if (registrationEntries.length === 0) {
                            tableBody.append(`
                <tr>
                    <td>${++rowCount}</td>
                    <td>${username}</td>
                    <td>${status}</td>
                    <td>N/A</td>
                </tr>
            `);
                        } else {
                            registrationEntries.forEach((token, index) => {
                                let hashCount = (registration.match(/#/g) || []).length;
                                let statusCode = hashCount > 1 ? 2 : 1;
                                let link = `https://spmr.mygovuc.gov.my/smtp_relay/view/${token}/${statusCode}`;

                                tableBody.append(`
                    <tr>
                        <td>${++rowCount}</td>
                        <td>${index === 0 ? username : ''}</td>
                        <td>${index === 0 ? status : ''}</td>
                        <td>
                            <a href="${link}" target="_blank">${token}</a>
                        </td>
                    </tr>
                `);
                            });
                        }
                    }

                    $('#data-table-card').removeClass('d-none');
                }*/
                /*function processResponse(response, hostname, smtpUsernames) {
                    response = response.trim().replace(/^"|"$/g, '');
                    let parts = response.split('" "').map(p => p.trim());
                    let tableBody = $('#data-table-body');

                    if (tableBody.children().length === 0) {
                        tableBody.empty();
                    }

                    let rowCount = tableBody.children().length;

                    for (let i = 0; i < parts.length; i += 3) {
                        let username = parts[i] || '';
                        let status = parts[i + 1] || '';
                        let registration = parts[i + 2] || '';

                        // Extract all tokens from registration field
                        let tokens = [];
                        let tokenMatch;
                        const tokenRegex = /#([A-Za-z0-9]+)(?::|$)/g;

                        while ((tokenMatch = tokenRegex.exec(registration))){
                            tokens.push(tokenMatch[1]);
                        }

                        if (tokens.length === 0) {
                            // No token found - add row with N/A
                            tableBody.append(`
                <tr>
                    <td>${++rowCount}</td>
                    <td>${username}</td>
                    <td>${status}</td>
                    <td>N/A</td>
                </tr>
            `);
                        } else {
                            // For each token found, create a row
                            tokens.forEach(token => {
                                let isTerminated = registration.includes(`${token}:`);
                                let statusCode = isTerminated ? 1 : 2;
                                let link = `https://spmr.mygovuc.gov.my/smtp_relay/view/${token}/${statusCode}`;

                                tableBody.append(`
                    <tr>
                        <td>${++rowCount}</td>
                        <td>${username}</td>
                        <td>${status}</td>
                        <td><a href="${link}" target="_blank">${token}</a></td>
                    </tr>
                `);
                            });
                        }
                    }

                    $('#data-table-card').removeClass('d-none');
                }*/
                function processResponse(response, hostname, smtpUsernames) {
                    // Clean and prepare the response data
                    response = response.trim().replace(/^"|"$/g, '');
                    let parts = response.split('" "').map(p => p.trim());
                    let tableBody = $('#data-table-body');

                    // Clear table if empty
                    if (tableBody.children().length === 0) {
                        tableBody.empty();
                    }

                    // Start row count from current table size
                    let rowCount = tableBody.children().length;

                    // Process each record (3 parts per record)
                    for (let i = 0; i < parts.length; i += 3) {
                        let username = parts[i] || '';
                        let status = parts[i + 1] || '';
                        let registration = parts[i + 2] || '';

                        // Extract tokens with status detection
                        let tokens = [];

                        // Special case for email#token format
                        if (registration.includes('@') && registration.includes('#')) {
                            let lastHashIndex = registration.lastIndexOf('#');
                            let token = registration.substring(lastHashIndex + 1).replace(':', '');
                            let statusCode = registration.startsWith('#') ? 2 : 1;

                            tokens.push({
                                token: token,
                                statusCode: statusCode
                            });
                        }
                        // Regular token case
                        else {
                            const tokenRegex = /(?:^|\s)(#?)([A-Za-z0-9]+)(?::|$)/g;
                            let match;

                            while ((match = tokenRegex.exec(registration))) {
                                tokens.push({
                                    token: match[2], // The actual token
                                    statusCode: match[1] === '#' ? 2 : 1 // 2 if starts with #, 1 otherwise
                                });
                            }
                        }

                        if (tokens.length === 0) {
                            // No tokens found - add N/A row
                            tableBody.append(`
                <tr>
                    <td>${++rowCount}</td>
                    <td>${username}</td>
                    <td>${status}</td>
                    <td>N/A</td>
                </tr>
            `);
                        } else {
                            // Add row for each token found
                            tokens.forEach(tokenData => {
                                let link = `https://spmr.mygovuc.gov.my/smtp_relay/view/${tokenData.token}/${tokenData.statusCode}`;

                                tableBody.append(`
                    <tr>
                        <td>${++rowCount}</td>
                        <td>${username}</td>
                        <td>${status}</td>
                        <td><a href="${link}" target="_blank">${tokenData.token}</a></td>
                    </tr>
                `);
                            });
                        }
                    }

                    // Show the table when done
                    $('#data-table-card').removeClass('d-none');
                }
            });
        });
    </script>
@endpush
