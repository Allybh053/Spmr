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
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('server.create') }}">
                            <i class="me-1" data-feather="plus"></i>
                            Add New Server Config
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
<div class="container-xl px-4 mt-4">
    @if(session('success'))
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">Message</h5>
            {{ session('success') }}
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @foreach ($dataservers as $dataserver)
    <div class="row">
        <div class="col-lg-8">
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
                    <a class="text-primary" href="{{ route('server.edit', ['id' => $dataserver->id]) }}">Edit Configuration</a>
                </div>
            </div>
        </div>
    </div>

    @endforeach
</div>
<script>
    function updateStatus(serverId, checkbox) {
        // Check if the server is already active
        if (checkbox.checked) {
            const activeServer = document.querySelector('.form-check-input:checked');
            if (activeServer && activeServer !== checkbox) {
                alert("One server at a time can be active. Deactivate the current active server before activating another one.");
                checkbox.checked = false;
                return;
            }
        }

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
</script>

</main>
@endsection