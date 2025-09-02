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
                <!-- User Selection -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="userSelect" class="form-control-label">Select User</label>
                            <select class="form-control" id="userSelect" required>
                                <option value="">-- Select a User --</option>
                            </select>
                            <small class="text-muted">Choose the user to assign roles to</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">Current User Roles</label>
                            <div id="currentRoles" class="p-3 bg-light rounded">
                                <small class="text-muted">Select a user to view their current roles</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Information Display -->
                <div id="userInfoSection" class="row mb-4" style="display: none;">
                    <div class="col-12">
                        <div class="card border">
                            <div class="card-body p-3">
                                <h6 class="mb-3">User Information</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Name:</strong> <span id="userDisplayName">-</span></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Email:</strong> <span id="userDisplayEmail">-</span></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Member Since:</strong> <span id="userDisplayDate">-</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Roles Assignment Form -->
                <div id="roleAssignmentSection" style="display: none;">
                    <form id="assignRolesForm">
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-3">Available Roles</h6>
                                <div id="rolesGrid" class="row">
                                    <!-- Roles will be loaded here -->
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
                                        <button type="button" class="btn btn-success me-2" id="assignRolesBtn">
                                            <i class="fas fa-plus me-2"></i>Assign Roles
                                        </button>
                                        <button type="button" class="btn btn-warning" id="updateRolesBtn">
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
        let allUsers = [];
        let allRoles = [];
        let selectedUser = null;

        // Load initial data
        document.addEventListener('DOMContentLoaded', function() {
            checkAuthentication();
            loadUsers();
            loadRoles();
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

        // Load all users
        async function loadUsers() {
            if (!checkAuthentication()) return;
            
            const token = localStorage.getItem('access_token');
            
            try {
                const response = await fetch('http://127.0.0.1:8000/api/users', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (!response.ok) {
                    throw new Error(`Failed to fetch users: ${response.status}`);
                }

                allUsers = await response.json();
                populateUserSelect();
            } catch (error) {
                console.error('Error loading users:', error);
                showMessage('Failed to load users. Please refresh the page.', 'error');
            }
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
                renderRolesGrid();
            } catch (error) {
                console.error('Error loading roles:', error);
                showMessage('Failed to load roles. Please refresh the page.', 'error');
            }
        }

        // Populate user select dropdown
        function populateUserSelect() {
            const userSelect = document.getElementById('userSelect');
            userSelect.innerHTML = '<option value="">-- Select a User --</option>';
            
            allUsers.forEach(user => {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = `${user.name} (${user.email})`;
                userSelect.appendChild(option);
            });
        }

        // Render roles grid
        function renderRolesGrid() {
            const grid = document.getElementById('rolesGrid');
            grid.innerHTML = '';
            
            if (allRoles.length === 0) {
                grid.innerHTML = '<div class="col-12"><p class="text-muted">No roles available.</p></div>';
                return;
            }
            
            allRoles.forEach(role => {
                const roleCard = `
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="card role-card">
                            <div class="card-body p-3">
                                <div class="form-check">
                                    <input class="form-check-input role-checkbox" type="checkbox" 
                                           id="role_${role.id}" value="${role.name}">
                                    <label class="form-check-label" for="role_${role.id}">
                                        <strong>${role.name}</strong>
                                        <br><small class="text-muted">Guard: ${role.guard_name || 'api'}</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                grid.innerHTML += roleCard;
            });
        }

        // Handle user selection
        document.getElementById('userSelect').addEventListener('change', async function() {
            const userId = this.value;
            
            if (!userId) {
                selectedUser = null;
                document.getElementById('currentRoles').innerHTML = 
                    '<small class="text-muted">Select a user to view their current roles</small>';
                document.getElementById('userInfoSection').style.display = 'none';
                document.getElementById('roleAssignmentSection').style.display = 'none';
                return;
            }
            
            selectedUser = allUsers.find(user => user.id == userId);
            displayUserInfo(selectedUser);
            await loadUserRoles(userId);
            document.getElementById('userInfoSection').style.display = 'block';
            document.getElementById('roleAssignmentSection').style.display = 'block';
        });

        // Display user information
        function displayUserInfo(user) {
            document.getElementById('userDisplayName').textContent = user.name;
            document.getElementById('userDisplayEmail').textContent = user.email;
            document.getElementById('userDisplayDate').textContent = new Date(user.created_at).toLocaleDateString();
        }

        // Load current roles for selected user
        async function loadUserRoles(userId) {
            if (!checkAuthentication()) return;
            
            const token = localStorage.getItem('access_token');
            
            try {
                const response = await fetch(`http://127.0.0.1:8000/api/users/${userId}/roles`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (!response.ok) {
                    throw new Error(`Failed to fetch user roles: ${response.status}`);
                }

                const result = await response.json();
                
                // Handle both old and new API response formats for backward compatibility
                let currentRoles;
                if (result.role_names) {
                    // New format with detailed response
                    currentRoles = result.role_names;
                } else {
                    // Old format (just array of role names)
                    currentRoles = result;
                }
                
                displayCurrentRoles(currentRoles);
                updateRoleCheckboxes(currentRoles);
                
            } catch (error) {
                console.error('Error loading user roles:', error);
                showMessage('Failed to load current roles for this user.', 'error');
            }
        }

        // Display current roles
        function displayCurrentRoles(roles) {
            const container = document.getElementById('currentRoles');
            
            if (roles.length === 0) {
                container.innerHTML = '<small class="text-muted">This user has no roles assigned.</small>';
                return;
            }
            
            const badges = roles.map(role => 
                `<span class="badge badge-sm bg-gradient-primary me-1 mb-1">${role}</span>`
            ).join('');
            
            container.innerHTML = badges;
        }

        // Update checkboxes based on current roles
        function updateRoleCheckboxes(currentRoles) {
            const checkboxes = document.querySelectorAll('.role-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = currentRoles.includes(checkbox.value);
            });
        }

        // Select/Deselect all functionality
        document.getElementById('selectAllRolesBtn').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.role-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = true);
        });

        document.getElementById('deselectAllRolesBtn').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.role-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = false);
        });

        // Role operation functions
        async function performRoleOperation(operation, selectedRoles, buttonElement, originalText) {
            if (!selectedUser) {
                showMessage('Please select a user first.', 'error');
                return;
            }
            
            if (selectedRoles.length === 0) {
                showMessage('Please select at least one role.', 'error');
                return;
            }
            
            // Disable button and show loading state
            buttonElement.disabled = true;
            buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            
            if (!checkAuthentication()) return;
            
            const token = localStorage.getItem('access_token');
            
            let method, url, successMessage;
            
            switch(operation) {
                case 'assign':
                    method = 'POST';
                    url = `http://127.0.0.1:8000/api/users/${selectedUser.id}/roles`;
                    successMessage = `Roles successfully assigned to user "${selectedUser.name}"!`;
                    break;
                case 'update':
                    method = 'PUT';
                    url = `http://127.0.0.1:8000/api/users/${selectedUser.id}/roles`;
                    successMessage = `User "${selectedUser.name}" roles updated successfully!`;
                    break;
            }
            
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        roles: selectedRoles
                    })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showMessage(successMessage, 'success');
                    // Reload the current roles display
                    await loadUserRoles(selectedUser.id);
                } else {
                    if (response.status === 401) {
                        showMessage('Session expired. Please log in again.', 'error');
                        localStorage.removeItem('access_token');
                        window.location.href = "{{ route('loginView') }}";
                    } else if (response.status === 403) {
                        showMessage('You do not have permission to manage user roles.', 'error');
                    } else {
                        showMessage(result.message || `Failed to ${operation} roles. Please try again.`, 'error');
                    }
                }
            } catch (error) {
                console.error(`Error ${operation}ing roles:`, error);
                showMessage('Network error occurred. Please check your connection and try again.', 'error');
            } finally {
                // Re-enable button
                buttonElement.disabled = false;
                buttonElement.innerHTML = originalText;
            }
        }

        // Assign roles button handler
        document.getElementById('assignRolesBtn').addEventListener('click', async function() {
            const checkboxes = document.querySelectorAll('.role-checkbox:checked');
            const selectedRoles = Array.from(checkboxes).map(cb => cb.value);
            await performRoleOperation('assign', selectedRoles, this, '<i class="fas fa-plus me-2"></i>Assign Roles');
        });

        // Update roles button handler
        document.getElementById('updateRolesBtn').addEventListener('click', async function() {
            const checkboxes = document.querySelectorAll('.role-checkbox:checked');
            const selectedRoles = Array.from(checkboxes).map(cb => cb.value);
            await performRoleOperation('update', selectedRoles, this, '<i class="fas fa-sync me-2"></i>Update Roles');
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