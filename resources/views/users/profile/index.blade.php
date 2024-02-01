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
                                Account Settings - Profile
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-xl px-4 mt-4">
            <!-- Account page navigation-->
            <nav class="nav nav-borders">
                <a class="nav-link active ms-0" href="account-profile.html">Profile</a>
                <!--a class="nav-link" href="account-billing.html">Billing</a>
                <a class="nav-link" href="account-security.html">Security</a>
                <a class="nav-link" href="account-notifications.html">Notifications</a-->
            </nav>
            <hr class="mt-0 mb-4" />
            <div class="row">
                <div class="col-xl-4" hidden @disabled(true)>
                    <!-- Profile picture card-->
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">Profile Picture</div>
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
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">Account Details</div>
                        <div class="card-body">
                            <form >
                                @csrf
                                <!-- Form Group (Name)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputName">Name (how your name will appear to other users on the site)</label>
                                    <input class="form-control" id="inputName" type="text" placeholder="Enter your Name" value="{{ $user->username }}" />
                                </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3" hidden @disabled(true)>
                                    <!-- Form Group (first name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputFirstName">First name</label>
                                        <input class="form-control" id="inputFirstName" type="text" placeholder="Enter your first name" value="Valerie" />
                                    </div>
                                    <!-- Form Group (last name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputLastName">Last name</label>
                                        <input class="form-control" id="inputLastName" type="text" placeholder="Enter your last name" value="Luna" />
                                    </div>
                                </div>
                                <!-- Form Row        -->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (organization name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="acct_types">Account Type</label>
                                        <input class="form-control" id="acct_types" type="text" placeholder="" value="{{ $user->acct_types}}" readonly/>
                                    </div>
                                    <!-- Form Group (location)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="role">Role Type</label>
                                        <input class="form-control" id="role" type="text" placeholder="" value="{{ $user->role_id}}" readonly/>
                                    </div>
                                </div>
                                <!-- Form Group (email address)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputEmailAddress">Email address</label>
                                    <input class="form-control" id="inputEmailAddress" type="email" placeholder="Enter your email address" value="{{ $user->email }}" />
                                </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (status)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputStatus">status</label>
                                        <select class="form-control" id="inputStatus" name="enabled">
                                            <option value="1" {{ $user->enabled == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $user->enabled == 0 ? 'selected' : '' }}>No Active</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Save changes button-->
                                <button class="btn btn-primary" type="button">Save changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4" hidden @disabled(true)>
                    <!-- Profile picture card-->
                    <div class="card mb-4 mb-xl-0">
                    </div>
                </div>
                <div class="col-xl-8">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">Update Password</div>
                        <div class="card-body">
                            <form>
                                @csrf
                                <!-- Form Group (inputPassword)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputPassword">Password</label>
                                    <input class="form-control" id="inputPassword" type="password" placeholder="Enter Your New Password" value="" />
                                </div>
                                <!-- Form Group (first name)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputPassword2">Confirm Password</label>
                                    <input class="form-control" id="inputPassword2" type="password" placeholder="Confirm Password" value="" />
                                </div>
                                <button class="btn btn-primary" type="button">Update Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
