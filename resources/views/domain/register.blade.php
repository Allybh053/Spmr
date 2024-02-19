@extends('layouts.app')
@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="cloud"></i></div>
                               Add Domain
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="container-xl px-4">
            <div class="card mb-4">
                <div class="card-header">Domain</div>
                <div class="card-body">
                    <form method="POST" action="#">
                    @csrf
                    @method('POST')
                        <!-- Form Row-->
                        <div class="row gx-3 mb-3">
                            <!-- Form Group (first name)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="fqdn">FQDN</label>
                                <input class="form-control" id="fqdn" name="fqdn" type="text" placeholder="Enter FQDN" value="" required/>
                            </div>
                            <!-- Form Group (last name)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="ministry">Ministry</label>
                                <input class="form-control" id="ministry" name="ministry" type="text" placeholder="Enter Ministry Name" value="" required/>
                            </div>
                            <!-- Form Group (username)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="agency">Agency</label>
                                <input class="form-control" id="agency" name="agency" type="text" placeholder="Enter Agency Name" value="" required/>
                            </div>
                            <!-- Form Group (username)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="address">Address</label>
                                <textarea class="lh-base form-control" id="address" name="address" type="text" placeholder="Enter address..." rows="4"></textarea>
                            </div>
                            <div class="form-group col-md-4">
                            <label for="enabled">Status</label>
                            <select id="enabled" name="enabled" class="form-control" required>
                                <option selected>Choose...</option>
                                <option>Active</option>
                                <option>In-Active</option>
                            </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
