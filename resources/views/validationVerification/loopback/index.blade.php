@extends('layouts.app')
@push('css')



@endpush
@section('content')
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-fluid px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"></div>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                                        <li class="breadcrumb-item">Validation & Verification</li>
                                    </ol>
                                </nav>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="container-xl px-4 mt-4" id="searchingBox">
            <div class="row">
                <div class="col-xl-12">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">Loopback SMTP Test</div>

                        
                        <div class="card-body mt-1 mb-4">
                        <form id="report-form">
                        <div class="row col-12 mb-1">
                                    <div class="col-md-4">
                                        <label class="small mb-1" for="serverName">Server Name:</label>
                                        {!! Form::select('serverName', $serverName, null, ['id'=>'serverName', 'name'=>'serverName', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                    </div>
                        </div>
                        <div class="row col-12 mb-1">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="mailFrom">Mail From:</label>
                                        {!! Form::text('mailFrom',  null, ['id'=>'mail_from', 'name'=>'mail_from', 'class'=>'form-control']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="recepientTo">Recepient To:</label>
                                        {!! Form::text('recepientTo', null, ['id'=>'rec_to', 'name'=>'rec_to', 'class'=>'form-control']) !!}
                                    </div>
                        </div>
                        <div class="col-md-12">
                            <!-- Save and Back button-->
                            <div style="padding: 25px; text-align: right;">
                                <button class="btn btn-primary" id="back">Back</button>
                                <button class="btn btn-success" id="test">Test</button>
                            </div>
                        </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

 @endsection
 @push('script')
 @endpush