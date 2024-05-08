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
                                    <li class="breadcrumb-item">Server Overview</li>
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
                    <div class="card-header">Mail Relay Server Overview</div>


                    <div class="card-body mt-1 mb-4">
                        <form  id="searchForm"  class="ajax-form-search" novalidate action="{{ route('log.api.getServerName') }}" method="POST">
                            <!-- Form Row-->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="serverName">Server Mail Relay Name:</label>
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
    <script>
        let dataHostDom = [];
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
                        //console.log(response);

                        $('#card-title-header').text(response.serverName);
                        $('#ip-address').text(response.ipAddress);

                        //Websocket connections
                        let sockets = [];
                        let hostname = response.serverName[0];
                        const serverAddress = 'wss://' + hostname + '/wss/';
                        const socket = new WebSocket(serverAddress);
                        sockets.push(socket);

                        sockets.forEach((socket, index) => {
                            getDataServerOverview(socket);
                        });

                        function getDataServerOverview() {
                            const commands = [
                                'uptime',
                                'status=$(systemctl --no-pager status postfix | grep Active:); if echo "$status" | grep -q "active (exited) since"; then echo "Postfix is running"; elif echo "$status" | grep -q "inactive (dead) since"; then echo "Postfix is down"; else echo "Something is wrong"; fi',
                                '/usr/bin/tail -1 /data/collections/spmr/PostfixSASLauthFailed.txt',
                                '/usr/bin/tail -1 /data/collections/spmr/PostfixSMTPDconnectionLost.txt',
                                '/usr/bin/tail -1 /data/collections/spmr/PostfixSMTPDtimedOut.txt',
                                '/usr/bin/tail -1 /data/collections/spmr/PostfixBounceNondeliveryNotification.txt',
                                '/usr/bin/bash /opt/scripts/spmr/grand-totals-received.sh',
                                '/usr/bin/bash /opt/scripts/spmr/grand-totals-deffered.sh',
                                '/usr/bin/bash /opt/scripts/spmr/grand-totals-delivered.sh',
                                '/usr/bin/bash /opt/scripts/spmr/grand-totals-bounced.sh',
                                '/usr/bin/bash /opt/scripts/spmr/grand-totals-recipients.sh',
                                '/usr/bin/bash /opt/scripts/spmr/grand-totals-sending-hosts.sh',
                                '/usr/bin/bash /opt/scripts/spmr/host-domain-messages-received.sh',
                                '/usr/bin/bash /opt/scripts/spmr/per-hour-traffic-summary.sh',
                            ];

                            const outputs = [];

                            socket.addEventListener('open', (event) => {

                                commands.forEach(command => {
                                    socket.send(command);
                                });
                            });

                            socket.addEventListener('message', (event) => {

                                const response = event.data;
                                getHostDomSum(response)
                                const output = extractOutput(response);
                                if (output !== null) {

                                    outputs.push(output);

                                    if (outputs.length > commands.length) {
                                        $('#server-status').text(outputs[1]);
                                        $('#service-status').text(outputs[3]);
                                        $('#sasl').text(outputs[5]);
                                        $('#smtpd-lost').text(outputs[7]);
                                        $('#smtpd-timeout').text(outputs[9]);
                                        $('#bounced').text(outputs[11]);
                                    }
                                }
                            });
                        }

                        function extractOutput(response) {
                            const cleanResponse = response.replace(/\[\d;?\d*m/g, '').trim();
                            getGrandTotals(cleanResponse);
                            getHostDomSum(response);
                            const outputMatch = cleanResponse.match(/\[.*?\]#\S (.*)/);
                            const outputs = [];
                            if (outputMatch && outputMatch.length > 1) {
                                outputs.push(outputMatch[1]);
                                return outputs;
                            } else {
                                return null;
                            }
                        }


                        var labels = ['received', 'deferred', 'delivered', 'bounced', 'recipients', 'sending hosts/domains'];
                        var datasetValues = [];

                        // Update Messages Overview table
                        function getGrandTotals(cleanResponse) {
                            if (cleanResponse) {
                                const outputMatch = cleanResponse.match(/\[.*?\]#\S (.*)/);
                                const numericLines = outputMatch.filter(line => /^\d/.test(line));
                                const filteredLines = numericLines.filter(line => !line.includes(','));
                                if (filteredLines.length > 0 && filteredLines[0] !== undefined) {
                                    datasetValues.push(filteredLines[0]);
                                }

                            } else {
                                //console.error("cleanResponse is undefined");
                            }

                            updateTableData();
                            function updateTableData() {
                                // Get the tbody element by its ID
                                var tableBody = document.getElementById('tableBody');

                                // Clear existing rows
                                tableBody.innerHTML = '';

                                // Create a row for each label-value pair
                                for (var i = 0; i < labels.length && i < datasetValues.length; i++) {
                                    var row = document.createElement('tr');
                                    var labelCell = document.createElement('td');
                                    var valueCell = document.createElement('td');
                                    labelCell.textContent = labels[i];
                                    valueCell.textContent = datasetValues[i];
                                    row.appendChild(labelCell);
                                    row.appendChild(valueCell);
                                    tableBody.appendChild(row);
                                }

                            }
                        }

                        function getHostDomSum(response) {
                            if (response) {
                                // Remove ANSI escape sequences
                                const cleanResponse = response.replace(/\[\d;?\d*m/g, '').trim();

                                // Remove additional unwanted characters
                                const cleanRes = cleanResponse.replace(/(\\.||\[.*?#\d+ )/g, '').trim();

                                // Split the response into lines
                                const lines = cleanRes.split('\n');

                                // Define the regular expression pattern to match the prefix
                                const regex = /\[root@postmaster[a-z]\S*\/opt\/cluster\/postfix\]#/g;

                                // Extract the value after removing the prefix
                                const filteredLines = lines.map(line => {
                                    // Replace the prefix with an empty string
                                    const lineWithoutPrefix = line.replace(regex, '');

                                    // Trim any leading or trailing whitespace
                                    return lineWithoutPrefix.trim();
                                });
                                const numericLines = filteredLines.filter(line => /^\d/.test(line));
                                numericLines.forEach((line, index) => {
                                    const valOutput = line.split(',');
                                    dataHostDom.push(valOutput);
                                });

                            } else {
                                //console.error("response is undefined");
                            }

                            // Update Top 10 Host/Domain table
                            hostDomTableData();
                            function hostDomTableData() {
                                // Get the tbody element by its ID
                                var tableBody = document.getElementById('tableHostDomSum');

                                // Clear existing rows
                                tableBody.innerHTML = '';

                                // Filter out arrays containing any element with a dash '-'
                                const filteredOutput = dataHostDom.filter(arr => !arr.some(elem => elem.includes('-')));

                                // Create a row for each label-value pair
                                for (var i = 0; i < filteredOutput.length; i++) {
                                    var row = document.createElement('tr');
                                    var valueCell = document.createElement('td');
                                    var valueCell1 = document.createElement('td');
                                    var valueCell2 = document.createElement('td');
                                    valueCell.textContent = filteredOutput[i][0];
                                    valueCell1.textContent = filteredOutput[i][1];
                                    valueCell2.textContent = filteredOutput[i][2];
                                    row.appendChild(valueCell);
                                    row.appendChild(valueCell1);
                                    row.appendChild(valueCell2);
                                    tableBody.appendChild(row);
                                }
                            }


                            // Update Traffic Summary table
                            trafficSumTableData();
                            function trafficSumTableData() {
                                // Get the tbody element by its ID
                                var tableBody = document.getElementById('tableTrafficSum');

                                // Clear existing rows
                                tableBody.innerHTML = '';

                                // Filter out arrays containing any element contain dash '-'
                                const filteredTrafficSum = dataHostDom.filter(arr => arr.some(elem => elem.includes('-')));

                                // Create a row for each label-value pair
                                for (var i = 0; i < filteredTrafficSum.length; i++) {
                                    var row = document.createElement('tr');
                                    var valueCell = document.createElement('td');
                                    var valueCell1 = document.createElement('td');
                                    var valueCell2 = document.createElement('td');
                                    var valueCell3 = document.createElement('td');
                                    var valueCell4 = document.createElement('td');
                                    var valueCell5 = document.createElement('td');
                                    valueCell.textContent = filteredTrafficSum[i][0];
                                    valueCell1.textContent = filteredTrafficSum[i][1];
                                    valueCell2.textContent = filteredTrafficSum[i][2];
                                    valueCell3.textContent = filteredTrafficSum[i][3];
                                    valueCell4.textContent = filteredTrafficSum[i][4];
                                    valueCell5.textContent = filteredTrafficSum[i][5];
                                    row.appendChild(valueCell);
                                    row.appendChild(valueCell1);
                                    row.appendChild(valueCell2);
                                    row.appendChild(valueCell3);
                                    row.appendChild(valueCell4);
                                    row.appendChild(valueCell5);
                                    tableBody.appendChild(row);
                                }
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        //console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>


@endpush
