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
                        <a class="btn btn-sm btn-light text-primary new" type="button" data-bs-toggle="modal" data-bs-target="#serverModal">
                            <i class="me-1" data-feather="plus"></i>
                            Add New Virtual Server
                        </a>
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


    <div class="modal fade" id="serverModal" tabindex="-1" role="dialog" aria-labelledby="serverModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Virtual Server</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="ajax-form" novalidate action="{{ route('server.virtualServer.add') }}" method="POST">
                <div class="modal-body">
                <div class="row">
                    {!! Form::hidden('serverId', null, ['id' => 'serverId']) !!}
                    <div class="col-md-6 @if ($errors->has('hostname')) has-error @endif">
                    <label class="small mb-1" for="hostname">Hostname</label>
                        {!! Form::text('hostname', null, ['id' => 'hostname', 'class' => 'form-control', 'required']) !!}
                        @if ($errors->has('hostname')) <p class="help-block">{{ $errors->first('hostname') }}</p> @endif
                    </div>
                    <div class="col-md-6 @if ($errors->has('ipaddress')) has-error @endif">
                        <label class="small mb-1" for="ipaddress">Ip Address</label>
                            {!! Form::text('ipaddress', null, ['id' => 'ipaddress', 'class' => 'form-control','pattern'=>'((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)|([0-9a-fA-F]{1,4}:){7}([0-9a-fA-F]{1,4})', 'required']) !!}
                            @if ($errors->has('ipaddress')) <p class="help-block">{{ $errors->first('ipaddress') }}</p> @endif
                        </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>


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
                                        <div class="d-flex align-items-center justify-content-start">
                                            <div class="form-check form-switch me-3">
                                                <input class="form-check-input" type="checkbox"
                                                    id="flexSwitchCheck{{ $dataserver->id }}"
                                                    {{ $dataserver->status == 1 ? 'checked' : '' }}
                                                    onclick="updateStatus({{ $dataserver->id }}, this)" />
                                                <label class="form-check-label" for="flexSwitchCheck{{ $dataserver->id }}">{{ $dataserver->status == 1 ? 'Enable' : 'Disable' }}</label>
                                            </div>
                                            <a class="btn btn-sm btn-light text-primary rename me-3" data-bs-toggle="modal" data-bs-target="#serverModal" data-id="{{ $dataserver->id }}" data-hostname="{{ $dataserver->hostname }}" data-ipaddress="{{ $dataserver->ipaddress }}">Update Virtual Server</a>
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
       document.addEventListener("DOMContentLoaded", function() {
            $(document).on("click", ".new", function () {
                var serverId = '';
                $(".modal-content #serverId").val(serverId);

                var hostname = '';
                $(".modal-content #hostname").val(hostname);

                var ipaddress = '';
                $(".modal-content #ipaddress").val(ipaddress);
            });

            $(document).on("click", ".rename", function () {
                var serverId = $(this).data('id');
                $(".modal-content #serverId").val(serverId);

                var hostname = $(this).data('hostname');
                $(".modal-content #hostname").val(hostname);

                var ipaddress = $(this).data('ipaddress');
                $(".modal-content #ipaddress").val(ipaddress);
            });

        });
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
    // Check if the serverId exists in the master_id column and deleted_at column is null
    var checkUrl = '/server/check-server/' + serverId;

        fetch(checkUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.exists) {
                    // ServerId does not exist in the master_id column or deleted_at column is not null, show warning
                    Swal.fire({
                        title: 'Cannot delete!',
                        text: 'This server is linked to existing Primary servers.',
                        icon: 'warning',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                } else {
                    // ServerId exists in the master_id column and deleted_at column is null, proceed with deletion
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
            })
            .catch(error => {
                //console.error('There was a problem checking serverId:', error);
                // Optionally, handle error and display message to user
            });
    }



</script>

</main>
@endsection
