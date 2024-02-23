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
                               Edit FQDN
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="container-xl px-4">
            <div class="card mb-4">
                <div class="card-header">FQDN</div>
                <div class="card-body">
                    <form class="ajax-form" novalidate method="POST" action="{{ route('domain.update', $fqdndata->id) }}">
                        @csrf
                        <!-- Form Row-->
                        <div class="row gx-3 mb-3">
                            <!-- Form Group (first name)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="fqdn">Full Qualified Domain Name (FQDN)</label>
                                <input class="form-control" id="fqdn" name="fqdn" type="text" placeholder="Enter FQDN" value="{{ old('fqdn', $fqdndata->domain_name) }}" />
                            </div>
                            <!-- Form Group (last name)-->
                            <div class="col-md-6">
                                <label class="small mb-1" for="ministry">Ministry</label>
                                <input class="form-control" id="ministry" name="ministry" type="text" placeholder="Enter Ministry Name" value="{{ old('ministry', $fqdndata->ministry_name) }}" />
                            </div>
                            <!-- Form Group (username)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="agency">Agency</label>
                                <input class="form-control" id="agency" name="agency" type="text" placeholder="Enter Agency Name" value="{{ old('agency', $fqdndata->agencies_name) }}" />
                            </div>
                            <!-- Form Group (username)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="address">Remarks</label>
                                <textarea class="lh-base form-control" id="remarks" name="remarks" type="text" placeholder="Enter remarks..." rows="4">
                                    {{ old('remarks', $fqdndata->remarks) }}
                                </textarea>
                            </div>
                            {{--<!-- Form Group (username)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="address">Address</label>
                                <textarea class="lh-base form-control" id="address" name="address" type="text" placeholder="Enter address..." rows="4"></textarea>
                            </div>--}}
                            <div class="form-group col-md-4">
                            <label for="enabled">Status</label>
                            <select id="enabled" name="enabled" class="form-control">
                                <option value="" selected>Choose...</option>
                                <option value="1" {{ $fqdndata->enabled == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $fqdndata->enabled == 0 ? 'selected' : '' }}>In-Active</option>
                            </select>
                            </div>
                            <div class="row">
                                <!-- Save and Back button-->
                                <div class="col-md-12" style="padding: 25px;text-align:right;">
                                    <a class="btn btn-secondary" type="button" href="{{ route('domain.index') }}">Back</a>
                                    <button class="btn btn-success" id="save">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
