@extends('layouts.app')
@section('content')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="feather"></i></div>
                            System Information
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        {{-- <a class="btn btn-sm btn-light text-primary" href="{{ route('server.create') }}">
                            <i class="me-1" data-feather="plus"></i>
                            Add New Server Config
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </header>
<div class="container-fluid px-4 mt-4">
    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card card-header-actions h-100">
                <div class="card-header bg-warning text-white">
                    System Information
                </div>
                <div class="card-body">
                    <div class="list-group-item list-group-item-action">
                    <div class="list-group list-group-flush medium">
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            System Time : {{ now()->toDateTimeString() }}
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Hostname : {{ gethostname() }}
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            {{-- Client IPv4 Address: <span id="ipv4-address">Fetching...</span> --}}

                            System IPv4 Address: <span id="server-ipv4-address">Fetching...</span>
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            System IPv6 Address: <span id="server-ipv6-address">Fetching...</span>
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            DNS Servers:<hr>
                            @php
                                $dnsServers = exec("cat /etc/resolv.conf | grep nameserver | awk '{print $2}'");
                                echo $dnsServers ?: '-> <span class="text-danger">Not Available</span>';
                            @endphp
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            PostgreSQL Database: {{ config('database.connections.pgsql.database') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-4">
            <div class="card card-header-actions h-100">
                <div class="card-header bg-dark text-white">
                    System Version
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush medium">
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            SPMR Version: Version 1.0
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Environment: {{ app()->environment() }}
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            URL: {{ config('app.url') }}
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Application Key: <br>-> {{ config('app.key') }}
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Laravel Framework Version : {{ app()->version() }}
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            PHP Version: {{ phpversion() }}
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Application Version: {{ trim(shell_exec('git describe --tags --abbrev=0')) ?: 'Not Available' }}
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Composer Version: {{ trim(shell_exec('composer --version')) ?: 'Not Available' }}
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Git Version:
                            @php
                                $gitVersion = shell_exec('git --version');
                                echo $gitVersion ?: 'Not Available';
                            @endphp
                        </div>
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Last Commit Information:<br>->
                            @php
                                $lastCommit = shell_exec('git log -1');
                                echo $lastCommit ?: 'Not Available';
                            @endphp <br>

                        </div>
                        <div class="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Database Connection: {{ config('database.default') }}
                        </div>
                        <div cldivss="list-group-item list-group-item-action">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            PostgreSQL Version: <br>-> {{ DB::select("SELECT version();")[0]->version }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</main>
@endsection
@push('script')
<script>
    // Fetch server IP addresses using Laravel route
    fetch('/get-server-ip')
        .then(response => response.json())
        .then(data => {
            if (data.ipv4) {
                document.getElementById('server-ipv4-address').innerText = data.ipv4;
            } else {
                document.getElementById('server-ipv4-address').innerText = 'Not Available';
            }

            if (data.ipv6) {
                document.getElementById('server-ipv6-address').innerText = data.ipv6;
            } else {
                document.getElementById('server-ipv6-address').innerText = 'Not Available';
            }
        })
        .catch(() => {
            document.getElementById('server-ipv4-address').innerText = 'Not Available';
            document.getElementById('server-ipv6-address').innerText = 'Not Available';
        });
</script>
@endpush
