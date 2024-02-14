@extends('layouts.app')
@section('content')
                    <header class="py-10 mb-4 bg-gradient-primary-to-secondary">
                        <div class="container-xl px-4">
                            <div class="text-center">
                                <h1 class="text-white">Welcome to SPMR System</h1>
                                <p class="lead mb-0 text-white-50">Dashboard For Mail Relay Server</p>
                            </div>
                        </div>
                    </header>
                    <!-- Main page content-->
                    <div class="container-xl px-4 mt-n10">
                        <a class="card card-icon lift lift-sm mb-4" href="#">
                            <div class="row">
                                <div class="col-auto card-icon-aside bg-primary"><i class="text-white-50" data-feather="server"></i></div>
                                <div class="col">
                                   
                                    <div class="card-body py-4">
                                    @isset($serverconfig)
                                        <h5 class="card-title text-primary mb-2">{{ $serverconfig->hostname }}</h5>
                                        <p class="card-text mb-1">{{ $serverconfig->ipaddress }}</p>
                                        <div class="small text-muted"></div>
                                        @else
                                        <h5>Server Not Available</h5>
                                        <div class="small text-muted"><p>Please Select Your Server</p></div>
                                    @endisset
                                    </div>
                                  
                                </div>
                            </div>
                        </a>
                        <div class="row">
                            <div class="col-xl-4 mb-4">
                                <a class="card lift h-100" href="#!">
                                    <div class="card-body d-flex justify-content-center flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="me-3">
                                                <i class="feather-xl text-primary mb-3" data-feather="activity"></i>
                                                <h5>Total Application Registered</h5>
                                                <div class="inline">
                                                    <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                    <h2 style="display: inline-block; margin-left: 5px;">123</h2>
                                                </div>
                                            </div>
                                            <img src="{{asset ('assets/img/illustrations/dash_app.svg')}}" alt="..." style="width: 8rem" />
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 mb-4">
                                <a class="card lift h-100" href="#!">
                                    <div class="card-body d-flex justify-content-center flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="me-3">
                                                <i class="feather-xl text-secondary mb-3" data-feather="zap"></i>
                                                <h5>Total Domain Registered</h5>
                                                <div class="inline">
                                                    <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                    <h2 style="display: inline-block; margin-left: 5px;">123</h2>
                                                </div>
                                             </div>
                                            <img src="{{asset ('assets/img/illustrations/dash_domain.svg')}}" alt="..." style="width: 8rem" />
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 mb-4">
                                <a class="card lift h-100" href="#!">
                                    <div class="card-body d-flex justify-content-center flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="me-3">
                                                <i class="feather-xl text-green mb-3" data-feather="shield"></i>
                                                <h5>Numbers of Registered SASL</h5>
                                                <div class="inline">
                                                    <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                    <h2 style="display: inline-block; margin-left: 5px;">123</h2>
                                                </div>
                                            </div>
                                            <img src="{{asset ('assets/img/illustrations/dash_sasl.svg')}}" alt="..." style="width: 8rem" />
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-12">
                                <div class="card">
                                    <div class="card-header text-center p-5">
                                        <h5>View MailQueue Statistic (last 5 minutes)</h5>
                                        <p class="mb-4">Create view monitoring for Email Queue Health</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-area"><canvas id="myAreaChart" width="100%" height="30"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row  mt-5">
                            <div class="col-xxl-12">
                                <div class="card">
                                    <div class="card-header text-center p-5">
                                        <h5>RealTime Service Status</h5>
                                    </div>
                                    <div class="card-body mainRealtime" style="min-height: auto; max-height: 800px;overflow-y: auto;">
                                        @foreach ($server as $serverName => $services)
                                            <div class="card card-collapsable">
                                                <a class="card-header" href="#{{ Str::slug($serverName) }}" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="{{ Str::slug($serverName) }}">{{ $serverName }}
                                                    <div class="card-collapsable-arrow">
                                                        <i class="fas fa-chevron-down"></i>
                                                    </div>
                                                </a>
                                                <div class="collapse" id="{{ Str::slug($serverName) }}">
                                                    <div class="card-body">

                                                    <div class="row">
                                                        @foreach ($services as $serviceName => $status)
                                                            <div class="col-xl-4 mb-4">
                                                                <a class="card lift h-100" href="#!">
                                                                    <div class="card-body d-flex justify-content-center flex-column">
                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                            <div class="me-3">
                                                                                <h5>{{ $serviceName }}</h5>
                                                                            </div>
                                                                            @if ($status === 'Online')
                                                                                <img src="{{ asset('assets/img/demo/online.png') }}" alt="Online" style="width: 5rem">
                                                                            @else
                                                                                <img src="{{ asset('assets/img/demo/offline.png') }}" alt="Offline" style="width: 5rem">
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
</br>
                                        @endforeach






                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

 @endsection

 @push('script')
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
 <script src="{{asset('assets/demo/chart-area-demo.js')}}"></script>
 <script>
    document.addEventListener("DOMContentLoaded", function () {
        const collapser = document.querySelectorAll('.card-collapsable .card-header');
        collapser.forEach(function (el) {
            el.addEventListener('click', function () {
                let currentCollapse = this.getAttribute('href');
                collapser.forEach(function (el) {
                    if (el.getAttribute('href') !== currentCollapse) {
                        let otherCollapse = el.getAttribute('href');
                        $(otherCollapse).collapse('hide');
                    }
                });
            });
        });
    });
</script>
 @endpush
