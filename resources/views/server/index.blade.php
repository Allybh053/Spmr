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
                <div class="card-header">PDSA NAME LOOP HERE</div>
                <div class="card-body">
                    <div class="row col-md-12">
                    @foreach ($dataservers as $dataserver)
                        <div class="col-lg-6">
                            <div class="card card-header-actions mb-4">
                                <div class="card-header text-white {{ $dataserver->status == 1 ? 'bg-success' : 'bg-warning' }}">
                                    {{ $dataserver -> hostname}}
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                        id="flexSwitchCheck{{ $dataserver->id }}"
                                        {{ $dataserver->status == 1 ? 'checked' : '' }}
                                        onclick="updateStatus({{ $dataserver->id }}, this)"/>
                                        <label class="form-check-label" for="flexSwitchCheck{{ $dataserver->id }}">{{ $dataserver->status == 1 ? 'Active' : 'Disconnected' }}</label>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputNotificationEmail">IP Address : {{ $dataserver->ipaddress}}</label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputNotificationEmail">SSH Username : {{ $dataserver->sshusername}}</label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputNotificationEmail">SSH key Path : {{ $dataserver->sshkey}}</label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputNotificationEmail">Created At : {{ $dataserver->created_at->format('d/m/Y')}}</label>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between small">
                                    @can('server_update')
                                    <a class="text-primary" href="{{ route('server.edit', ['id' => $dataserver->id]) }}">Edit Configuration</a>
                                    @endcan
                                    @can('server_delete')
                                            <button class="btn btn-danger delete-btn" data-id="{{ $dataserver->id }}">Delete</button>
                                        @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>


            </div>
                </div>
            </div>
        </div>



</div>
<script>
    function updateStatus(serverId, checkbox) {
        // Check if the server is already active
        // if (checkbox.checked) {
        //     const activeServer = document.querySelector('.form-check-input:checked');
        //     if (activeServer && activeServer !== checkbox) {
        //         alert("One server at a time can be active. Deactivate the current active server before activating another one.");
        //         checkbox.checked = false;
        //         return;
        //     }
        // }

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
            console.log(data);
            location.reload();
        })
        .catch(error => {
            console.error(error);
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
                console.log('Server deleted successfully');
                location.reload();
            })
            .catch(error => {
                console.error('There was a problem with the deletion:', error);
                // Optionally, handle error and display message to user
            });
    }

</script>

</main>
@endsection
