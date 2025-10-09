@extends('backend.layouts.master')
@section('content')
    <div class="col-12 mt-5">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Create New Role</h6>
            </div>
            <div class="card-body px-4 pt-4 pb-2">
                {{-- Show Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('createRole') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="roleName" class="form-control-label">Role Name</label>
                                <input class="form-control" type="text" id="roleName" name="name"
                                    placeholder="Enter role name" required>
                                <small class="text-muted">
                                    Role name should be unique and descriptive (e.g., editor, moderator)
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" id="createBtn">
                                <i class="fas fa-plus me-2"></i>Create Role
                            </button>
                            <a href="{{ route('getAllRoles') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-arrow-left me-2"></i>Back to Roles
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
