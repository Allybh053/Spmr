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
                                    {!! Form::select('serverName', $serverName, $defaultServerId ?? 1, ['id'=>'serverName', 'name'=>'serverName', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
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
            $.ajax({
                    type: 'POST',
                    url: '{!! route('log.api.getServerName') !!}',
                    data: {serverName: $('#serverName').val()},
                    success: function(response) {
                        // Handle the response from the controller
                        // console.log(response);

                        $('#card-title-header').text(response.subhostname);
                        $('#ip-address').text(response.ipaddress);

                        //Websocket connections

                        let hostname = response.subhostname;
                        // const serverAddress = 'wss://' + hostname + '/wss/';
                        websocket(hostname);
                  },
                  error: function(xhr, status, error) {
                    //console.error(xhr.responseText);
                    }
                });

         // form submit
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

                        $('#card-title-header').text(response.subhostname);
                        $('#ip-address').text(response.ipaddress);

                        //Websocket connections

                        let hostname = response.subhostname;
                        // const serverAddress = 'wss://' + hostname + '/wss/';
                        websocket(hostname);




                  },
                    error: function(xhr, status, error) {
                        // Handle errors
                        //console.error(xhr.responseText);
                    }
                });
            });


            function websocket(hostname){
                let sockets = [];

                const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/' + hostname;
                        const socket = new WebSocket(serverAddress);

                        sockets.push(socket);

                        sockets.forEach((socket, index) => {
                            getDataServerOverview(socket);
                        });

                        function getDataServerOverview() {
                            const commands = [
                                '/opt/scripts/websocket/cmd11' + ' ' + '{{ $user->email }}',
                                '/opt/scripts/websocket/cmd2' + ' ' + '{{ $user->email }}' + ' ' + 'postfix',
                                '/opt/scripts/websocket/cmd12' + ' ' + '{{ $user->email }}' + ' ' + 'PostfixSASLauthFailed',
                                '/opt/scripts/websocket/cmd12' + ' ' + '{{ $user->email }}' + ' ' + 'PostfixSASLauthFailed',
                                '/opt/scripts/websocket/cmd12' + ' ' + '{{ $user->email }}' + ' ' + 'PostfixSMTPDconnectionLost',
                                '/opt/scripts/websocket/cmd12' + ' ' + '{{ $user->email }}' + ' ' + 'PostfixSMTPDtimedOut',
                                '/opt/scripts/websocket/cmd12' + ' ' + '{{ $user->email }}' + ' ' + 'PostfixBounceNondeliveryNotification',
                                '/opt/scripts/websocket/cmd13' + ' ' + '{{ $user->email }}' + ' ' + 'grand-totals-received',
                                '/opt/scripts/websocket/cmd13' + ' ' + '{{ $user->email }}' + ' ' + 'grand-totals-delivered',
                                '/opt/scripts/websocket/cmd13' + ' ' + '{{ $user->email }}' + ' ' + 'grand-totals-deffered',
                                '/opt/scripts/websocket/cmd13' + ' ' + '{{ $user->email }}' + ' ' + 'grand-totals-bounced',
                                '/opt/scripts/websocket/cmd13' + ' ' + '{{ $user->email }}' + ' ' + 'grand-totals-rejected',
                                '/opt/scripts/websocket/cmd13' + ' ' + '{{ $user->email }}' + ' ' + 'grand-totals-senders',
                                '/opt/scripts/websocket/cmd13' + ' ' + '{{ $user->email }}' + ' ' + 'grand-totals-sending-hosts',
                                '/opt/scripts/websocket/cmd13' + ' ' + '{{ $user->email }}' + ' ' + 'grand-totals-recipients',
                                '/opt/scripts/websocket/cmd13' + ' ' + '{{ $user->email }}' + ' ' + 'grand-totals-recipient-hosts',
                                '/opt/scripts/websocket/cmd13' + ' ' + '{{ $user->email }}' + ' ' + 'host-domain-messages-received',
                                '/opt/scripts/websocket/cmd13' + ' ' + '{{ $user->email }}' + ' ' + 'per-hour-traffic-summary',
                            ];

                            const outputs = [];

                            socket.addEventListener('open', (event) => {

                                commands.forEach(command => {
                                    socket.send(command);
                                });
                            });

                            socket.addEventListener('message', (event) => {

                                const response = event.data;
                                //console.log("outputs"+outputs);
                                outputs.push(response);

                                $('#server-status').text(outputs[0]);
                                $('#service-status').text(outputs[1]);
                                $('#sasl').text(outputs[2]);
                                $('#smtpd-lost').text(outputs[3]);
                                $('#smtpd-timeout').text(outputs[4]);
                                $('#bounced').text(outputs[5]);

                                getDomain(outputs);
                                getGrandTotals(outputs);
                                trafficSumTableData(outputs);
                            });
                        }
            }


            function getGrandTotals(outputs){

            // Append grand totals to another table
            var grandTotalTableBody = document.getElementById('grandTotalTable');

            // Clear existing rows in grand total table
            grandTotalTableBody.innerHTML = '';

            var labels = ['received', 'delivered','deferred',  'bounced','rejected' ,'senders','sending hosts/domains','recipients', 'recipient hosts/domains'];
            var grandTotals = [outputs[7], outputs[8], outputs[9],outputs[10], outputs[11], outputs[12], outputs[13], outputs[14], outputs[15]];

            // Format numbers in grandTotals array
            var formattedGrandTotals = grandTotals.map(function(total) {
                    return numberWithCommas(total);
                });

                // Create rows for grand totals
                for (var i = 0; i < labels.length; i++) {
                    var grandTotalRow = document.createElement('tr');

                    var labelCell = document.createElement('td');
                    var grandTotalCell = document.createElement('td');

                    labelCell.textContent = labels[i];
                    grandTotalCell.textContent = formattedGrandTotals[i];

                    grandTotalRow.appendChild(labelCell);
                    grandTotalRow.appendChild(grandTotalCell);

                    grandTotalTableBody.appendChild(grandTotalRow);
                }
                if (!$.fn.DataTable.isDataTable('#grandTotalsDatatable')) {
                    $('#grandTotalsDatatable').DataTable({
                        "order": [[1, "desc"]] // Initial sorting on the second column (Counts) in descending order
                    });
}
            }

            function getDomain(outputs) {
                var regexFormat = /^\d+,[^,]+,.+$/; // Regular expression to match format "number,number,string"

                    // Filter outputs to get elements matching the format
                    var filteredOutputs = outputs.filter(function(output) {
                        return regexFormat.test(output);
                    });
                    if ($.fn.DataTable.isDataTable('#spmrDatatable')) {
                        $('#spmrDatatable').DataTable().destroy();
                    }
                    // Get the tbody element by its ID
                    var tableBody = document.getElementById('tableHostDomSum');

                    // Clear existing rows
                    tableBody.innerHTML = '';

                    // Create a row for each filtered output
                    for (var i = 0; i < filteredOutputs.length; i++) {
                        var row = document.createElement('tr');
                        var columns = filteredOutputs[i].split(',');

                        for (var j = 0; j < columns.length; j++) {
                            var cell = document.createElement('td');
                            // if (j != 0) { // Format the first column (assumed to be a number)
                                // cell.textContent = numberWithCommas(columns[j]);
                            // } else {
                                cell.textContent = numberWithCommas(columns[j]);
                            // }
                            row.appendChild(cell);
                        }

                        tableBody.appendChild(row);
                    }

                    // Re-initialize DataTables
                    $('#spmrDatatable').DataTable({
                        "order": [], // Initial sorting on the first column (Message Count) in descending order
                        "language": {
                            "emptyTable": "No data available in table" // Custom message when table is empty
                        }
                    });


            }

            function trafficSumTableData(outputs) {
                if ($.fn.DataTable.isDataTable('#spmrTrafficDatatable')) {
                        $('#spmrTrafficDatatable').DataTable().destroy();
                    }
                // Get the tbody element by its ID
                var tableBody = document.getElementById('tableTrafficSum');

                // Clear existing rows
                tableBody.innerHTML = '';

                var regex = /^\d{4}-\d{4}/; // Regular expression to match format "0000-0000"
                var filteredTrafficSum = outputs.filter(function(output) {
                    return regex.test(output);
                });

                // Create a row for each filtered output
                for (var i = 0; i < filteredTrafficSum.length; i++) {
                    var outputParts = filteredTrafficSum[i].split(','); // Split the string into parts
                    var row = document.createElement('tr');

                    for (var j = 0; j < outputParts.length; j++) {
                            var cell = document.createElement('td');
                            if (j != 0) { // Format the first column (assumed to be a number)
                                cell.textContent = numberWithCommas(outputParts[j]);
                            } else {
                                cell.textContent = outputParts[j];
                            }
                            row.appendChild(cell);
                        }

                    // Append the row to the table body
                    tableBody.appendChild(row);
                }

                // Re-initialize DataTables
                $('#spmrTrafficDatatable').DataTable({
                        "order": [], // Initial sorting on the first column (Message Count) in descending order
                        "language": {
                            "emptyTable": "No data available in table" // Custom message when table is empty
                        }
                    });
            }

            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

        });
    </script>


@endpush
