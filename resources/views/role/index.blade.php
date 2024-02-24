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
                                <button class="btn btn-primary new" type="button" data-bs-toggle="modal" data-bs-target="#roleModal">Add Role</button>
                            </div>
                            </div>
                        </div>

                        
                        <div class="card-body mt-1 mb-4">
                        <div class="card card-collapsable mb-4">
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

                                        <a class="card-header mb-4" href="#{{ str_replace(' ', '', $role->name) }}" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="{{ $role->name }}">{{ $role->name }}
                                            <div class="card-collapsable-arrow">
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                        </a>
                                        <div class="collapse" id="{{ str_replace(' ', '', $role->name) }}">
                                                    <div class="card-body">
                                                    <form class="ajax-form-confirm" action="{{ route('setup.role.storePermission', ['id' => $role->id]) }}" method="POST">
                                                        <h6>List of Permission</h6>
                                                    <div class="row">
                                                            <div class="col-md-12 mb-4">
                                                            @foreach($permissions as $x => $perm)
                                                            <hr>
                                                            <h3><b>{{ ucfirst(str_replace("_"," ",strtoupper($x))) }}</b></h3>
                                                            @foreach($perm as $j => $k)
                                                            @if($j)
                                                                </br>
                                                            @endif
                                                            <p><b>{{ ucfirst(str_replace("_"," ",strtoupper($j))) }}</b></p>
                                                            <div class="row">
                                                            @foreach($k as $l => $m)
                                                                    @php
                                                                        $per_found = null;

                                                                        if( isset($role) ) {
                                                                            $per_found = $role->hasPermissionTo($m->id);
                                                                        }
                                                                    @endphp
                                                                    <div class="col-md-4">
                                                                        <span class="card lift h-20 w-1500 mt-2">
                                                                            <div class="card-body d-flex justify-content-center flex-column">
                                                                                <div class="d-flex align-items-center justify-content-between">
                                                                                    <div class="me-3">
                                                                                        
                                                                                        <div class="form-check">
                                                                                            {!! Form::checkbox("permissions[]", $m->id, $per_found, ['class' => 'form-check-input', 'id' => 'flexCheckDefault']) !!}
                                                                                            <label class="form-check-label" for="flexCheckDefault">{{ ucfirst(str_replace("_"," ",$m->name)) }}</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </span>
                                                                    </div>
                                                                    @endforeach
                                                                    </div>
                                                            @endforeach
                                                            @endforeach
                                                            </div>
                                                        </div>

                                                        @if($role->name != 'Developer' && $role->name != 'Superadmin')
                                                                        <div class="row">
                                                                            <div class="col s12 m12 l12" style="text-align:right;">
                                                                                <button type="button" class="btn btn-danger" id="deletePeranan" data-id="{{ $role->id }}" data-url="{{ route('setup.role.delete') }}" style="margin-bottom: 15px; margin-left: 15px; float: left">Delete</button>
                                                                                <button type="button" class="btn btn-primary rename"  data-bs-toggle="modal" data-bs-target="#roleModal" data-id="{{ $role->id }}" data-name="{{ $role->name }}" style="margin-bottom: 15px; margin-left: 15px; float: left">Change Name</button>
                                                                                <button class="btn btn-success" id="submit" type="submit">Submit</button>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                     </form>

                                                    </div>
                                                </div>

                                                </br>
                                                @endif  
                           
                                                @endforeach
                                                

                                               
                                            </div>
                        
                                        </div>

                    </div>
                </div>
            </div>
        </div>

                <div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="roleModal" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Role</h5>
                                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form class="ajax-form" novalidate action="{{ route('setup.role.create') }}" method="POST">
                            <div class="modal-body">
                            <div class="row">
                                {!! Form::hidden('roleId', null, ['id' => 'roleId']) !!}
                                <div class="col-md-6 @if ($errors->has('name')) has-error @endif">   
                                <label class="small mb-1" for="name">Name</label>
                                    {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control']) !!}
                                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
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