@extends('backend.layouts.master')
@section('content')
<div class="col-12 mt-5">
    <div class="card mb-4">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
            <h6>User Role Management</h6>
            <div>
                <a href="{{ route('getAllRoles') }}" class="btn btn-info btn-sm me-2">
                    <i class="fas fa-users-cog me-2"></i>View All Roles
                </a>
                <a href="{{ route('assignPermissionsView') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-user-shield me-2"></i>Assign Permissions
                </a>
            </div>
        </div>
        <div class="card-body px-4 pt-4 pb-2">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('assignRoles') }}">
                @csrf

                <!-- User Selection -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id" class="form-control-label">Select User</label>
                            <select class="form-control" id="user_id" name="user_id" required>
                                <option value="">-- Select a User --</option>
                                @foreach($users as $user)
                                    {{-- <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}> --}}
                                    <option value="{{ $user->id }}">

                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if(request('user_id'))
                        @php
                            $selectedUser = $users->firstWhere('id', request('user_id'));
                        @endphp
                        @if($selectedUser)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Current User Roles</label>
                                    <div class="p-3 bg-light rounded">
                                        @if($selectedUser->roles->count() > 0)
                                            @foreach($selectedUser->roles as $role)
                                                <span class="badge badge-sm bg-gradient-primary me-1 mb-1">{{ $role->name }}</span>
                                            @endforeach
                                        @else
                                            <small class="text-muted">This user has no roles assigned.</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- User Information Display -->
                @if(request('user_id'))
                    @php
                        $selectedUser = $users->firstWhere('id', request('user_id'));
                    @endphp
                    @if($selectedUser)
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-body p-3">
                                        <h6 class="mb-3">User Information</h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p class="mb-1"><strong>Name:</strong> {{ $selectedUser->name }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-1"><strong>Email:</strong> {{ $selectedUser->email }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-1"><strong>Member Since:</strong> {{ $selectedUser->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Roles Assignment Section -->
                @if(request('user_id'))
                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-3">Available Roles</h6>
                            <div class="row">
                                @forelse($roles as $role)
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="card role-card">
                                            <div class="card-body p-3">
                                                <div class="form-check">
                                                    <input class="form-check-input role-checkbox" type="checkbox"
                                                           name="roles[]" id="roles[]"
                                                           value="{{ $role->name }}"
                                                           {{-- {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}> --}}
                                                           {{ in_array($role->name,$userRole) ? 'checked':'' }}
                                                           >
                                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                                        <strong>{{ $role->name }}</strong>
                                                        <br><small class="text-muted">Guard: {{ $role->guard_name ?? 'web' }}</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-muted">No roles available.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="selectAllRolesBtn">
                                        <i class="fas fa-check-double me-2"></i>Select All
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="deselectAllRolesBtn">
                                        <i class="fas fa-times me-2"></i>Deselect All
                                    </button>
                                </div>
                                <div>
                                    <input type="hidden" name="operation" value="assign" id="operationInput">
                                    <button type="submit" class="btn btn-success me-2" name="operation" value="assign">
                                        <i class="fas fa-plus me-2"></i>Assign Roles
                                    </button>
                                    <button type="submit" class="btn btn-warning" name="operation" value="update">
                                        <i class="fas fa-sync me-2"></i>Update Roles
                                    </button>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <strong>Assign:</strong> Adds selected roles to user's existing roles ||
                                    <strong>Update:</strong> Replaces all user's roles with selected ones
                                </small>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">Select a user to view and assign roles.</p>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

{{-- <script>
    // Simple JavaScript for select/deselect functionality (minimal)
    document.addEventListener('DOMContentLoaded', function() {
        // Select/Deselect all functionality
        const selectAllBtn = document.getElementById('selectAllRolesBtn');
        const deselectAllBtn = document.getElementById('deselectAllRolesBtn');

        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function() {
                const checkboxes = document.querySelectorAll('.role-checkbox');
                checkboxes.forEach(checkbox => checkbox.checked = true);
            });
        }

        if (deselectAllBtn) {
            deselectAllBtn.addEventListener('click', function() {
                const checkboxes = document.querySelectorAll('.role-checkbox');
                checkboxes.forEach(checkbox => checkbox.checked = false);
            });
        }

        // Auto-submit when user is selected
        const userSelect = document.getElementById('user_id');
        if (userSelect) {
            userSelect.addEventListener('change', function() {
                if (this.value) {
                    // Add user_id to URL and reload
                    const url = new URL(window.location);
                    url.searchParams.set('user_id', this.value);
                    window.location.href = url;
                } else {
                    // Remove user_id from URL and reload
                    const url = new URL(window.location);
                    url.searchParams.delete('user_id');
                    window.location.href = url;
                }
            });
        }
    });
</script> --}}

<style>
    .role-card {
        border: 1px solid #e3e6f0;
        transition: all 0.3s ease;
    }

    .role-card:hover {
        border-color: #5e72e4;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .role-checkbox:checked + label {
        color: #5e72e4;
        font-weight: 600;
    }
</style>
@endsection
