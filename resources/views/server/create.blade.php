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
            <div class="card-header">New Server Connection Configuration</div>
            <div class="card-body">
                <strong><p style="color:blue;">Virtual Server</p></strong><hr>
                <form id="formserver" action="{{ route('upload-server') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="hostname">Server Hostname</label>
                    <input class="form-control" id="hostname" name="hostname" type="text" placeholder="Enter Hostname" value="" required>
                </div>
                <div class="mb-3">
                    <label for="ipaddress">IP Address</label>
                    <input class="form-control" id="ipaddress" name="ipaddress" type="text" placeholder="Enter IP Address" required>
                </div>
                {{-- <hr>
                <strong><p style="color:midnightblue;">Primary Server</p></strong><hr>
                <div class="mb-3">
                    <label for="primary1">Primary Server 1 Hostname</label>
                    <input class="form-control" id="primary1a" name="primary1name" type="text" placeholder="">
                </div>
                <div class="mb-3">
                    <label for="primary2">Primary Server Ip Address</label>
                    <input class="form-control" id="primary1b" name="primary1ip" type="text" placeholder="">
                </div>
                <div class="mb-3">
                    <label for="primary1">Primary Server 2 Hostname</label>
                    <input class="form-control" id="primary2a" name="primary2name" type="text" placeholder="">
                </div>
                <div class="mb-3">
                    <label for="primary2">Primary Server Ip Address</label>
                    <input class="form-control" id="primary2b" name="primary2ip" type="text" placeholder="">
                </div> --}}
                <button class="btn btn-primary me-2 my-1" type="submit">Submit</button>
                <a class="btn btn-danger me-2 my-1" type="button" href="{{ route('server.index')}}">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection