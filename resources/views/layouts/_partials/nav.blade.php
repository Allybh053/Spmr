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
                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseDashboards" aria-expanded="false" aria-controls="collapseDashboards">
                    <div class="nav-link-icon"><i data-feather="activity"></i></div>
                    Dashboards
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseDashboards" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        <a class="nav-link" href="dashboard-1.html">
                            Default
                            <span class="badge bg-primary-soft text-primary ms-auto">Updated</span>
                        </a>
                        <a class="nav-link" href="dashboard-2.html">Multipurpose</a>
                        <a class="nav-link" href="dashboard-3.html">Affiliate</a>
                    </nav>
                </div>
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
                <!-- Sidenav Heading (Config)-->
                <div class="sidenav-menu-heading">Config</div>
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
