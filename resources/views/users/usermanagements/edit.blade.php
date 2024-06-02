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
        </nav>
        <hr class="mt-0 mb-4" />
        <div class="row">
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
                                <input readonly class="form-control" id="username" name="username" type="text" placeholder="Enter your username" value="{{ old('username', $user->username) }}" oninput="validateInput(this)" required/>
                                <span id="errorMessage" style="color: red;"></span>
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
                                <input readonly class="form-control" id="email" name="email" type="email" placeholder="Enter email address" value="{{ old('email', $user->email) }}" oninput="this.value = this.value.toLowerCase()" onchange="handleEmailChange()" required/>
                            </div>
                            <!-- Form Row-->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="position">Position</label>
                                    <select class="form-control select2" id="position_id" name="position_id" aria-label="Default select2" required>
                                        <option value="{{ $user->position_id }}" {{ $user->position_id ? 'selected' : '' }}>{{ $user->position_id }}</option>
                                        @foreach ($positions as $position)
                                            <option value="{{ $position->name }}">
                                                {{ $position->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Form Group (phone number)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="department">Department</label>
                                    <select class="form-control select2" id="department_id" name="department_id" aria-label="Default select2" required>
                                        <option value="{{ $user->department_id }}" {{ $user->department_id ? 'selected' : '' }}>{{ $user->department_id }}</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->name }}">
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Form Row-->
                            <div class="row gx-3 mb-3">
                                <!-- Form Group (phone number)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="phone">Phone No</label>
                                    <input class="form-control" id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" minlength="10" />
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
                                        <option value="0" {{ $user->enabled == 0 ? 'selected' : '' }}>Inactive</option>
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
@push('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script>

function allowOnlyNumbers(event) {
        var charCode = event.which ? event.which : event.keyCode;
        // Only allow number keys (0-9) and control keys (backspace, delete, arrow keys)
        if (charCode < 48 || charCode > 57) {
            event.preventDefault();
        }
    }

    // Attach the event listener to the phone input field
document.getElementById('phone').addEventListener('keypress', allowOnlyNumbers);

function handleEmailChange() {
    const emailInput = document.getElementById('email');
    let emailValue = emailInput.value;
    emailValue = emailValue.replace(/[_-]/g, '');

    const usernameInput = document.getElementById('username');
    usernameInput.value = emailValue;
}


    function validateInput(input) {
        // Regular expression to match only alias, underscore, numbering, and letters (uppercase or lowercase)
        var regex = /^[a-zA-Z0-9_@]+$/;
        var inputValue = input.value;
        var errorMessage = document.getElementById('errorMessage');

        if (regex.test(inputValue)) {
            errorMessage.textContent = ''; // Clear error message if input is valid
        } else {
            errorMessage.textContent = 'Only letters, numbers, underscore, and alias are allowed.';
            // Remove invalid characters from the input
            input.value = inputValue.replace(/[^a-zA-Z0-9_]/g, '');
        }
    }

    $(document).ready(function() {


$(".select2").select2();

    $("#formserver").validate({
        errorPlacement: function(label, element) {
            if (element.hasClass('select2')) {
                label.insertAfter(element.next('.select2-container')).addClass('mt-2 text-danger');
            } else {
                label.addClass('mt-2 text-danger');
                label.insertAfter(element);
            }
        },
        highlight: function(element) {
            $(element).parent().addClass('is-invalid');
            $(element).addClass('form-control-danger');
        },
        unhighlight: function(element) {
            $(element).parent().removeClass('is-invalid');
            $(element).removeClass('form-control-danger');
        },
        success: function(label, element) {
            if ($(element).hasClass('select2-hidden-accessible')) {
                label.remove();
            } else {
                label.remove();
            }
        }
    });
    $('#role_id').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
        }
    });
    $('#position_id').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
        }
    });
    $('#department_id').on('change', function() {
        var isValid = $(this).valid();
        if (isValid) {
            $(this).removeClass('is-invalid');
        }
    });

function matchCustom(params, data) {
            // If there are no search terms, return all data
            if ($.trim(params.term) === '') {
                return data;
            }

            // Do not display the item if there is no 'text' property
            if (typeof data.text === 'undefined') {
                return null;
            }

            // Check if the search term is contained in the text
            if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                return data;
            }

            // Return `null` if the term should not be displayed
            return null;
        }

        $('#position_id').select2({
            matcher: matchCustom,
            placeholder: 'Select an option',
            allowClear: true
        });
        $('#department_id').select2({
            matcher: matchCustom,
            placeholder: 'Select an option',
            allowClear: true
        });
    });
</script>
@endpush
@endsection
