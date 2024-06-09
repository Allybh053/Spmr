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
                            Edit Server Config
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4">
        @if(session('message'))
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">Message</h5>
                {{ session('message') }}
                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
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
        <div class="card mb-4">
            <div class="card-header">Edit Server Connection Configuration</div>
            <div class="card-body">
                <strong><h3 style="color:blue;">Virtual Server</h3></strong><hr>
                <form id="formserver" action="{{ route('server.update', ['id' => $server->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="mb-3">
                    <label for="hostname">Server Hostname</label>
                    <input type="hidden" value="{{ $server->id  }}" name="hostname" />
                    {!! Form::select('hostname', $getServer, $server->id ?? null, ['id'=>'hostnamed', 'name'=>'hostnamed', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default'), 'disabled'=>'true']) !!}
                    <span id="hostname-error" class="text-danger"></span>
                </div>
                <div class="mb-3">
                    <label for="ipaddress">IP Address</label>
                    <input class="form-control" id="ipaddress" name="ipaddress" type="text" value="{{ old('ipaddress', $server->ipaddress) }}" readonly>
                </div>
                <hr>
                <strong><h3 style="color:midnightblue;">Primary Server</h3></strong><hr>
                <div  id="subservers-container">
                    @foreach ($subserver as $index=>$sub)
                    <div class="mb-3">
                        <input type="hidden" name="subServers[{{ $index }}][id]" value="{{ $sub->id }}">
                        <label for="primary{{$loop->index + 1}}">Primary Server {{$loop->index + 1}} Hostname</label>
                        <input class="form-control" id="primary{{$loop->index + 1}}name" name="subServers[{{ $index }}][name]" placeholder="Enter Hostname" type="text" value="{{ old('subhostname', $sub->subhostname) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="primary2">Primary Server {{$loop->index + 1}} Ip Address</label>
                        <input class="form-control" id="primary{{$loop->index + 1}}ip" name="subServers[{{ $index }}][ip]" type="text" placeholder="Enter IP Address" value="{{ old('ipaddress', $sub->ipaddress) }}" required>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary" id="add-subserver">Add Primary Server</button><hr>
                <div class="mb-3">
                    <label class="small mb-1" for="inputNotificationEmail">Updated At : {{ \Carbon\Carbon::now('Asia/Kuala_Lumpur')->format('d/m/Y h:i:s') }}</label>
                </div>
                <button class="btn btn-primary me-2 my-1" type="submit">Submit</button>
                <a class="btn btn-danger me-2 my-1" type="button" href="{{ route('server.index')}}">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    <style>
        label {
            font-weight: bold;
        }
    </style>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('subservers-container');
        let subserverIndex = {{ count($subserver) }};

        document.getElementById('add-subserver').addEventListener('click', function () {
              // Remove or adjust the condition to allow more than 2
            if (subserverIndex >= 2) {
                alert('You can only add up to 2 Primary Servers.');
                return;
            }

            subserverIndex++;

            const container = document.getElementById('subservers-container');

            const hostnameDiv = document.createElement('div');
            hostnameDiv.classList.add('mb-3');
            const hostnameLabel = document.createElement('label');
            hostnameLabel.setAttribute('for', `primary${subserverIndex}name`);
            hostnameLabel.textContent = `Primary Server ${subserverIndex} Hostname`;
            const hostnameInput = document.createElement('input');
            hostnameInput.classList.add('form-control');
            hostnameInput.setAttribute('id', `primary${subserverIndex}name`);
            hostnameInput.setAttribute('name', `subServers[${subserverIndex}][name]`);
            hostnameInput.setAttribute('placeholder', 'Enter Hostname');
            hostnameInput.setAttribute('type', 'text');
            //hostnameInput.required = true;
            hostnameDiv.appendChild(hostnameLabel);
            hostnameDiv.appendChild(hostnameInput);

            const ipDiv = document.createElement('div');
            ipDiv.classList.add('mb-3');
            const ipLabel = document.createElement('label');
            ipLabel.setAttribute('for', `primary${subserverIndex}ip`);
            ipLabel.textContent = `Primary Server ${subserverIndex} IP Address`;
            const ipInput = document.createElement('input');
            ipInput.classList.add('form-control');
            ipInput.setAttribute('id', `primary${subserverIndex}ip`);
            ipInput.setAttribute('name', `subServers[${subserverIndex}][ip]`);
            ipInput.setAttribute('placeholder', 'Enter IP Address');
            ipInput.setAttribute('type', 'text');
            //ipInput.required = true;
            ipDiv.appendChild(ipLabel);
            ipDiv.appendChild(ipInput);

            container.appendChild(hostnameDiv);
            container.appendChild(ipDiv);

            // Add event listener to validate IP address uniqueness
            ipInput.addEventListener('input', function () {
                const ipAddress = ipInput.value;
                const existingIPs = Array.from(container.querySelectorAll('input[name$="ip"]')).map(input => input.value);

                if (existingIPs.filter(ip => ip === ipAddress).length > 1) {
                    alert('IP address already keyin.');
                    ipInput.value = '';
                }
            });
        });

        // Add event listeners for existing inputs
        Array.from(document.querySelectorAll('input[name$="ip"]')).forEach(input => {
            input.addEventListener('input', function () {
                const ipAddress = input.value;
                const existingIPs = Array.from(container.querySelectorAll('input[name$="ip"]')).map(input => input.value);

                if (existingIPs.filter(ip => ip === ipAddress).length > 1) {
                    alert('IP address already keyin.');
                    input.value = '';
                }
            });
        });
    });


    function checkHostname() {
    var hostnameInput = document.getElementById('hostname');
    var newHostname = hostnameInput.value;
    var oldHostname = '{{ $server->hostname }}'; // Assuming $server variable holds the server data

    var hostnameError = document.getElementById('hostname-error');

    // Clear previous error message
    hostnameError.textContent = '';
    // If the new hostname is the same as the old one, no need to perform the Ajax request
    if (newHostname === oldHostname) {
        hostnameError.textContent = '';
        hostnameInput.classList.remove('is-invalid');
        return;
    }
    // Perform Ajax request
    $.ajax({
        url: "{{ route('check.hostname.unique') }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            hostname: newHostname
        },
        success: function(response) {
            //console.log(response);
            if (response.exists) {
                hostnameError.textContent = 'Hostname already exists.';
                hostnameInput.classList.add('is-invalid');
            } else {
                hostnameInput.classList.remove('is-invalid');
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
                        console.log(response);
                        $('#ipaddress').val(response.ip_address);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#ipaddress').val('');
            }
        });
    });

</script>
@endsection
