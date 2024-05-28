<div class="container-xl px-4 mt-4">
    <div class="row">
        <div class="col-xl-12">
            <!-- Account details card-->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title text-primary mb-2" id="card-title-header">Server Name: {{ $serverData['serverName'] }}</h5>
                    <p class="card-text mb-1" id="ip-address">Ip Address : {{ $serverData['ipAddress'] }}</p>
                    <div class="small text-muted"></div>
                </div>


                <div class="card-body mt-1 mb-4">
                    <div class="row">
                        <div class="col-xl-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather-xl text-primary mb-3" data-feather="activity"></i>
                                            <h5>Server Status</h5>
                                            <div class="inline">
                                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                <p style="display: inline-block; margin-left: 5px;" id="server-status">{{ $serverData['serverStatus'] }}</p>
                                            </div>
                                        </div>
                                        <img src="{{asset ('assets/img/illustrations/dash_app.svg')}}" alt="..." style="width: 8rem" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather-xl text-primary mb-3" data-feather="activity"></i>
                                            <h5>Service Postfix Status</h5>
                                            <div class="inline">
                                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                <p style="display: inline-block; margin-left: 5px;" id="service-status">{{ $serverData['serviceStatus'] }}</p>
                                            </div>
                                        </div>
                                        <img src="{{asset ('assets/img/illustrations/dash_app.svg')}}" alt="..." style="width: 8rem" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather-xl text-green mb-3" data-feather="shield"></i>
                                            <h5>SASL Authentication Failed</h5>
                                            <div  class="small text-muted">Updated every 2 hours</div>
                                            <div class="inline">
                                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                <p style="display: inline-block; margin-left: 5px;" id="sasl">{{ $serverData['sasl'] }}</p>
                                            </div>
                                        </div>
                                        <img src="{{asset ('assets/img/illustrations/dash_sasl.svg')}}" alt="..." style="width: 8rem" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather-xl text-primary mb-3" data-feather="activity"></i>
                                            <h5>(%) SMTPD Connection Lost</h5>
                                            <div  class="small text-muted">Updated every 2 hours</div>
                                            <div class="inline">
                                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                <p style="display: inline-block; margin-left: 5px;" id="smtpd-lost">{{ $serverData['smtpdLost'] }}</p>
                                            </div>
                                        </div>
                                        <img src="{{asset ('assets/img/illustrations/dash_domain.svg')}}" alt="..." style="width: 8rem" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather-xl text-secondary mb-3" data-feather="zap"></i>
                                            <h5>(%) SMTPD Connection Timeout</h5>
                                            <div  class="small text-muted">Updated every 2 hours</div>
                                            <div class="inline">
                                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                <p style="display: inline-block; margin-left: 5px;" id="smtpd-timeout">{{ $serverData['smtpdTimeOut'] }}</p>
                                            </div>
                                        </div>
                                        <img src="{{asset ('assets/img/illustrations/dash_domain.svg')}}" alt="..." style="width: 8rem" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body d-flex justify-content-center flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-3">
                                            <i class="feather-xl text-green mb-3" data-feather="shield"></i>
                                            <h5>Bounced Non-Delivery Notifications</h5>
                                            <div  class="small text-muted">Updated every 2 hours</div>
                                            <div class="inline">
                                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                                <p style="display: inline-block; margin-left: 5px;" id="bounced">{{ $serverData['bounced'] }}</p>
                                            </div>
                                        </div>
                                        <img src="{{asset ('assets/img/illustrations/processing.svg')}}" alt="..." style="width: 8rem" />
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    @php
                    use Carbon\Carbon;

                        $currentDate = Carbon::now()->format('d/m/Y');
                    @endphp
                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Pie chart example-->
                            <div class="card mb-4">
                                <div class="card-header">Message(s) Overview</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="grandTotalsDatatable" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Messages</th>
                                                <th>Grand Totals</th>
                                            </tr>
                                            </thead>
                                            <tbody id="grandTotalTable">
                                            <!-- Rows will be dynamically added here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Updated {{ $currentDate }}</div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header">Top 10 Host/Domain Summary: Messages Received</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="spmrDatatable"  class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Message Count</th>
                                                <th>Bytes</th>
                                                <th>Host/Domain</th>
                                            </tr>
                                            </thead>
                                            <tbody id="tableHostDomSum">
                                            <!-- Rows will be dynamically added here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Updated {{ $currentDate }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header">Per-Hour Traffic Summary</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="spmrTrafficDatatable"  class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Time</th>
                                                <th>Received</th>
                                                <th>Delivered</th>
                                                <th>Deferred</th>
                                                <th>Bounced</th>
                                                <th>Rejected</th>
                                            </tr>
                                            </thead>
                                            <tbody id="tableTrafficSum">
                                            <!-- Rows will be dynamically added here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">Updated {{ $currentDate }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
