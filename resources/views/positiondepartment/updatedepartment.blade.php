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
                                Department
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="container-xl px-4">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0 text-primary">Update Department</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('positiondepartment.saveupdatedepartment', ['id' => $department->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name">Department Name</label>
                            <input class="form-control" id="name" name="name" type="text" placeholder="" value="{{ $department->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="remark">Description</label>
                            <textarea class="form-control" id="remark" name="remark" rows="4">{{ $department->remark }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status">Status</label>
                            <select class="form-control select2" name="status" id="status" required>
                                <option value="1" {{ $department->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $department->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3 text-center">
                            <a href="{{ url()->previous() }}" class="btn btn-danger">
                                <i class="me-1" data-feather="arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">Update Department</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>
@endsection
