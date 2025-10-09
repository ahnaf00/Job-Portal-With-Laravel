@extends('backend.layouts.master')
@section('content')
    <div class="col-12 mt-5">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>Assign Permissions to Role</h6>
                <div>
                    <a href="{{ route('getAllRoles') }}" class="btn btn-info btn-sm me-2">
                        <i class="fas fa-users-cog me-2"></i>View All Roles
                    </a>
                    <a href="{{ route('getAllPermissions') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-key me-2"></i>View All Permissions
                    </a>
                </div>
            </div>
            <div class="card-body px-4 pt-4 pb-2">
                <!-- Role Selection -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="roleSelect" class="form-control-label">Select Role</label>
                            <select class="form-control" id="roleSelect" required>
                                <option value="">-- Select a Role --</option>
                            </select>
                            <small class="text-muted">Choose the role to assign permissions to</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">Current Role Permissions</label>
                            <div id="currentPermissions" class="p-3 bg-light rounded">
                                <small class="text-muted">Select a role to view its current permissions</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions Assignment Form -->
                <div id="permissionAssignmentSection" style="display: none;">
                    <form id="assignPermissionsForm">
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-3">Available Permissions</h6>
                                <div id="permissionsGrid" class="row">
                                    <!-- Permissions will be loaded here -->
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="selectAllBtn">
                                            <i class="fas fa-check-double me-2"></i>Select All
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="deselectAllBtn">
                                            <i class="fas fa-times me-2"></i>Deselect All
                                        </button>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-success" id="assignBtn">
                                            <i class="fas fa-save me-2"></i>Assign Permissions
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <div id="messageContainer" class="mt-3" style="display: none;">
            <div id="messageAlert" class="alert" role="alert">
                <span id="messageText"></span>
            </div>
        </div>
    </div>

    <style>
        .permission-card {
            border: 1px solid #e3e6f0;
            transition: all 0.3s ease;
        }

        .permission-card:hover {
            border-color: #5e72e4;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .permission-checkbox:checked + label {
            color: #5e72e4;
            font-weight: 600;
        }
    </style>
@endsection
