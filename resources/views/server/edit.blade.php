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
        <div class="card mb-4">
            <div class="card-header">Edit Server Connection Configuration</div>
            <div class="card-body">
                <form id="formserver" action="{{ route('server.update', ['id' => $server->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="mb-3">
                    <label for="hostname">Server Hostname</label>
                    <input class="form-control" id="hostname" name="hostname" type="text" value="{{ old('hostname', $server->hostname) }}" required>
                </div>
                <div class="mb-3">
                    <label for="ipaddress">IP Address</label>
                    <input class="form-control" id="ipaddress" name="ipaddress" type="text" value="{{ old('ipaddress', $server->ipaddress) }}" required>
                </div>
                <div class="mb-3" hidden @disabled(true)>
                    <label for="sshusername">SSH Username</label>
                    <input class="form-control" id="sshusername" name="sshusername" type="text" value="{{ old('sshusername', $server->sshusername) }}" required>
                </div>
                <div class="mb-3" hidden @disabled(true)>
                    <label>Current SSH Key : <a href="{{ route('server.file.download', ['id' => $server->id]) }}" target="_blank">{{ $server->sshkey }}</a></label><hr>
                    <label for="sshkey">New SSH Key</label>
                    <input class="form-control" type="file" name="sshkey" id="sshkey" accept=".pem"/>
                    <span class="text-xs text-danger">Upload File Key format .pem</span>
                </div>
                <button class="btn btn-primary me-2 my-1" type="submit">Submit</button>
                <a class="btn btn-danger me-2 my-1" type="button" href="{{ route('server.index')}}">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection