@extends('layouts.app')
@section('content')
<main>
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="server"></i></div>
                            Add New Server Config
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4">
        <div class="card mb-4">
            @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 3000  // Automatically close after 3 seconds
                    });
                });
            </script>
            @endif

            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'error',
                            title: '',
                            text: '{{ session('error') }}',
                            showConfirmButton: false,
                            timer: 3000  // Automatically close after 3 seconds
                        });
                    });
                </script>
            @endif

            <div class="card-header">New Server Connection Configuration</div>
            <div class="card-body">
                <strong><h3 style="color:blue;">Virtual Server</h3></strong><hr>
                <form id="formserver" action="{{ route('upload-server') }}" method="post" enctype="multipart/form-data"  onsubmit="return validateForm()">
                @csrf
                <div class="mb-3">
                    <label for="hostname">Server Hostname</label>
                    {!! Form::select('hostname', $getServer, $server->master_id ?? null, ['id'=>'hostname', 'name'=>'hostname', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                    <span id="hostname-error" class="text-danger"></span>
                </div>
                <div class="mb-3">
                    <label for="ipaddress">IP Address</label>
                    <input class="form-control" id="ipaddress" name="ipaddress" type="text" placeholder="" value="" pattern="((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)|([0-9a-fA-F]{1,4}:){7}([0-9a-fA-F]{1,4})" readonly>
                    <small class="form-text text-muted">Enter a valid IPv4 or IPv6 address.</small>
                </div>
                <hr>
                <strong><h3 style="color:midnightblue;">Primary Server</h3></strong><hr>
                <div class="mb-3">
                    <label for="primary1">Primary Server 1 Hostname</label>
                    <input class="form-control" id="primary1a" name="primary1name" placeholder="Enter Hostname" type="text" value="" required>
                </div>
                <div class="mb-3">
                    <label for="primary2">Primary Server Ip Address</label>
                    <input class="form-control" id="primary1b" name="primary1ip" type="text" placeholder="Enter IP Address" pattern="((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)|([0-9a-fA-F]{1,4}:){7}([0-9a-fA-F]{1,4})" required>
                    <small class="form-text text-muted">Enter a valid IPv4 or IPv6 address.</small>
                </div>
                <div class="mb-3">
                    <label for="primary1">Primary Server 2 Hostname</label>
                    <input class="form-control" id="primary2a" name="primary2name" placeholder="Enter Hostname" type="text" value="">
                </div>
                <div class="mb-3">
                    <label for="primary2">Primary Server Ip Address</label>
                    <input class="form-control" id="primary2b" name="primary2ip" type="text" placeholder="Enter IP Address" pattern="((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)|([0-9a-fA-F]{1,4}:){7}([0-9a-fA-F]{1,4})">
                    <small class="form-text text-muted">Enter a valid IPv4 or IPv6 address.</small>
                </div>
                <button class="btn btn-primary me-2 my-1" type="submit">Submit</button>
                <a class="btn btn-danger me-2 my-1" type="button" href="{{ route('server.index')}}">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</main>
<style>
    label {
        font-weight: bold;
    }
    .is-invalid {
        border-color: #dc3545;
    }
</style>
<script>
function validateForm() {
    const ipv4Pattern = /^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
    const ipv6Pattern = /^([0-9a-fA-F]{1,4}:){7}([0-9a-fA-F]{1,4})$/;

    const ipaddress = document.getElementById('ipaddress');
    const primary1ip = document.getElementById('primary1b');
    const primary2ip = document.getElementById('primary2b');

    let valid = true;

    // Reset invalid class
    ipaddress.classList.remove('is-invalid');
    primary1ip.classList.remove('is-invalid');
    primary2ip.classList.remove('is-invalid');

    // Check for valid IPv4 or IPv6 addresses
    if (!ipv4Pattern.test(ipaddress.value) && !ipv6Pattern.test(ipaddress.value)) {
        alert('Please enter a valid IPv4 or IPv6 address for IP Address.');
        ipaddress.classList.add('is-invalid');
        valid = false;
    }
    if (!ipv4Pattern.test(primary1ip.value) && !ipv6Pattern.test(primary1ip.value)) {
        alert('Please enter a valid IPv4 or IPv6 address for Primary Server IP Address 1.');
        primary1ip.classList.add('is-invalid');
        valid = false;
    }
    if (primary2ip.value && (!ipv4Pattern.test(primary2ip.value) && !ipv6Pattern.test(primary2ip.value))) {
        alert('Please enter a valid IPv4 or IPv6 address for Primary Server IP Address 2.');
        primary2ip.classList.add('is-invalid');
        valid = false;
    }


    // Check for duplicate IP addresses
    if (ipaddress.value === primary1ip.value || ipaddress.value === primary2ip.value || (primary1ip.value && primary1ip.value === primary2ip.value)) {
        alert('IP addresses must be unique.');
        if (ipaddress.value === primary1ip.value) {
            ipaddress.classList.add('is-invalid');
            primary1ip.classList.add('is-invalid');
        }
        if (ipaddress.value === primary2ip.value) {
            ipaddress.classList.add('is-invalid');
            primary2ip.classList.add('is-invalid');
        }
        if (primary1ip.value === primary2ip.value) {
            primary1ip.classList.add('is-invalid');
            primary2ip.classList.add('is-invalid');
        }
        valid = false;
    }

    return valid;
}

function checkHostname() {
    var hostname = document.getElementById('hostname').value;
    var hostnameError = document.getElementById('hostname-error');

    // Clear previous error message
    hostnameError.textContent = '';

    // Perform Ajax request
    $.ajax({
        url: "{{ route('check.hostname.unique') }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            hostname: hostname
        },
        success: function(response) {
            console.log(response);
            if (response.exists) {
                hostnameError.textContent = 'Hostname already exists.';
                document.getElementById('hostname').classList.add('is-invalid');
            } else {
                document.getElementById('hostname').classList.remove('is-invalid');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}
document.addEventListener('DOMContentLoaded', function () {
        $('#hostname').on('change', function() {
            var selectedHostname = $(this).val();
            if (selectedHostname) {
                // Make an AJAX request to fetch the IP address based on the selected hostname
                $.ajax({
                    url: '/server/get-ip-address', // Replace with the actual endpoint to fetch IP address
                    method: 'GET',
                    data: { hostname: selectedHostname },
                    success: function(response) {
                        // console.log(response);
                        $('#ipaddress').val(response.ip_address);
                    },
                    error: function(xhr, status, error) {
                        // console.error(error);
                    }
                });
            } else {
                $('#ipaddress').val('');
            }
        });
    });
</script>
@endsection
