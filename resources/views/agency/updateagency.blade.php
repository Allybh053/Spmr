@extends('layouts.app')
@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-fluid px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="cloud"></i></div>
                                Agency
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="container-fluid px-4">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0 text-primary">Update Agency</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('agency.saveupdateagency', ['id' => $agency->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name">Ministry Code</label>
                            <select class="form-control select2" name="ministry" id="ministry" required>
                                <option value="{{ $ministry1->id }}">{{ $ministry1->name }}</option>
                                @foreach ($ministry as $ministry)
                                <option value="{{ $ministry['id'] }}">{{ $ministry['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name">Agency Code Name</label>
                            <input class="form-control" id="code_name" name="code_name" type="text" placeholder="" value="{{ $agency->code_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="name">Agency Name</label>
                            <input class="form-control" id="name" name="name" type="text" placeholder="" value="{{ $agency->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="remark">Description</label>
                            <textarea class="form-control" id="remark" name="remark" rows="4">{{ $agency->remark }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status">Status</label>
                            <select class="form-control select2" name="status" id="status" required>
                                <option value="1" {{ $agency->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $agency->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3 text-center">
                            <a href="{{ url()->previous() }}" class="btn btn-danger">
                                <i class="me-1" data-feather="arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">Update Agency</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>
@endsection
