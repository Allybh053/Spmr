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

                <!-- <div class="sidenav-menu-heading">Uc Overview</div> -->
                <!-- Sidenav Accordion (Dashboard)-->
                <!-- <a class="nav-link" href="{{ route('uc.index') }}">
                    <div class="nav-link-icon"><i data-feather="activity"></i></div>
                    Uc Overview
                    <div class="sidenav-collapse-arrow"></div>
                </a> -->

                 <!-- Sidenav Menu Heading (Core)-->
                 @php
                 $canViewMailRelay = Gate::check('smtp_mail_relay_view') || Gate::check('manage_mail_relay_view') || Gate::check('mail_upstream_routing_view');
             @endphp
                 @if ($canViewMailRelay)
                 <div class="sidenav-menu-heading">Mail Relay Management</div>

                <!-- Sidenav Link (Charts)-->
                @can('smtp_mail_relay_view')
                <a class="nav-link" href="{{ route('smtp_relay.smtp_relay_req') }}">
                    <div class="nav-link-icon"><i data-feather="grid"></i></div>
                    SMTP Relay Request
                    <div class="sidenav-collapse-arrow"></div>
                </a>
                @endcan
                <!-- Sidenav Link (Mail Relay Module)-->
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
                {{-- <!-- <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseHistory" aria-expanded="false" aria-controls="collapseHistory">
                    <div class="nav-link-icon"><i data-feather="link"></i></div>
                   History Connection
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseHistory" data-bs-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages"> -->
                    <!-- <a class="nav-link" href="{{ route('history.blacklist.index') }}">Blacklist Firewall</a>
                    <a class="nav-link" href="{{ route('history.whitelist.index')}}">Whitelist Firewall</a> -->
                    <!-- <a class="nav-link" href="{{ route('history.fail.index') }}">Fail-to-Ban Blacklist Firewall</a>
                </nav> -->

                <!-- </div> --> --}}


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
                <!-- Sidenav Accordion (Dashboard)-->
                {{--<a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseAppLog" aria-expanded="false" aria-controls="collapseAppLog">
                    <div class="nav-link-icon"><i data-feather="mail"></i></div>
                    Log
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseAppLog" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavAppsMenu">
                    @can('mail_log_view')
                        <a class="nav-link" href="{{ route('mailLog.index') }}">
                            <div class="nav-link-icon"><i data-feather="mail"></i></div>
                            Mail Log Viewer
                            <div class="sidenav-collapse-arrow"></div>
                        </a>
                        @endcan
                        @can('spmr_activity_log_view')
                        <a class="nav-link" href="{{ route('activityLog.index') }}">
                            <div class="nav-link-icon"><i data-feather="eye"></i></div>
                            SPMR Activity Log
                            <div class="sidenav-collapse-arrow"></div>
                        </a>
                        @endcan

                    </nav>
                </div>--}}

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
                    $canViewValidation = Gate::check('loopback_smtp_test_view') || Gate::check('replication_status_view') || Gate::check('print_list_ip_address_view');
                @endphp

                @if ($canViewValidation)
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
                        {{--@can('replication_status_view')
                            <a class="nav-link" href="{{ route('replication.index') }}">Replication Status</a>
                        @endcan--}}
                        {{--@can('print_list_ip_address_view')
                            <a class="nav-link" href="{{ route('printList.index') }}">Print List Ip Address</a>
                        @endcan--}}
                    </nav>
                </div>
                @endif
                @php
                $canViewReport = Gate::check('agencies_report_view') || Gate::check('usage_report_view');
                @endphp
                @if ($canViewReport)
                <div class="sidenav-menu-heading">Report Management</div>

                {{--<a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseReport" aria-expanded="false" aria-controls="collapseReport">
                    <div class="nav-link-icon"><i data-feather="trello"></i></div>
                    Reporting
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseReport" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        @can('traffic_statistic_summary_view')
                            <a class="nav-link" href="{{ route('report.statistic.index') }}">Statistic Traffic Summary</a>
                        @endcan
                        @can('detail_report_sender_view')
                            <a class="nav-link" href="{{ route('report.detail.index') }}">Detail Report Sender</a>
                        @endcan
                        @can('query_log_view')
                            <a class="nav-link" href="{{ route('report.queryLog.index') }}">Query Log</a>
                        @endcan
                    </nav>
                </div>--}}

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
                @endif

                @php
                    $canViewSysMgmt = Gate::check('server_view') || Gate::check('session_view');
                @endphp
                @if ($canViewSysMgmt)
                <!-- Sidenav Heading (Config)-->
                <div class="sidenav-menu-heading">System Management</div>
                <!-- Sidenav Accordion (UC log file)-->
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
                @endif

                {{--<a class="nav-link" href="{{ route('sessions.index')}}">
                    <div class="nav-link-icon"><i data-feather="list"></i></div>
                    Session List
                    <div class="sidenav-collapse-arrow"></div>
                </a>--}}

                @php
                $canViewSetting = Gate::check('user_view') || Gate::check('role_permission_view') || Gate::check('domain_name_view') || Gate::check('position_department_view') || Gate::check('version_view')  || Gate::check('spmr_activity_log_view') ;
                @endphp
                @if($canViewSetting)
                <!-- Sidenav Accordion (Setting)-->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseAppsetting" aria-expanded="false" aria-controls="collapseAppsetting">
                    <div class="nav-link-icon"><i data-feather="settings"></i></div>
                    Settings
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse show" id="collapseAppsetting" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavAppsMenu" style="background-color: #1c1c1c">
                           <!-- Nested Sidenav Accordion (Apps -> User Management)-->
                           <!-- <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#appsCollapseUserManagement" aria-expanded="false" aria-controls="appsCollapseUserManagement">
                            Application Users
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="appsCollapseUserManagement" data-bs-parent="#accordionSidenavAppsMenu">
                            <nav class="sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('usermanagements.index') }}">Users List</a>
                                <a class="nav-link" href="{{ route('usermanagements.create') }}">Add User</a>
                            </nav>
                        </div> -->
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
                        <!-- Nested Sidenav Accordion (Apps -> domain Management)-->
                        <!-- <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#appsCollapseDomainManagement" aria-expanded="false" aria-controls="appsCollapseDomainManagement">
                            Domain Name Registration
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="appsCollapseDomainManagement" data-bs-parent="#accordionSidenavAppsMenu">
                            <nav class="sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('domain.index') }}">FQDN List</a>
                                <a class="nav-link" href="{{ route('domain.register') }}">Add New FQDN</a>
                            </nav>
                        </div> -->
                        @can('domain_name_view')
                        <a class="nav-link" href="{{ route('domain.index') }}" hidden>
                            Domain Name Registration
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
                </div><!-- Assuming 'role' is a direct attribute -->
                @endauth
            </div>
        </div>

    </nav>
</div>
