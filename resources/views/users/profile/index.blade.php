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
            @if(session('success'))
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading">Message</h5>
                    {{ session('success') }}
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!-- Account page navigation-->
            <nav class="nav nav-borders">
                <a class="nav-link active ms-0" href="{{ route("profile.index")}}">Profile</a>
            </nav>
            <hr class="mt-0 mb-4" />
            <div class="row">
                {{-- <div class="col-xl-4">
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">Profile Picture</div>
                        <div class="card-body text-center">
                            <img class="img-account-profile rounded-circle mb-2" src="assets/img/illustrations/profiles/profile-1.png" alt="" />
                            <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                            <button class="btn btn-primary" type="button">Upload new image</button>
                        </div>
                    </div>
                </div> --}}
                <div class="col-xl-8">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">Account Details</div>
                        <div class="card-body">
                            <form id="passwordForm1" method="POST" action="{{ route('profile.updatedetail', ['id' => $profile->id]) }}">
                                @csrf
                                @method('POST')
                                <!-- Form Group (Name)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="username">Username (how your username will appear to other users on the site)</label>
                                    <input class="form-control" id="username" name="username" type="text" placeholder="Enter your Name" value="{{ old('username', $profile->username) }}" />
                                </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (first name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="fname">First name</label>
                                        <input class="form-control" id="fname" name="fname" type="text" placeholder="Enter your first name" value="{{ old('fname', $profile->fname) }}" />
                                    </div>
                                    <!-- Form Group (last name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="lname">Last name</label>
                                        <input class="form-control" id="lname" name="lname" type="text" placeholder="Enter your last name" value="{{ old('lname', $profile->lname) }}" />
                                    </div>
                                </div>
                                <!-- Form Row        -->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (organization name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="role_id">Account Role</label>
                                        <input class="form-control" id="role_id" name="role_id" type="text" placeholder="" value="{{ old('role_id', optional($profile->role)->name) }}" disabled/>
                                    </div>
                                </div>
                                <!-- Form Row        -->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (organization name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="position_id">Position</label>
                                        <input class="form-control" id="position_id" name="position_id" type="text" placeholder="" value="{{ $profile->position_id ?? '' }}" disabled/>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="department_id">Department</label>
                                        <input class="form-control" id="department_id" name="department_id" type="text" placeholder="" value="{{ $profile->department_id ?? '' }}" disabled/>
                                    </div>
                                </div>
                                <!-- Form Group (email address)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="email">Email address</label>
                                    <input class="form-control" id="email" name="email" type="email" placeholder="Enter your email address" value="{{ old('email', $profile->email) }}" />
                                </div>
                                <!-- Form Group (phone number)-->
                                <div class="col-md-6" @disabled(true)>
                                    <label class="small mb-1" for="phone">Phone No</label>
                                    <input class="form-control" id="phone" name="phone" type="text" value="{{ old('phone', $profile->phone) }}"/>
                                </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (status)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="enabled">status</label>
                                        <select class="form-control" id="enabled" name="enabled" disabled>
                                            <option value="1" {{ $profile->enabled == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $profile->enabled == 0 ? 'selected' : '' }}>No Active</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Save changes button-->
                                <button class="btn btn-primary" type="submit">Save changes</button>
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
                            <form id="passwordForm2" method="POST" action="{{ route('profile.updatepassword', ['id' => $profile->id]) }}">
                                @csrf
                                <!-- Form Group (inputPassword)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="password">Password</label>
                                    <input class="form-control" id="password" name="password" type="password" placeholder="Enter Your New Password" value="" minlength="8"/>
                                </div>
                                <!-- Form Group (first name)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="password2">Confirm Password</label>
                                    <input class="form-control" id="password2" name="password2" type="password" placeholder="Confirm Password" value=""  minlength="8"/>
                                </div>
                                <button class="btn btn-primary" type="button" onclick="validatePassword()">Update Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            label {
                font-weight: bold;
            }
        </style>
        <script>
            function validatePassword() {
                var password = document.getElementById("password").value;
                var confirmPassword = document.getElementById("password2").value;

                if (password === confirmPassword) {
                    // Passwords match, submit the form
                    document.getElementById("passwordForm2").submit();
                } else {
                    // Passwords do not match, show an error message or handle it accordingly
                    alert("Passwords do not match. Please enter matching passwords.");
                }
            }
        </script>
    </main>
@endsection
