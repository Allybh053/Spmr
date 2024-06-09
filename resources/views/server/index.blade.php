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
                            Server Configuration
                        </h1>
                    </div>
                    @can('server_add')
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('server.create') }}">
                            <i class="me-1" data-feather="plus"></i>
                            Add New Server Config
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </header>
<div class="container-xl px-4 mt-4">
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


    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">PDSA Putrajaya</div>
                <div class="card-body">
                    <div class="row col-md-12">
                        @foreach ($dataservers as $dataserver)
                        <!-- Check if the server is a master server (i.e., it has sub-servers) -->
                        {{-- @if($dataserver->subServers->isNotEmpty()) --}}
                            <div class="col-lg-12 mb-4">
                                <div class="card">
                                    <div class="card-header text-white {{ $dataserver->status == 1 ? 'bg-success' : 'bg-warning' }}">
                                        <p style="color: black;">Virtual-server: {{ $dataserver->hostname }}</p>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                            id="flexSwitchCheck{{ $dataserver->id }}"
                                            {{ $dataserver->status == 1 ? 'checked' : '' }}
                                            onclick="updateStatus({{ $dataserver->id }}, this)"/>
                                            <label class="form-check-label" for="flexSwitchCheck{{ $dataserver->id }}">{{ $dataserver->status == 1 ? 'Enable' : 'Disable' }}</label>
                                        </div>
                                    </div>

                                    @can('server_update')
                                    <a class="text-primary" style="margin-left: 30px;margin-top:10px; margin-bottom:5px;" href="{{ route('server.edit', ['id' => $dataserver->id]) }}">Edit Configuration</a>
                                    @endcan

                                    <div class="card-footer d-flex align-items-center justify-content-between small">
                                        @can('server_delete')
                                            <button class="btn btn-danger delete-btn" data-id="{{ $dataserver->id }}" >Delete</button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            @if($dataserver->subServers->isNotEmpty())
                            @foreach ($dataserver->subServers as $subServer)
                                <div class="col-lg-6">
                                    <div class="card card-header-actions mb-4">
                                        <div class="card-header text-white bg-primary {{ $dataserver->status == 1 ? 'bg-success' : 'bg-warning' }}"">
                                            <p style="color: black;">Primary-server: {{ $subServer->subhostname }}</p>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="small mb-1">IP Address: {{ $subServer->ipaddress }}</label>
                                            </div>
                                            <div class="mb-3">
                                                <label class="small mb-1">Status: {{ $subServer->status_cluster == 1 ? 'Active' : 'Passive' }}</label>
                                            </div>
                                        </div>
                                        <div class="card-footer d-flex align-items-center justify-content-between small">
                                            @can('server_delete')
                                                <button class="btn btn-danger delete-btn" data-id="{{ $subServer->id }}">Delete</button>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <hr class="bold-hr">
                            @endif
                    @endforeach
                    </div>
                </div>

                </div>
            </div>
        </div>



</div>

<style>
    label {
        font-weight: bold;
    }
    .bold-hr {
        border: 0;
        border-top: 10px solid black;
        margin: 20px 0;
        /* height: 100px; Adjust the height to make it bolder */
        background-color: black; /* You can change the color as needed */
    }
</style>
<script>
    function updateStatus(serverId, checkbox) {

        // Assuming you have the server/update-status route
        fetch('/server/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                id: serverId,
                status: checkbox.checked ? 1 : 0,
            }),
        })
        .then(response => response.json())
        .then(data => {
            //console.log(data);
            location.reload();
        })
        .catch(error => {
            //console.error(error);
            // Rollback checkbox state on error
            checkbox.checked = !checkbox.checked;
        });
    }


    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                var serverId = this.getAttribute('data-id');
                confirmDelete(serverId); // Call confirmDelete function with server ID
            });
        });
    });

    function confirmDelete(serverId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this server!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteServer(serverId); // Call deleteServer function if user confirms
            }
        });
    }

    // Function to handle deletion with Ajax
    function deleteServer(serverId) {
        var deleteUrl = '/server/servers/' + serverId;

        fetch(deleteUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                //console.log('Server deleted successfully');
                location.reload();
            })
            .catch(error => {
                //console.error('There was a problem with the deletion:', error);
                // Optionally, handle error and display message to user
            });
    }

</script>

</main>
@endsection
