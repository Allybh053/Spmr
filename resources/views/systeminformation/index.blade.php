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
                    <div class="list-group list-group-flush medium">
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            System Time : {{ now()->utc() }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Hostname : {{ gethostname() }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Client IPv4 Address: {{ request()->ip() }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Client IPv6 Address:
                            @if(isset($_SERVER['SERVER_ADDR_IPV6']))
                                {{ $_SERVER['SERVER_ADDR_IPV6'] }}
                            @else
                                <span class="text-danger">Not Available</span>
                            @endif
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            DNS Servers:<hr>
                            @php
                                $dnsServers = exec("cat /etc/resolv.conf | grep nameserver | awk '{print $2}'");
                                echo $dnsServers ?: '-> <span class="text-danger">Not Available</span>';
                            @endphp
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            PostgreSQL Database: {{ config('database.connections.pgsql.database') }}
                        </a>
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
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Environment: {{ app()->environment() }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            URL: {{ config('app.url') }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Application Key: <br>-> {{ config('app.key') }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Laravel Framework Version : {{ app()->version() }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            PHP Version: {{ phpversion() }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Application Version: {{ trim(shell_exec('git describe --tags --abbrev=0')) ?: 'Not Available' }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Composer Version: {{ trim(shell_exec('composer --version')) ?: 'Not Available' }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Git Version:
                            @php
                                $gitVersion = shell_exec('git --version');
                                echo $gitVersion ?: 'Not Available';
                            @endphp
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Last Commit Information:<br>->
                            @php
                                $lastCommit = shell_exec('git log -1');
                                echo $lastCommit ?: 'Not Available';
                            @endphp <br>

                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            Database Connection: {{ config('database.default') }}
                        </a>
                        <a class="list-group-item list-group-item-action" href="#!">
                            <i class="text-blue me-2" data-feather="chevrons-right"></i>
                            PostgreSQL Version: <br>-> {{ DB::select("SELECT version();")[0]->version }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</main>
@endsection
