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
                            Add New User Account
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
                <!-- Account details card-->
                <div class="card mb-4">
                    <div class="card-header">Account Details</div>
                    <div class="card-body">
                        <form id="formserver" name="formserver" method="POST" action="{{ route('usersmanagements.store') }}">
                            @csrf
                            <!-- Form Group (username)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="username">Username (how the name will appear to other users on the site)</label>
                                <input class="form-control" id="username" name="username" type="text" placeholder="Enter your username" value="" oninput="this.value = this.value.toLowerCase()" required/>
                            </div>
                            <!-- Form Row-->
                            <div class="row gx-3 mb-3">
                                <!-- Form Group (first name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="fname">First name</label>
                                    <input class="form-control" id="fname" name="fname" type="text" placeholder="Enter your first name" value="" required/>
                                </div>
                                <!-- Form Group (last name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="lname">Last name</label>
                                    <input class="form-control" id="lname" name="lname" type="text" placeholder="Enter your last name" value="" required/>
                                </div>
                            </div>
                            <!-- Form Row        -->
                            {{-- <div class="row gx-3 mb-3"> --}}
                                <!-- Form Group (organization name)-->
                                <div class="col-md-6" hidden @disabled(true)>
                                    <label class="small mb-1" for="acct_types">Account Type</label>
                                    <select class="form-select" name="acct_types" aria-label="Default select ">
                                        <option selected disabled>Select Account Type:</option>
                                        <option value="1">xxxx</option>
                                        <option value="2">xxxx</option>
                                        <option value="3">xxxx</option>
                                        <option value="4">xxxx</option>
                                    </select>
                                </div>
                                <!-- Form Group (location)-->
                                <div class="md-3">
                                    <label class="small mb-1" for="role_id">Role Type</label>
                                    <select class="form-control select2" id="role_id" name="role_id" aria-label="Default select" required>
                                        <option selected disabled>Select Role</option>
                                        @foreach ($roles as $roleId => $roleName)
                                        <option value="{{ $roleId }}">{{ $roleName }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            {{-- </div> --}}
                            <!-- Form Group (email address)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="email">Email address</label>
                                <input class="form-control" id="email" name="email" type="email" placeholder="Enter email address" value="" oninput="this.value = this.value.toLowerCase()" required/>
                            </div>
                            <!-- Form Row-->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="position">Position</label>
                                    <select class="form-control select2" name="position" aria-label="Default select2" required>
                                        <option selected disabled>Select Position:</option>
                                        @foreach ($positions as $position)
                                        <option value="{{ $position->name }}">{{ $position->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Form Group (phone number)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="department">Department</label>
                                    <select class="form-control select2" name="department" aria-label="Default select2" required>
                                        <option selected disabled>Select Department:</option>
                                        @foreach ($departments as $department)
                                        <option value="{{ $department->name }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <!-- Form Group (phone number)-->
                                <div class="col-md-6" @disabled(true) hidden>
                                    <label class="small mb-1" for="ldap_authentication">LDAP Authentication</label>
                                    <select class="form-select" name="ldap_authentication" aria-label="Default select " required>
                                        <option selected disabled>Select Status:</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <!-- Form Group (phone number)-->
                                <div class="col-md-6" @disabled(true)>
                                    <label class="small mb-1" for="phone">Phone No</label>
                                    <input class="form-control" id="phone" name="phone" type="text" value=""/>
                                </div>
                            </div>
                            <!-- Form Group (inputPassword)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="password">Password</label>
                                <input class="form-control" id="password" name="password" type="password" placeholder="Enter New Password" value="" minlength="8" required/>
                            </div>
                            <div class="row gx-3 mb-3">
                                <!-- Form Group (organization name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="enabled">Account Status</label>
                                    <select class="form-control select2" name="enabled" aria-label="Default select " required>
                                        <option selected disabled>Select Account Status:</option>
                                        <option value="1">Active</option>
                                        <option value="0">In-Active</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Save changes button-->
                            <a href="{{ route('usermanagements.index') }}" class="btn btn-danger">
                                <i class="me-1" data-feather="arrow-left"></i> Cancel
                            </a>
                            <button class="btn btn-primary" type="submit">Add User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script>
var mySelect2 = $('#type_reqs');

mySelect2.select2();

$("#formserver").validate({

rules: {
//   systemtype: {
//     required: true
//   }
},
messages: {
//   systemtype: {
//     required: "Please choose the system type",
//   },

},
errorPlacement: function(label, element) {
  if (element.hasClass('select2')) {
    label.insertAfter(element.next('.select2-container')).addClass('mt-2 text-danger');
    select2label = label
  } else {
    label.addClass('mt-2 text-danger');
    label.insertAfter(element);
  }
},
highlight: function(element) {
  $(element).parent().addClass('is-invalid')
  $(element).addClass('form-control-danger')
},
success: function(label, element) {
  $(element).parent().removeClass('is-invalid')
  $(element).removeClass('form-control-danger')
  label.remove();
}
});

mySelect2.on("change", function(e) {
  select2label.remove(); //remove label
});

        document.getElementById('userForm').addEventListener('submit', function(event) {
            var requiredFields = document.querySelectorAll('#userForm [required]');
            var formValid = true;

            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    formValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!formValid) {
                event.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    </script>
    @endpush
    <style>
        label {
            font-weight: bold;
        },
        .is-invalid {
            border-color: red;
        }
    </style>
</main>

@endsection
