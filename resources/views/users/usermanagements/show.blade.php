@extends('layouts.app')
@section('content')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="user"></i></div>
                            User Account
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="{{route('usermanagements.index')}}">
                            <i class="me-1" data-feather="arrow"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main page content-->
    <div class="container-xl px-4 mt-4">
        <nav class="nav nav-borders">
            <a class="nav-link active ms-0" href="account-profile.html">Details</a>
        </nav>
        <hr class="mt-0 mb-4" />
        <div class="row">
            <div class="col-xl-4" @disabled(true) hidden>
                <!-- Profile picture card-->
                <div class="card mb-4 mb-xl-0">
                    <div class="card-header">User Picture</div>
                    <div class="card-body text-center">
                        <!-- Profile picture image-->
                        <img class="img-account-profile rounded-circle mb-2" src="assets/img/illustrations/profiles/profile-1.png" alt="" />
                        <!-- Profile picture help block-->
                        <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                        <!-- Profile picture upload button-->
                        <button class="btn btn-primary" type="button">Upload new image</button>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <!-- Account details card -->
                <div class="card mb-4">
                    <div class="card-header">Account Details</div>
                    <div class="card-body">
                            <!-- Form Group (username)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="username">Username (how the name will appear to other users on the site)</label>
                                <p>{{ $user->username }}</p>
                            </div>
                            <!-- Form Row        -->
                            <div class="row gx-3 mb-3">
                                <!-- Form Group (organization name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="fname">First Name</label>
                                    <p>{{ $user->fname }}</p>
                                </div>
                                <!-- Form Group (location)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="lname">Last Name</label>
                                    <p>{{ $user->lname }}</p>
                                </div>
                            </div>
                            <!-- Form Row        -->
                            <div class="row gx-3 mb-3">
                                <!-- Form Group (location)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="role_id">Role Type</label>
                                    <p>
                                        @if($user->role)
                                            {{ $user->role->name }}
                                        @else
                                            No role assigned for this user.
                                        @endif
                                    </p>
                                </div>
                                <!-- Form Group (organization name)-->
                                <div class="col-md-6" hidden @disabled(true)>
                                    <label class="small mb-1" for="acct_types">Account Type</label>
                                    <p>{{ $user->acct_types }}</p>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <!-- Form Group (organization name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="acct_types">Position</label>
                                    <p>{{ $user->position_id }}</p>
                                </div>
                                <!-- Form Group (location)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="role_id">Department</label>
                                    <p>{{ $user->department_id }}</p>
                                </div>
                            </div>
                            <!-- Form Group (email address)-->
                            <!-- Form Row-->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="email">Email address</label>
                                    <p>{{ $user->email }}</p>
                                </div>
                                <!-- Form Group (phone number)-->
                                <div class="col-md-6" @disabled(true)>
                                    <label class="small mb-1" for="phone">Phone No</label>
                                    <p>{{ $user->phone }}</p>
                                </div>
                            </div>
                            <!-- Form Row-->
                            <div class="row gx-3 mb-3">
                                <!-- Form Group (phone number)-->
                                <div class="col-md-6" hidden @disabled(true)>
                                    <label class="small mb-1" for="ldap_authentication">LDAP Authentication</label>
                                    <p>{{ $user->ldap_authentication }}</p>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <!-- Form Group (organization name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="enable">Account Status</label>
                                    <p>{{ $user->enabled == 1 ? 'Active' : 'Inactive' }}</p>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
    p {
        color: blue;
    }
    </style>
</main>

@endsection
