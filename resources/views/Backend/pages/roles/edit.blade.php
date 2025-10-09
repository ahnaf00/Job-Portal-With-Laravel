@extends('backend.layouts.master')

@section('content')
<div class="col-12 mt-5">
    <div class="card mb-4">
        <div class="card-header pb-0">
            <h6>Edit Role</h6>
        </div>
        <div class="card-body px-3 pt-3 pb-2">
            <form action="{{ route('updateRole', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="name">Role Name</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $role->name) }}"
                           class="form-control @error('name') is-invalid @enderror"
                           required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update Role</button>
                <a href="{{ route('getAllRoles') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
