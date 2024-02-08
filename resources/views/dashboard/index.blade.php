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
                        <div class="row">
                            <div class="col-xxl-8" style="margin-left: 201px;">
                                <div class="card col">
                                    <div class="card-body text-center"><h3><u>Postmaster.mygovuc.gov.my</u></h3></div>
                                </div>
                            </div>
                        </div><hr>
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
                    </div>

 @endsection
 
 @push('script')
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
 <script src="{{asset('assets/demo/chart-area-demo.js')}}"></script>
 @endpush
