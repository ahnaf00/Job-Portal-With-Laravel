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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    async function fetchRoles() {
        const token = localStorage.getItem('access_token');
        const tbody = document.getElementById('rolesBody');

        if (!token) {
            showMessage('You are not authenticated. Please log in.');
            window.location.href = "{{ route('loginView') }}"; // Redirect to login page
            tbody.innerHTML = '<tr><td colspan="3" class="text-center">Please log in to view roles.</td></tr>';
            return;
        }

        try {
            const response = await fetch('http://127.0.0.1:8000/api/roles', {
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
                    showMessage('Roles endpoint not found. Please check the API configuration.');
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center">Roles endpoint not available.</td></tr>';
                    return;
                }
                throw new Error(`Failed to fetch roles: ${response.status} ${response.statusText}`);
            }

            const roles = await response.json();
            tbody.innerHTML = '';

            if (!Array.isArray(roles) || roles.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">No roles found.</td></tr>';
                return;
            }

            roles.forEach((role, index) => {
                const row = `
                    <tr>
                        <th scope="row">${index + 1}</th>
                        <td>${role.name}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="editRole(${role.id})">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteRole(${role.id})">Delete</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } catch (error) {
            console.error('Error fetching roles:', error);
            showMessage('Failed to load roles. Please try again.');
            tbody.innerHTML = '<tr><td colspan="3" class="text-center">Error loading roles.</td></tr>';
        }
    }

    // Fetch roles when the page loads
    document.addEventListener('DOMContentLoaded', fetchRoles);
    </script>
@endsection


