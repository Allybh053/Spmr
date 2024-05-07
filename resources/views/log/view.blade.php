<div class="container-xl px-4 mt-4">
    <div class="row">
        <div class="col-xl-12">
            <!-- Account details card-->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title text-primary mb-2" id="card-title-header">{{ $serverData['serverName'] }}</h5>
                    <p class="card-text mb-1" id="ip-address">Ip Address : {{ $serverData['ipAddress'] }}</p>
                    <div class="small text-muted"></div>
                </div>


                <div class="card-body mt-1 mb-4">
                    <div class="row">
                        <div class="col-xl-4 mb-4">
                            <a class="card lift h-100" href="#!">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather-xl text-primary mb-3" data-feather="activity"></i>
                                            <h5>Server Status</h5>
                                            <div class="inline">
                                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                <h2 style="display: inline-block; margin-left: 5px;" id="server-status">{{ $serverData['serverStatus'] }}</h2>
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
                                            <i class="feather-xl text-primary mb-3" data-feather="activity"></i>
                                            <h5>Service Status</h5>
                                            <div class="inline">
                                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                <h2 style="display: inline-block; margin-left: 5px;" id="service-status">{{ $serverData['serviceStatus'] }}</h2>
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
                                            <i class="feather-xl text-green mb-3" data-feather="shield"></i>
                                            <h5>SASL Authentication Failed</h5>
                                            <div class="inline">
                                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                <h2 style="display: inline-block; margin-left: 5px;" id="sasl">{{ $serverData['sasl'] }}</h2>
                                            </div>
                                        </div>
                                        <img src="{{asset ('assets/img/illustrations/dash_sasl.svg')}}" alt="..." style="width: 8rem" />
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-4 mb-4">
                            <a class="card lift h-100" href="#!">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather-xl text-primary mb-3" data-feather="activity"></i>
                                            <h5>(%) SMTPD Connection Lost</h5>
                                            <div class="inline">
                                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                <h2 style="display: inline-block; margin-left: 5px;" id="smtpd-lost">{{ $serverData['smtpdLost'] }}</h2>
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
                                            <i class="feather-xl text-secondary mb-3" data-feather="zap"></i>
                                            <h5>(%) SMTPD Connection Timeout</h5>
                                            <div class="inline">
                                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                <h2 style="display: inline-block; margin-left: 5px;" id="smtpd-timeout">{{ $serverData['smtpdTimeOut'] }}</h2>
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
                                            <h5>Bounced Non-Delivery Notifications</h5>
                                            <div class="inline">
                                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                <h2 style="display: inline-block; margin-left: 5px;" id="bounced">{{ $serverData['bounced'] }}</h2>
                                            </div>
                                        </div>
                                        <img src="{{asset ('assets/img/illustrations/processing.svg')}}" alt="..." style="width: 8rem" />
                                    </div>
                                </div>
                            </a>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Pie chart example-->
                            <div class="card mb-4">
                                <div class="card-header">Message(s) Overview</div>
                                <div class="card-body">
                                    <div class="chart-pie"><canvas id="myPieChart" width="100%" height="50"></canvas></div>
                                </div>
                                <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card mb-4" style="max-height: 394px;min-height: 394px;">
                                <div class="card-header">Top 10 Host/Domain</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="spmrDatatable"  class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Host/Domain</th>
                                                <th>Sender Email</th>
                                                <th>Receipient Email</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($serverData['top10Table'] as $index => $rowData)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $rowData['host'] }}</td>
                                                    <td>{{ $rowData['senderEmail'] }}</td>
                                                    <td>{{ $rowData['receipientEmail'] }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-header">Traffic Summary</div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="spmrTrafficDatatable"  class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Message(s) Overview</th>
                                            <th>Column A</th>
                                            <th>Column B</th>
                                            <th>Column C</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($serverData['trafficSummary'] as $index => $rowData)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $rowData['message'] }}</td>
                                                <td>{{ $rowData['data1'] }}</td>
                                                <td>{{ $rowData['data2'] }}</td>
                                                <td>{{ $rowData['data3'] }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
