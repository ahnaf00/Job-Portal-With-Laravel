@extends('backend.layouts.master')
@section('content')
    <div class="col-12 mt-5">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>Permissions</h6>
                <div>
                    <a href="{{ route('getAllRoles') }}" class="btn btn-info btn-sm me-2">
                        <i class="fas fa-users-cog me-2"></i>View Roles
                    </a>
                    <a href="{{ route('assignPermissionsView') }}" class="btn btn-warning btn-sm me-2">
                        <i class="fas fa-user-shield me-2"></i>Assign to Roles
                    </a>
                    <a href="{{ route('createPermissionView') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>Create New Permission
                    </a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table" id="permissionsTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Permission Name</th>
                                <th scope="col">Guard Name</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="permissionsBody">
                            <!-- Permissions will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function fetchPermissions() {
            const token = localStorage.getItem('access_token');
            const tbody = document.getElementById('permissionsBody');

            if (!token) {
                showMessage('You are not authenticated. Please log in.');
                window.location.href = "{{ route('loginView') }}";
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">Please log in to view permissions.</td></tr>';
                return;
            }

            try {
                const response = await fetch('http://127.0.0.1:8000/api/permissions', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (!response.ok) {
                    if (response.status === 401) {
                        showMessage('Session expired. Please log in again.');
                        localStorage.removeItem('access_token');
                        window.location.href = "{{ route('loginView') }}";
                        return;
                    }
                    if (response.status === 404) {
                        showMessage('Permissions endpoint not found. Please check the API configuration.');
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center">Permissions endpoint not available.</td></tr>';
                        return;
                    }
                    if (response.status === 403) {
                        showMessage('You do not have permission to view permissions.');
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center">Access denied.</td></tr>';
                        return;
                    }
                    throw new Error(`Failed to fetch permissions: ${response.status} ${response.statusText}`);
                }

                const permissions = await response.json();
                tbody.innerHTML = '';

                if (!Array.isArray(permissions) || permissions.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center">No permissions found.</td></tr>';
                    return;
                }

                permissions.forEach((permission, index) => {
                    const createdAt = new Date(permission.created_at).toLocaleDateString();
                    const row = `
                        <tr>
                            <th scope="row">${index + 1}</th>
                            <td><span class="badge badge-sm bg-gradient-primary">${permission.name}</span></td>
                            <td><span class="text-secondary text-xs font-weight-bold">${permission.guard_name || 'api'}</span></td>
                            <td><span class="text-secondary text-xs font-weight-bold">${createdAt}</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="editPermission(${permission.id})" title="Edit Permission">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger ms-1" onclick="deletePermission(${permission.id})" title="Delete Permission">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            } catch (error) {
                console.error('Error fetching permissions:', error);
                showMessage('Failed to load permissions. Please try again.', 'error');
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">Error loading permissions.</td></tr>';
            }
        }

        // Edit permission function (placeholder)
        function editPermission(permissionId) {
            showMessage('Edit permission functionality will be implemented soon.', 'info');
            console.log('Edit permission:', permissionId);
        }

        // Delete permission function (placeholder)
        function deletePermission(permissionId) {
            if (confirm('Are you sure you want to delete this permission?')) {
                showMessage('Delete permission functionality will be implemented soon.', 'info');
                console.log('Delete permission:', permissionId);
            }
        }

        // Fetch permissions when the page loads
        document.addEventListener('DOMContentLoaded', fetchPermissions);
    </script>
@endsection