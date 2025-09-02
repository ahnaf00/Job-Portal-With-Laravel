@extends('backend.layouts.master')
@section('content')
    <div class="col-12 mt-5">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Create New Role</h6>
            </div>
            <div class="card-body px-4 pt-4 pb-2">
                <form id="createRoleForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="roleName" class="form-control-label">Role Name</label>
                                <input class="form-control" type="text" id="roleName" name="name" placeholder="Enter role name" required>
                                <small class="text-muted">Role name should be unique and descriptive (e.g., editor, moderator)</small>
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

        <!-- Success/Error Messages -->
        <div id="messageContainer" class="mt-3" style="display: none;">
            <div id="messageAlert" class="alert" role="alert">
                <span id="messageText"></span>
            </div>
        </div>
    </div>

    <script>
        // Function to show messages
        function showMessage(message, type = 'success') {
            const messageContainer = document.getElementById('messageContainer');
            const messageAlert = document.getElementById('messageAlert');
            const messageText = document.getElementById('messageText');

            messageText.textContent = message;
            messageAlert.className = `alert alert-${type === 'error' ? 'danger' : 'success'}`;
            messageContainer.style.display = 'block';

            // Auto-hide after 5 seconds
            setTimeout(() => {
                messageContainer.style.display = 'none';
            }, 5000);
        }

        // Form submission handler
        document.getElementById('createRoleForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const createBtn = document.getElementById('createBtn');
            const roleName = document.getElementById('roleName').value.trim();

            // Validate input
            if (!roleName) {
                showMessage('Please enter a role name.', 'error');
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
                const response = await fetch('http://127.0.0.1:8000/api/roles', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        name: roleName
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    showMessage(result.message || 'Role created successfully!', 'success');
                    // Clear the form
                    document.getElementById('roleName').value = '';
                    // Optionally redirect after a short delay
                    setTimeout(() => {
                        window.location.href = "{{ route('getAllRoles') }}";
                    }, 2000);
                } else {
                    // Handle specific error cases
                    if (response.status === 401) {
                        showMessage('Session expired. Please log in again.', 'error');
                        localStorage.removeItem('access_token');
                        window.location.href = "{{ route('loginView') }}";
                    } else if (response.status === 403) {
                        showMessage('You do not have permission to create roles.', 'error');
                    } else if (response.status === 422) {
                        // Validation errors
                        if (result.errors && result.errors.name) {
                            showMessage(result.errors.name[0], 'error');
                        } else {
                            showMessage('Validation error: ' + (result.message || 'Invalid input'), 'error');
                        }
                    } else {
                        showMessage(result.message || 'Failed to create role. Please try again.', 'error');
                    }
                }
            } catch (error) {
                console.error('Error creating role:', error);
                showMessage('Network error occurred. Please check your connection and try again.', 'error');
            } finally {
                // Re-enable button
                createBtn.disabled = false;
                createBtn.innerHTML = '<i class="fas fa-plus me-2"></i>Create Role';
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
