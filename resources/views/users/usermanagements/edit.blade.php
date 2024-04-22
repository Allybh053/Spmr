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
                            Edit User Account
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
            <a class="nav-link active ms-0" href="account-profile.html">Details</a>
            <!--a class="nav-link" href="account-billing.html">Billing</a>
            <a class="nav-link" href="account-security.html">Security</a>
            <a class="nav-link" href="account-notifications.html">Notifications</a-->
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
                        <form method="POST" action="{{ route('usersmanagements.update', $user->id) }}">
                            @csrf
                            @method('PUT')
                            <!-- Form Group (username)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="username">Username (how the name will appear to other users on the site)</label>
                                <input class="form-control" id="username" name="username" type="text" placeholder="Enter your username" value="{{ old('username', $user->username) }}" oninput="this.value = this.value.toLowerCase()" required/>
                            </div>
                            <!-- Form Row-->
                            <div class="row gx-3 mb-3">
                                <!-- Form Group (first name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="fname">First name</label>
                                    <input class="form-control" id="fname" name="fname" type="text" placeholder="Enter your first name" value="{{ old('fname', $user->fname) }}" required/>
                                </div>
                                <!-- Form Group (last name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="lname">Last name</label>
                                    <input class="form-control" id="lname" name="lname" type="text" placeholder="Enter your last name" value="{{ old('lname', $user->lname) }}" required/>
                                </div>
                            </div>
                                <!-- Form Group (location)-->
                                <div class="md-3">
                                    <label class="small mb-1" for="role_id">Role Type</label>
                                    <select class="form-control select2" name="role_id" id="role_id" aria-label="Default select2" required>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            {{-- </div> --}}
                            <!-- Form Group (email address)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="email">Email address</label>
                                <input class="form-control" id="email" name="email" type="email" placeholder="Enter email address" value="{{ old('email', $user->email) }}" oninput="this.value = this.value.toLowerCase()" required/>
                            </div>
                            <!-- Form Row-->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="position">Position</label>
                                    <select class="form-control select2" name="position_id" aria-label="Default select2" required>
                                        @foreach ($positions as $position)
                                            <option value="{{ $position->id }}" {{ $user->position == $position->id ? 'selected' : '' }}>
                                                {{ $position->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Form Group (phone number)-->
                                <div class="col-md-6" @disabled(true)>
                                    <label class="small mb-1" for="department">Department</label>
                                    <select class="form-control select2" name="department_id" aria-label="Default select2" required>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" {{ $user->department_id == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Form Row-->
                            <div class="row gx-3 mb-3">
                                <!-- Form Group (phone number)-->
                                <div class="col-md-6" @disabled(true)>
                                    <label class="small mb-1" for="phone">Phone No</label>
                                    <input class="form-control" id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}"/>
                                </div>
                            </div>
                            <hr>
                            <div class="card-header">Change Security</div><br>
                            <!-- Form Group (inputPassword)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="password">Password</label>
                                <input class="form-control" id="password" name="password" type="password" placeholder="Enter New Password" value=""  minlength="8"/>
                            </div>
                            <div class="row gx-3 mb-3">
                                <!-- Form Group (organization name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="enabled">Account Status</label>
                                    <select class="form-control select2" name="enabled" aria-label="Default select example">
                                        <option value="1" {{ $user->enabled == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $user->enabled == 0 ? 'selected' : '' }}>Not Active</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Save changes button-->
                            <button class="btn btn-primary" type="submit">Update User</button>
                            <a href="{{ route('usermanagements.index') }}" class="btn btn-secondary">Cancel</a>
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
</main>

@endsection
