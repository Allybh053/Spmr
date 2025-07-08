<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-dark">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <!-- Sidenav Menu Heading (Account)-->
                <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                <div class="sidenav-menu-heading d-sm-none">Account</div>
                <!-- Sidenav Link (Alerts)-->
                <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                <a class="nav-link d-sm-none" href="#!">
                    <div class="nav-link-icon"><i data-feather="bell"></i></div>
                    Alerts
                    <span class="badge bg-warning-soft text-warning ms-auto">4 New!</span>
                </a>
                <!-- Sidenav Link (Messages)-->
                <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                <a class="nav-link d-sm-none" href="#!">
                    <div class="nav-link-icon"><i data-feather="mail"></i></div>
                    Messages
                    <span class="badge bg-success-soft text-success ms-auto">2 New!</span>
                </a>
                <!-- Sidenav Menu Heading (Core)-->
                <div class="sidenav-menu-heading">Main Page</div>
                <!-- Sidenav Accordion (Dashboard)-->
                <a class="nav-link" href="{{ route('dashboard.index')}}">
                    <div class="nav-link-icon"><i data-feather="activity"></i></div>
                    Dashboard
                    <div class="sidenav-collapse-arrow"></div>
                </a>

                @php
                    $canViewMailRelay = Gate::check('smtp_mail_relay_view') || Gate::check('manage_mail_relay_view') || Gate::check('mail_upstream_routing_view');
                @endphp
                @if ($canViewMailRelay)
                    <div class="sidenav-menu-heading">Mail Relay Management</div>

                    @can('smtp_mail_relay_view')
                        <a class="nav-link" href="{{ route('smtp_relay.smtp_relay_req') }}">
                            <div class="nav-link-icon"><i data-feather="grid"></i></div>
                            SMTP Relay Request
                            <div class="sidenav-collapse-arrow"></div>
                        </a>
                    @endcan
                    @can('manage_mail_relay_view')
                        <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseMailPages" aria-expanded="false" aria-controls="collapseMailPages">
                            <div class="nav-link-icon"><i data-feather="mail"></i></div>
                            Manage SMTP Relay Request
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse show" id="collapseMailPages" data-bs-parent="#accordionSidenav">
                            <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages" style="background-color: #1c1c1c">
                                <a hidden class="nav-link" href="{{ route('mailrelay.index')}}">View Mail Relay Server</a>
                                <a class="nav-link" href="{{ route('mailrelay.register')}}">Register Mail Relay</a>
                            </nav>
                        </div>
                    @endcan
                    @can('mail_upstream_routing_view')
                        <a class="nav-link" href="{{ route('mailrelay.mailupstream') }}">
                            <div class="nav-link-icon"><i data-feather="grid"></i></div>
                            Mail Upstream Routing
                            <div class="sidenav-collapse-arrow"></div>
                        </a>
                    @endcan
                @endif

                @if (Gate::check('server_overview_view') || Gate::check('manage_service_view'))
                    <div class="sidenav-menu-heading">Services Management</div>
                @endif

                @can('server_overview_view')
                    <a class="nav-link" href="{{ route('log.index') }}">
                        <div class="nav-link-icon"><i data-feather="globe"></i></div>
                        Server Overview
                        <div class="sidenav-collapse-arrow"></div>
                    </a>
                @endcan
                @can('manage_service_view')
                    <a class="nav-link" href="{{ route('manageService.index') }}">
                        <div class="nav-link-icon"><i data-feather="target"></i></div>
                        Manage Services
                        <div class="sidenav-collapse-arrow"></div>
                    </a>
                @endcan

                @if (Gate::check('mail_log_view') || Gate::check('central_log_view'))
                    <div class="sidenav-menu-heading">Review Management</div>
                @endif

                @can('mail_log_view')
                    <a class="nav-link" href="{{ route('mailLog.index') }}">
                        <div class="nav-link-icon"><i data-feather="mail"></i></div>
                        Mail Log Viewer
                        <div class="sidenav-collapse-arrow"></div>
                    </a>
                @endcan

                @can('central_log_view')
                    <a class="nav-link" href="{{ route('centralLog.index') }}">
                        <div class="nav-link-icon"><i data-feather="search"></i></div>
                        Central Log Postmaster
                        <div class="sidenav-collapse-arrow"></div>
                    </a>
                @endcan

                @php
                    $canViewValidation = Gate::check('loopback_smtp_test_view') || Gate::check('dns_watcher_view');
                @endphp

                @if ($canViewValidation)
                    <div class="sidenav-menu-heading">Validation & Verification</div>
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseValidation" aria-expanded="false" aria-controls="collapseValidation">
                        <div class="nav-link-icon"><i data-feather="feather"></i></div>
                        Validation & Verification
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse show" id="collapseValidation" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages" style="background-color: #1c1c1c">
                            @can('loopback_smtp_test_view')
                                <a class="nav-link" href="{{ route('loopback.index') }}">Loopback SMTP Test</a>
                            @endcan
                            @can('dns_watcher_view')
                                <a class="nav-link" href="{{ route('dns.index') }}">DNS SMTP Test</a>
                            @endcan
                        </nav>
                    </div>
                @endif

                @php
                    $canViewSearchIp = Gate::check('search_ip_view');
                    $canViewSearchSasl = Gate::check('search_sasl_view');
                @endphp

                @if ($canViewSearchIp || $canViewSearchSasl)
                    <div class="sidenav-menu-heading">Search Management</div>
                @endif

                @if ($canViewSearchIp)
                    <a class="nav-link" href="{{ route('searchIp.index') }}">
                        <div class="nav-link-icon"><i data-feather="info"></i></div>
                        Search IP & Status
                        <div class="sidenav-collapse-arrow"></div>
                    </a>
                @endif

                @if ($canViewSearchSasl)
                    <a class="nav-link" href="{{ route('searchSasl.index') }}">
                        <div class="nav-link-icon"><i data-feather="user"></i></div>
                        Search Sasl & Status
                        <div class="sidenav-collapse-arrow"></div>
                    </a>
                @endif

                @php
                    $canViewReport = Gate::check('agencies_report_view') || Gate::check('usage_report_view') || Gate::check('query_log_view');
                @endphp
                @if ($canViewReport)
                    <div class="sidenav-menu-heading">Report Management</div>

                    @can('agencies_report_view')
                        <a class="nav-link" href="{{ route('generateReport.index') }}">
                            <div class="nav-link-icon"><i data-feather="trello"></i></div>
                            Agencies Report
                            <div class="sidenav-collapse-arrow"></div>
                        </a>
                    @endcan
                    @can('usage_report_view')
                        <a class="nav-link" href="{{ route('monthlyReport.index') }}">
                            <div class="nav-link-icon"><i data-feather="trello"></i></div>
                            Usage Report
                            <div class="sidenav-collapse-arrow"></div>
                        </a>
                    @endcan
                    @can('query_log_view')
                        <a class="nav-link" href="{{ route('GenerateQueryLog.index') }}">
                            <div class="nav-link-icon"><i data-feather="trello"></i></div>
                            Query Log Report
                            <div class="sidenav-collapse-arrow"></div>
                        </a>
                    @endcan
                @endif
                @php
                    $canViewSysMgmt = Gate::check('server_view') || Gate::check('session_view') || Gate::check('replication_monitoring_view');
                @endphp

                @if ($canViewSysMgmt)
                    <div class="sidenav-menu-heading">System Management</div>

                    @can('server_view')
                        <a class="nav-link" href="{{ route('server.index')}}">
                            <div class="nav-link-icon"><i data-feather="server"></i></div>
                            Server Configuration
                            <div class="sidenav-collapse-arrow"></div>
                        </a>
                    @endcan

                    @can('session_view')
                        <a class="nav-link" href="{{ route('sessions.index')}}">
                            <div class="nav-link-icon"><i data-feather="list"></i></div>
                            Session List
                            <div class="sidenav-collapse-arrow"></div>
                        </a>
                    @endcan

                    @can('replication_monitoring_view')
                        <a class="nav-link" href="{{ route('replicationMonitoring.index')}}">
                            <div class="nav-link-icon"><i data-feather="database"></i></div>
                            Replication Monitoring
                            <div class="sidenav-collapse-arrow"></div>
                        </a>
                    @endcan
                @endif

                @php
                    $canViewSetting = Gate::check('user_view') || Gate::check('role_permission_view') || Gate::check('domain_name_view') || Gate::check('position_department_view') || Gate::check('version_view') || Gate::check('spmr_activity_log_view') ||Gate::check('ministry_view') || Gate::check('agencies_view');
                @endphp
                @if($canViewSetting)
                    <div class="sidenav-menu-heading">Settings</div>
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseAppsetting" aria-expanded="false" aria-controls="collapseAppsetting">
                        <div class="nav-link-icon"><i data-feather="settings"></i></div>
                        Settings
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse show" id="collapseAppsetting" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavAppsMenu" style="background-color: #1c1c1c">
                            @can('user_view')
                                <a class="nav-link" href="{{ route('usermanagements.index') }}">
                                    Application Users
                                    <div class="sidenav-collapse-arrow"></div>
                                </a>
                            @endcan
                            @can('role_permission_view')
                                <a class="nav-link" href="{{ route('setup.role.index')}}">
                                    Roles & Permission
                                    <div class="sidenav-collapse-arrow"></div>
                                </a>
                            @endcan

                            @can('ministry_view')
                                <a class="nav-link" href="{{ route('ministry.index') }}">
                                    Ministry
                                    <div class="sidenav-collapse-arrow"></div>
                                </a>
                            @endcan
                            @can('agencies_view')
                                <a class="nav-link" href="{{ route('agency.index') }}">
                                    Agency
                                    <div class="sidenav-collapse-arrow"></div>
                                </a>
                            @endcan

                            @can('position_department_view')
                                <a class="nav-link" href="{{ route('positiondepartment.index')}}">
                                    Position & Department
                                    <div class="sidenav-collapse-arrow"></div>
                                </a>
                            @endcan

                            @can('version_view')
                                <a class="nav-link" href="{{ route('systeminformation.index')}}">
                                    Version
                                    <div class="sidenav-collapse-arrow"></div>
                                </a>
                            @endcan

                            @can('spmr_activity_log_view')
                                <a class="nav-link" href="{{ route('activityLog.index') }}">
                                    SPMR Activity Log
                                    <div class="sidenav-collapse-arrow"></div>
                                </a>
                            @endcan
                        </nav>
                    </div>
                @endif
            </div>
        </div>
        <!-- Sidenav Footer-->
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Logged in as:</div>
                @auth
                    <div class="sidenav-footer-title">{{ auth()->user()->username }}</div>
                    <div class="sidenav-footer-role">
                        {{ auth()->user()->getRoleNames()->first() ?? '' }}
                    </div>
                @endauth
            </div>
        </div>
    </nav>
</div>
