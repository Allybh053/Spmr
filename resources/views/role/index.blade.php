@extends('layouts.app')
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
                                        <li class="breadcrumb-item">List of Role</li>
                                    </ol>
                                </nav>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-xl px-4 mt-4">
            <div class="row">
                <div class="col-xl-12">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">
                        <div class="row">
                            <div class="col-md-12" style="text-align:right;">
                                <h6 style="float: left">Role And Permission</h6>
                                <a href="#roleModal"class="btn btn-sm btn-primary">Add Role</a>
                            </div>
                            </div>
                        </div>

                        
                        <div class="card-body mt-1 mb-4">
                        <div class="card card-collapsable">
                        @foreach ($roles as $role)
                                                @if($role->name != 'Developer')
                                                        @php
                                                            $options = [];
                                                            $fontColor = 'black';
                                                            $submitButton = 'yes';
                                                        @endphp

                                                        @if($role->name == 'Superadmin')
                                                            @php
                                                                $options = ['disabled'];
                                                                $fontColor = 'purple';
                                                                $submitButton = '';
                                                            @endphp
                                                        @endif

                                        <a class="card-header" href="#{{ $role->name }}" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="{{ $role->name }}">{{ $role->name }}
                                            <div class="card-collapsable-arrow">
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                        </a>
                                        <div class="collapse" id="{{ $role->name }}">
                                                    <div class="card-body">
                                                    <form class="ajax-form-confirm" action="{{ route('setup.role.storePermission', ['id' => $role->id]) }}" method="POST">
                                                        <h6>List of Permission</h6>
                                                    <div class="row">
                                                            <div class="col-xl-4 mb-4">
                                                            @foreach($permissions as $x => $perm)
                                                            @foreach($perm as $j => $k)
                                                            <p>{{ ucfirst(str_replace("_"," ",strtoupper($j))) }}</p>
                                                            @foreach($k as $l => $m)
                                                                    @php
                                                                        $per_found = null;

                                                                        if( isset($role) ) {
                                                                            $per_found = $role->hasPermissionTo($m->name);
                                                                        }
                                                                    @endphp
                                                                    <div class="row" style="margin-bottom: -40px">
                                                                    <div class="col-xl-4 mb-4">
                                                                            <span class="card lift h-20 w-1500 mt-2">
                                                                                <div class="card-body d-flex justify-content-center flex-column">
                                                                                    <div class="d-flex align-items-center justify-content-between">
                                                                                        <div class="me-3">
                                                                                        <div class="form-check">
                                                                            {!! Form::checkbox("permissions[]", $m->name, $per_found, ['class' => 'form-check-input', 'id' => 'flexCheckDefault']) !!}
                                                                                    <label class="form-check-label" for="flexCheckDefault">{{ ucfirst(str_replace("_"," ",$m->name)) }}</label>
                                                                                </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                    </span>
                                                                        </div>
                                                                     </div>
                                                                    @endforeach
                                                            @endforeach
                                                            @endforeach
                                                            </div>
                                                        </div>
                                                        @if($role->name != 'Developer' || $role->name != 'Superadmin')
                                                                        <div class="row">
                                                                            <div class="col s12 m12 l12" style="text-align:right;">
                                                                                <button type="button" class="btn btn-danger" id="deletePeranan" data-id="{{ $role->id }}" data-url="{{ route('setup.role.delete') }}" style="margin-bottom: 15px; margin-left: 15px; float: left">Delete</button>
                                                                                <a href="#roleModal" class="rename modal-trigger btn btn-primary" data-id="{{ $role->id }}" data-name="{{ $role->name }}" style="margin-bottom: 15px; margin-left: 15px; float: left">Change Name</a>
                                                                                <button class="btn btn-success" id="submit" type="submit">Submit</button>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                     </form>

                                                    </div>
                                                </div>

                                                @endif  
                            @endforeach
                                                

                                               
                                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    <div id="roleModal" class="modal modal-fixed-footer">
        <form class="ajax-form" novalidate action="{{ route('setup.role.create') }}" method="POST">
            @csrf
            <div class="modal-content">
                <h4>Role</h4>
                <div class="row">
                    {!! Form::hidden('roleId', null, ['id' => 'roleId']) !!}
                    <div class="input-field col m12 s6 @if ($errors->has('name')) has-error @endif">
                        <i class="material-icons prefix">class</i>
                        {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control']) !!}
                        <label for="name">Name</label>
                        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat ">Close</a>
                <button type="submit" class="modal-action modal-close waves-effect waves-green btn-flat ">Save</button>
            </div>
        </form>
    </div>
@endsection
@push('script')
    <script>
        (function ($) {
            $(document).on("click", ".new", function () {
                var roleId = '';
                $(".modal-content #roleId").val(roleId);

                var roleName = '';
                $(".modal-content #name").val(roleName);
            });

            $(document).on("click", ".rename", function () {
                var roleId = $(this).data('id');
                $(".modal-content #roleId").val(roleId);

                var roleName = $(this).data('name');
                $(".modal-content #name").val(roleName);
            });

        })(jQuery);

        document.addEventListener("DOMContentLoaded", function () {
        const collapser = document.querySelectorAll('.card-collapsable .card-header');
        collapser.forEach(function (el) {
            el.addEventListener('click', function () {
                let currentCollapse = this.getAttribute('href');
                collapser.forEach(function (el) {
                    if (el.getAttribute('href') !== currentCollapse) {
                        let otherCollapse = el.getAttribute('href');
                        $(otherCollapse).collapse('hide');
                    }
                });
            });
        });
    });
    </script>
@endpush