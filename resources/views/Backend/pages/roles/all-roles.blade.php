@extends('backend.layouts.master')
@section('content')
    <div class="col-12 mt-5">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>Roles</h6>
                <div>
                    <a href="{{ route('getAllPermissions') }}" class="btn btn-info btn-sm me-2">
                        <i class="fas fa-key me-2"></i>View Permissions
                    </a>
                    <a href="{{ route('assignPermissionsView') }}" class="btn btn-warning btn-sm me-2">
                        <i class="fas fa-user-shield me-2"></i>Assign Permissions
                    </a>
                    <a href="{{ route('createRoleView') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>Create New Role
                    </a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table" id="rolesTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Role Title</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="rolesBody">
                            <!-- Roles will be dynamically inserted here -->
                            @foreach ($roles as $role)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        {{-- <button class="btn btn-sm btn-primary" onclick="editRole(${role.id})">Edit</button> --}}
                                        <a href="{{ route('editRoleView',$role->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <button class="btn btn-sm btn-danger" onclick="deleteRole(${role.id})">Delete</button>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


