@extends('layouts.app')
@push('css')
<style>
    #terminal-output {
        background: black;
        color: white;
        padding: 10px;
        font-family: monospace;
        height: 500px;
        overflow-y: scroll;
    }

    .btn-4x4 {
        width: 6rem;
        height: 3rem;
        padding: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
</style>



@endpush
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
                                <li class="breadcrumb-item">Central Log Postmaster</li>
                            </ol>
                        </nav>
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-xl px-4 mt-4" id="searchingBox">
    <div class="row">
        <div class="col-xl-12">
            <!-- Account details card-->
            <div class="card mb-4">
                <div class="card-header">View Central Log</div>
                <div class="card-body mt-1 mb-4">
                    {{--<label class="small mb-1" for="serverName">Filter lines with keyword:</label>
                    <input type="text" id="search-input" placeholder="Enter search term">
                    <button class="btn btn-success" id="search-button">Search</button>--}}

                    {{--<div>
                        <label class="form-label fw-bold" for="line-input">Last <input type="text" id="line-input" placeholder="100 (default)" value="100"> lines of mail.log</label>
                    </div>

                    <div>
                        <label class="form-label fw-bold" for="since-input">Since: </label>
                        <select class="form-control select2" id="since-input">
                            <option value="latest">Latest Available</option>
                            <option value="realtime">Real-time follow</option>
                            <option value="current_boot">Current boot</option>
                            <option value="7_days_ago">7 days ago</option>
                            <option value="24_hours_ago">24 hours ago</option>
                            <option value="8_hours_ago">8 hours ago</option>
                            <option value="1_hour_ago">1 hours ago</option>
                            <option value="30_minutes_ago">30 minutes ago</option>
                            <option value="10_minutes_ago">10 minutes ago</option>
                            <option value="3_minutes_ago">3 minutes ago</option>
                            <option value="1_minute_ago">1 minutes ago</option>
                        </select>
                    </div>--}}

                    <div class="row gx-3 mb-3">
                        <div class="col-md-6" style="padding: 10px;">
                            <label class="fw-bold" for="timeStamp">Time Stamp:</label>
                            <input class="form-control" id="timeStamp" name="timeStamp" type="text" placeholder="e.g. 2024-01-01 or Any" value="Any"/>
                        </div>

                        <div class="col-md-6" style="padding: 10px;">
                            <label class="fw-bold" for="serverName">Server Hostname:</label>
                            {{--<select class="form-control select2" name="mailrelay" id="mailrelay" aria-label="Default select" required>
                                --}}{{--<option selected disabled>Select Server</option>--}}{{--
                                --}}{{--<option selected disabled>ALL (default)</option>--}}{{--
                                @foreach ($serversArray as $server)
                                    --}}{{--<option value="{{ $server['ipaddress'] }}" @if($server['ipaddress'] == $serverIp->ipaddress) selected @endif>{{ $server['subhostname'] }}</option>--}}{{--
                                    <option value="{{ $server['subhostname'] }}">{{ $server['subhostname'] }}</option>
                                @endforeach
                            </select>--}}
                            <input class="form-control" id="mailrelay" name="mailrelay" type="text" placeholder="e.g. postmaster1a or Any" value="Any"/>
                        </div>

                        <div class="col-md-6" style="padding: 10px;">
                            <label class="fw-bold" for="processId">Process ID:</label>
                            <input class="form-control" id="processId" name="processId" type="text" placeholder="e.g. postfix/smtpd or Any" value="Any"/>
                        </div>

                        {{--<div class="col-md-6">
                            <label class="fw-bold" for="line-input">Last lines of mail.log:</label>
                            <input class="form-control" id="line-input" name="line-input" type="text" placeholder="100 (default)" value="100"/>
                        </div>--}}

                        {{--<div class="col-md-6">
                            <label class="fw-bold" for="filter-input">Since:</label>
                            <select class="select2 form-control form-control-danger " name="filter-input" id="filter-input" required>
                                <option value="latest">Latest Available</option>
                                --}}{{--<option value="realtime">Real-time follow</option>
                                <option value="current_boot">Current boot</option>--}}{{--
                                <option value="7_days_ago">7 days ago</option>
                                <option value="24_hours_ago">24 hours ago</option>
                                <option value="8_hours_ago">8 hours ago</option>
                                <option value="1_hour_ago">1 hours ago</option>
                                <option value="30_minutes_ago">30 minutes ago</option>
                                <option value="10_minutes_ago">10 minutes ago</option>
                                <option value="3_minutes_ago">3 minutes ago</option>
                                <option value="1_minute_ago">1 minutes ago</option>
                            </select>
                        </div>--}}
                    </div>

                    <div>
                        <label class="form-label fw-bold" for="search-input">Filter lines with keyword:</label>
                        <textarea id="search-input" class="form-control border-primary" rows="5" placeholder="Enter search term" required></textarea>
                    </div>

                    <div>
                        <div class="col-md-6" style="padding: 10px;">
                            <label class="fw-bold" for="line-input">Last lines of mail.log:</label>
                            <input class="form-control" id="line-input" name="line-input" type="text" placeholder="100 (default)" value="100"/>
                        </div>
                    </div>


                </div>
                <div class="col-md-12">
                    <div style="padding: 25px; text-align: right;">
                        <button class="btn btn-success" id="search-button">Submit</button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Submitted Data</div>
                <div class="card-body">
                    <div id="terminal-output"></div>

                </div>
            </div>
        </div>
    </div>
</div>


 @endsection
 @push('script')
 <script>
     $(document).ready(function() {
         async function searchLogFile() {
             const query = document.getElementById('search-input').value;
             const queryLines = document.getElementById('line-input').value;
             const selectServer = document.getElementById('mailrelay').value;
             const timeStamp = document.getElementById('timeStamp').value;
             const processId = document.getElementById('processId').value;

             let valSelectServer = '';
             let valTimeStamp
             let valProcId = '';

             if (selectServer === 'Any') {
                 valSelectServer = '(postmaster1a|postmaster2a|postmaster3a)$';
             } else {
                 valSelectServer = selectServer;
             }

             if (timeStamp === 'Any') {
                 valTimeStamp = '(.*|any)$';
             } else {
                 valTimeStamp = timeStamp;
             }

             if (processId === 'Any') {
                 valProcId = '/postfix\\/smtpd|postfix\\/cleanup|postfix\\/qgmr/';
             } else {
                 valProcId = '/postfix\\/' + processId + '/';
             }

             if (query === '') {
                 alert("Please enter the keyword");
             } else {
                 try {
                     const response = await fetch('/central-log/search', {
                         method: 'POST',
                         headers: {
                             'Content-Type': 'application/json',
                             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                         },
                         body: JSON.stringify({
                             search: query,
                             searchLine: queryLines,
                             searchServer: valSelectServer,
                             searchTimeStamp: valTimeStamp,
                             searchProcessId: valProcId,
                         })
                     });

                     if (response.ok) {
                         const data = await response.json();
                         //console.log(data);
                         if (data.output) {
                             displayOutput(data.output);
                         } else {
                             displayOutput('No results found.');
                         }
                     } else {
                         displayOutput('Error: ' + response.statusText);
                     }
                 } catch (error) {
                     displayOutput('Error: ' + error.message);
                 }
             }

         }

         function displayOutput(data) {
             const outputDiv = document.getElementById('terminal-output');
             outputDiv.innerHTML = data;
             outputDiv.scrollTop = outputDiv.scrollHeight; // auto-scroll to the bottom
         }

         document.getElementById('search-button').addEventListener('click', searchLogFile);
     });

 </script>
 @endpush
