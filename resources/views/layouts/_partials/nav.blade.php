<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
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
                <div class="sidenav-menu-heading">Dashboard</div>
                <!-- Sidenav Accordion (Dashboard)-->
                <a class="nav-link" href="{{ route('dashboard.index')}}">
                    <div class="nav-link-icon"><i data-feather="activity"></i></div>
                    Dashboards
                    <div class="sidenav-collapse-arrow"></div>
                </a>
                <!-- Sidenav Link (Charts)-->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseSMTPPages" aria-expanded="false" aria-controls="collapseSMTPPages">
                    <div class="nav-link-icon"><i data-feather="grid"></i></div>
                    SMTP (Mail Relay)
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseSMTPPages" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <!--<a class="nav-link" href="dashboard-1.html">
                            Register New
                        </a>-->
                        <a class="nav-link" href="{{ route('smtp_relay.smtp_relay_req') }}">
                            SMTP Relay Request
                        </a>
                        <a class="nav-link" href="dashboard-2.html">SMTP Listing</a>
                    </nav>
                </div>
                <!-- Sidenav Link (Mail Relay Module)-->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseMailPages" aria-expanded="false" aria-controls="collapseMailPages">
                    <div class="nav-link-icon"><i data-feather="mail"></i></div>
                    Manage Mail Relay
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseMailPages" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="{{ route('mailrelay.index')}}">View Mail Relay Server</a>
                        <a class="nav-link" href="{{ route('mailrelay.register')}}">Register Mail Relay</a>
                    </nav>
                </div>


                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseHistory" aria-expanded="false" aria-controls="collapseHistory">
                    <div class="nav-link-icon"><i data-feather="mail"></i></div>
                   History Connection
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseHistory" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="#">Blacklist Firewall</a>
                        <a class="nav-link" href="#">Whitelist Firewall</a>
                        <a class="nav-link" href="#">Fail-to-Ban Blacklist Firewall</a>
                    </nav>
                </div>


                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseServices" aria-expanded="false" aria-controls="collapseServices">
                    <div class="nav-link-icon"><i data-feather="radio"></i></div>
                   Services
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseServices" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="#">Check Status</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseUcOverview" aria-expanded="false" aria-controls="collapseUcOverview">
                    <div class="nav-link-icon"><i data-feather="mail"></i></div>
                   UC Overview
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseUcOverview" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="#">View Register Application IP</a>
                        <a class="nav-link" href="#">View Register Domain Map</a>
                        <a class="nav-link" href="#">View Register Username</a>
                        <a class="nav-link" href="#">View List Wildcard</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseLog" aria-expanded="false" aria-controls="collapseLog">
                    <div class="nav-link-icon"><i data-feather="mail"></i></div>
                   Log
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLog" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="#">Chart</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseReport" aria-expanded="false" aria-controls="collapseReport">
                    <div class="nav-link-icon"><i data-feather="mail"></i></div>
                   Report
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseReport" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="#">Statistic Traffic Summary</a>
                        <a class="nav-link" href="#">Detail Report Sender</a>
                    </nav>
                </div>

                <!-- Sidenav Heading (Config)-->
                <div class="sidenav-menu-heading">Config</div>
                <!-- Sidenav Accordion (UC log file)-->
                <a class="nav-link" href="{{ route('dashboard.index')}}">
                    <div class="nav-link-icon"><i data-feather="server"></i></div>
                    Server Config
                    <div class="sidenav-collapse-arrow"></div>
                </a>
                <!-- Sidenav Accordion (Setting)-->
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseAppsetting" aria-expanded="false" aria-controls="collapseAppsetting">
                    <div class="nav-link-icon"><i data-feather="globe"></i></div>
                    Setting
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseAppsetting" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavAppsMenu">
                        <!-- Nested Sidenav Accordion (Apps -> Role Management)-->
                        <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#appsCollapseRoleManagement" aria-expanded="false" aria-controls="appsCollapseRoleManagement">
                            Roles Management
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="appsCollapseRoleManagement" data-bs-parent="#accordionSidenavAppsMenu">
                            <nav class="sidenav-menu-nested nav">
                                <a class="nav-link" href="user-management-list.html">Roles List</a>
                                <a class="nav-link" href="user-management-add-user.html">Add Role</a>
                                <a class="nav-link" href="user-management-groups-list.html">Permission List</a>
                                <a class="nav-link" href="user-management-org-details.html">Add Permission</a>
                            </nav>
                        </div>
                        <!-- Nested Sidenav Accordion (Apps -> User Management)-->
                        <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#appsCollapseUserManagement" aria-expanded="false" aria-controls="appsCollapseUserManagement">
                            Appplication User
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="appsCollapseUserManagement" data-bs-parent="#accordionSidenavAppsMenu">
                            <nav class="sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('usermanagements.index') }}">Users List</a>
                                <a class="nav-link" href="{{ route('usermanagements.create') }}">Add User</a>
                            </nav>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Sidenav Footer-->
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Logged in as:</div>
                @auth
                <div class="sidenav-footer-title">{{ auth()->user()->username }}</div>
                @endauth
            </div>
        </div>
    </nav>
</div>
