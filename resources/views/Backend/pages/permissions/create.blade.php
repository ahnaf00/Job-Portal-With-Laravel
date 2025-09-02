@extends('backend.layouts.master')
@section('content')
    <div class="col-12 mt-5">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Create New Permission</h6>
            </div>
            <div class="card-body px-4 pt-4 pb-2">
                <form id="createPermissionForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="permissionName" class="form-control-label">Permission Name</label>
                                <input class="form-control" type="text" id="permissionName" name="name" placeholder="Enter permission name" required>
                                <small class="text-muted">Permission name should be descriptive and use kebab-case (e.g., user-create, job-edit, application-review)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Common Permission Examples</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillPermission('user-list')">
                                        user-list
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillPermission('user-create')">
                                        user-create
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillPermission('user-edit')">
                                        user-edit
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="fillPermission('user-delete')">
                                        user-delete
                                    </button>
                                </div>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="fillPermission('job-list')">
                                        job-list
                                    </button>
                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="fillPermission('job-create')">
                                        job-create
                                    </button>
                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="fillPermission('job-edit')">
                                        job-edit
                                    </button>
                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="fillPermission('job-delete')">
                                        job-delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" id="createBtn">
                                <i class="fas fa-plus me-2"></i>Create Permission
                            </button>
                            <a href="{{ route('getAllPermissions') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-arrow-left me-2"></i>Back to Permissions
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <div id="messageContainer" class="mt-3" style="display: none;">
            <div id="messageAlert" class="alert" role="alert">
                <span id="messageText"></span>
            </div>
        </div>
    </div>

    <script>
        // Function to fill permission name from examples
        function fillPermission(permissionName) {
            document.getElementById('permissionName').value = permissionName;
        }

        // Form submission handler
        document.getElementById('createPermissionForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const createBtn = document.getElementById('createBtn');
            const permissionName = document.getElementById('permissionName').value.trim();
            
            // Validate input
            if (!permissionName) {
                showMessage('Please enter a permission name.', 'error');
                return;
            }
            
            // Validate naming convention
            if (!/^[a-z][a-z0-9-]*[a-z0-9]$/.test(permissionName)) {
                showMessage('Permission name should use lowercase letters, numbers, and hyphens only (e.g., user-create, job-edit).', 'error');
                return;
            }
            
            // Get token from localStorage
            const token = localStorage.getItem('access_token');
            if (!token) {
                showMessage('You are not authenticated. Please log in.', 'error');
                window.location.href = "{{ route('loginView') }}";
                return;
            }
            
            // Disable button and show loading state
            createBtn.disabled = true;
            createBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
            
            try {
                const response = await fetch('http://127.0.0.1:8000/api/permissions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        name: permissionName
                    })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showMessage(result.message || 'Permission created successfully!', 'success');
                    // Clear the form
                    document.getElementById('permissionName').value = '';
                    // Optionally redirect after a short delay
                    setTimeout(() => {
                        window.location.href = "{{ route('getAllPermissions') }}";
                    }, 2000);
                } else {
                    // Handle specific error cases
                    if (response.status === 401) {
                        showMessage('Session expired. Please log in again.', 'error');
                        localStorage.removeItem('access_token');
                        window.location.href = "{{ route('loginView') }}";
                    } else if (response.status === 403) {
                        showMessage('You do not have permission to create permissions.', 'error');
                    } else if (response.status === 422) {
                        // Validation errors
                        if (result.errors && result.errors.name) {
                            showMessage(result.errors.name[0], 'error');
                        } else {
                            showMessage('Validation error: ' + (result.message || 'Invalid input'), 'error');
                        }
                    } else {
                        showMessage(result.message || 'Failed to create permission. Please try again.', 'error');
                    }
                }
            } catch (error) {
                console.error('Error creating permission:', error);
                showMessage('Network error occurred. Please check your connection and try again.', 'error');
            } finally {
                // Re-enable button
                createBtn.disabled = false;
                createBtn.innerHTML = '<i class="fas fa-plus me-2"></i>Create Permission';
            }
        });

        // Check authentication on page load
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('access_token');
            if (!token) {
                showMessage('You are not authenticated. Redirecting to login...', 'error');
                setTimeout(() => {
                    window.location.href = "{{ route('loginView') }}";
                }, 2000);
            }
        });
    </script>
@endsection