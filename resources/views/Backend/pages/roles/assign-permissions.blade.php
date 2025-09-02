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

    <script>
        let allRoles = [];
        let allPermissions = [];
        let selectedRole = null;

        // Load initial data
        document.addEventListener('DOMContentLoaded', function() {
            checkAuthentication();
            loadRoles();
            loadPermissions();
        });

        // Check authentication
        function checkAuthentication() {
            const token = localStorage.getItem('access_token');
            if (!token) {
                showMessage('You are not authenticated. Redirecting to login...', 'error');
                setTimeout(() => {
                    window.location.href = "{{ route('loginView') }}";
                }, 2000);
                return false;
            }
            return true;
        }

        // Load all roles
        async function loadRoles() {
            if (!checkAuthentication()) return;
            
            const token = localStorage.getItem('access_token');
            
            try {
                const response = await fetch('http://127.0.0.1:8000/api/roles', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (!response.ok) {
                    throw new Error(`Failed to fetch roles: ${response.status}`);
                }

                allRoles = await response.json();
                populateRoleSelect();
            } catch (error) {
                console.error('Error loading roles:', error);
                showMessage('Failed to load roles. Please refresh the page.', 'error');
            }
        }

        // Load all permissions
        async function loadPermissions() {
            if (!checkAuthentication()) return;
            
            const token = localStorage.getItem('access_token');
            
            try {
                const response = await fetch('http://127.0.0.1:8000/api/permissions', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (!response.ok) {
                    throw new Error(`Failed to fetch permissions: ${response.status}`);
                }

                allPermissions = await response.json();
                renderPermissionsGrid();
            } catch (error) {
                console.error('Error loading permissions:', error);
                showMessage('Failed to load permissions. Please refresh the page.', 'error');
            }
        }

        // Populate role select dropdown
        function populateRoleSelect() {
            const roleSelect = document.getElementById('roleSelect');
            roleSelect.innerHTML = '<option value="">-- Select a Role --</option>';
            
            allRoles.forEach(role => {
                const option = document.createElement('option');
                option.value = role.id;
                option.textContent = role.name;
                roleSelect.appendChild(option);
            });
        }

        // Render permissions grid
        function renderPermissionsGrid() {
            const grid = document.getElementById('permissionsGrid');
            grid.innerHTML = '';
            
            if (allPermissions.length === 0) {
                grid.innerHTML = '<div class="col-12"><p class="text-muted">No permissions available.</p></div>';
                return;
            }
            
            allPermissions.forEach(permission => {
                const permissionCard = `
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="card permission-card">
                            <div class="card-body p-3">
                                <div class="form-check">
                                    <input class="form-check-input permission-checkbox" type="checkbox" 
                                           id="permission_${permission.id}" value="${permission.name}">
                                    <label class="form-check-label" for="permission_${permission.id}">
                                        <strong>${permission.name}</strong>
                                        <br><small class="text-muted">Guard: ${permission.guard_name || 'api'}</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                grid.innerHTML += permissionCard;
            });
        }

        // Handle role selection
        document.getElementById('roleSelect').addEventListener('change', async function() {
            const roleId = this.value;
            
            if (!roleId) {
                selectedRole = null;
                document.getElementById('currentPermissions').innerHTML = 
                    '<small class="text-muted">Select a role to view its current permissions</small>';
                document.getElementById('permissionAssignmentSection').style.display = 'none';
                return;
            }
            
            selectedRole = allRoles.find(role => role.id == roleId);
            await loadRolePermissions(roleId);
            document.getElementById('permissionAssignmentSection').style.display = 'block';
        });

        // Load current permissions for selected role
        async function loadRolePermissions(roleId) {
            if (!checkAuthentication()) return;
            
            const token = localStorage.getItem('access_token');
            
            try {
                const response = await fetch(`http://127.0.0.1:8000/api/roles/${roleId}/permissions`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (!response.ok) {
                    throw new Error(`Failed to fetch role permissions: ${response.status}`);
                }

                const result = await response.json();
                const currentPermissions = result.allpermissions || [];
                
                displayCurrentPermissions(currentPermissions);
                updatePermissionCheckboxes(currentPermissions);
                
            } catch (error) {
                console.error('Error loading role permissions:', error);
                showMessage('Failed to load current permissions for this role.', 'error');
            }
        }

        // Display current permissions
        function displayCurrentPermissions(permissions) {
            const container = document.getElementById('currentPermissions');
            
            if (permissions.length === 0) {
                container.innerHTML = '<small class="text-muted">This role has no permissions assigned.</small>';
                return;
            }
            
            const badges = permissions.map(permission => 
                `<span class="badge badge-sm bg-gradient-info me-1 mb-1">${permission}</span>`
            ).join('');
            
            container.innerHTML = badges;
        }

        // Update checkboxes based on current permissions
        function updatePermissionCheckboxes(currentPermissions) {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = currentPermissions.includes(checkbox.value);
            });
        }

        // Select/Deselect all functionality
        document.getElementById('selectAllBtn').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = true);
        });

        document.getElementById('deselectAllBtn').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = false);
        });

        // Form submission handler
        document.getElementById('assignPermissionsForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            if (!selectedRole) {
                showMessage('Please select a role first.', 'error');
                return;
            }
            
            const assignBtn = document.getElementById('assignBtn');
            const checkboxes = document.querySelectorAll('.permission-checkbox:checked');
            const selectedPermissions = Array.from(checkboxes).map(cb => cb.value);
            
            // Disable button and show loading state
            assignBtn.disabled = true;
            assignBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Assigning...';
            
            if (!checkAuthentication()) return;
            
            const token = localStorage.getItem('access_token');
            
            try {
                const response = await fetch(`http://127.0.0.1:8000/api/roles/${selectedRole.id}/permissions`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        permissions: selectedPermissions
                    })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showMessage(`Permissions successfully assigned to role "${selectedRole.name}"!`, 'success');
                    // Reload the current permissions display
                    await loadRolePermissions(selectedRole.id);
                } else {
                    if (response.status === 401) {
                        showMessage('Session expired. Please log in again.', 'error');
                        localStorage.removeItem('access_token');
                        window.location.href = "{{ route('loginView') }}";
                    } else if (response.status === 403) {
                        showMessage('You do not have permission to assign permissions to roles.', 'error');
                    } else {
                        showMessage(result.message || 'Failed to assign permissions. Please try again.', 'error');
                    }
                }
            } catch (error) {
                console.error('Error assigning permissions:', error);
                showMessage('Network error occurred. Please check your connection and try again.', 'error');
            } finally {
                // Re-enable button
                assignBtn.disabled = false;
                assignBtn.innerHTML = '<i class="fas fa-save me-2"></i>Assign Permissions';
            }
        });

        // Show message function (using global function from script.blade.php)
        function showMessage(message, type = 'success') {
            // Fallback if global function is not available
            if (typeof window.showMessage === 'function') {
                window.showMessage(message, type);
            } else {
                const messageContainer = document.getElementById('messageContainer');
                const messageAlert = document.getElementById('messageAlert');
                const messageText = document.getElementById('messageText');
                
                messageText.textContent = message;
                messageAlert.className = `alert alert-${type === 'error' ? 'danger' : 'success'}`;
                messageContainer.style.display = 'block';
                
                setTimeout(() => {
                    messageContainer.style.display = 'none';
                }, 5000);
            }
        }
    </script>

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