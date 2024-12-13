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

                    <label class="form-label fw-bold" for="search-input">Filter lines with keyword:</label>
                    <textarea id="search-input" class="form-control border-primary" rows="5" placeholder="Enter search term"></textarea>

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





{{--<div class="container-xl px-4 mt-4">
    <div class="row">
        <div class="col-xl-12">
            <div class="card border-light shadow-sm">
                <div class="card-header bg-primary text-white text-center fs-5">View Central Log</div>
                <div class="card-body">
                    <form id="search-ip-form">
                        --}}{{--<div class="mb-3">
                            <!-- Server Selection -->
                            <label class="form-label fw-bold" for="serverName">Server Name:</label>
                            <select class="form-control select2 border-primary" name="mailrelay" id="mailrelay" aria-label="Default select" required>
                                <option selected disabled>Select Server</option>
                                @foreach ($serversArray as $server)
                                    <option value="{{ $server['ipaddress'] }}" @if($server['ipaddress'] == $serverIp->ipaddress) selected @endif>{{ $server['subhostname'] }}</option>
                                @endforeach
                            </select>
                        </div>--}}{{--
                        <div class="mb-3">
                            <!-- IP Address List -->
                            <label class="form-label fw-bold" for="search-input">Filter lines with keyword:</label>
                            <textarea id="search-input" class="form-control border-primary" rows="5" placeholder="Enter search term"></textarea>
                            <small class="text-muted">Example: 10.10.10.10</small>
                        </div>
                        <!-- Submit Button -->
                        <div class="d-grid mt-4">
                            <button type="button" id="submit-button" class="btn btn-success btn-4x4">
                                <i class="me-2 fas fa-paper-plane"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            --}}{{--<div class="card border-light shadow-sm mt-4 d-none" id="data-table-card">
                <div class="card-header bg-primary text-white text-center fs-5">Submitted Data</div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Server Name</th>
                            <th>IP Address</th>
                        </tr>
                        </thead>
                        <tbody id="data-table-body">
                        </tbody>
                    </table>
                </div>
            </div>--}}{{--

            <div id="terminal-output"></div>
        </div>
    </div>
</div>--}}





 @endsection
 @push('script')
 <script>
     $(document).ready(function() {
         async function searchLogFile() {
             const query = document.getElementById('search-input').value;

             try {
                 const response = await fetch('/central-log/search', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                     },
                     body: JSON.stringify({ search: query })
                 });

                 //console.log(response)

                 if (response.ok) {
                     const data = await response.json();
                     console.log(data);
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

         function displayOutput(data) {
             const outputDiv = document.getElementById('terminal-output');
             outputDiv.innerHTML = data;
             outputDiv.scrollTop = outputDiv.scrollHeight; // auto-scroll to the bottom
         }

         document.getElementById('search-button').addEventListener('click', searchLogFile);
     });

 </script>
 @endpush
