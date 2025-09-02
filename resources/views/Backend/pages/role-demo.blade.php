@extends('backend.layouts.master')
@section('content')
    <div class="col-12 mt-5">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Role-Based Access Control Demo</h6>
                <p class="text-sm mb-0">This page demonstrates the role-based access control system</p>
            </div>
            <div class="card-body">
                <!-- User Info Section -->
                <div class="card border mb-4">
                    <div class="card-body">
                        <h6 class="mb-3">Current User Information</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Name:</strong> <span id="current-user-name">Loading...</span></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Email:</strong> <span id="current-user-email">Loading...</span></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Roles:</strong> <span id="current-user-roles">Loading...</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role-Based Content Examples -->
                <div class="row">
                    <!-- Super Admin Only Section -->
                    <div class="col-md-6 mb-4">
                        <div class="card border-primary" id="super-admin-section" style="display: none;">
                            <div class="card-header bg-primary">
                                <h6 class="text-white mb-0">Super Admin Features</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-sm mb-3">Only visible to super administrators.</p>
                                <button class="btn btn-primary btn-sm w-100 mb-2" onclick="showMessage('Super Admin action!', 'success')">
                                    <i class="fas fa-shield-alt me-2"></i>Manage System
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Company Only Section -->
                    <div class="col-md-6 mb-4">
                        <div class="card border-success" id="company-section" style="display: none;">
                            <div class="card-header bg-success">
                                <h6 class="text-white mb-0">Company Features</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-sm mb-3">Visible to company users and super admins.</p>
                                <button class="btn btn-success btn-sm w-100 mb-2" onclick="showMessage('Job created!', 'success')">
                                    <i class="fas fa-briefcase me-2"></i>Create Job
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role Testing Buttons -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Role Testing Functions</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-primary w-100" onclick="testSuperAdminAccess()">
                                    Test Super Admin
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-success w-100" onclick="testCompanyAccess()">
                                    Test Company
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-info w-100" onclick="testCandidateAccess()">
                                    Test Candidate
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-secondary w-100" onclick="refreshUserData()">
                                    Refresh Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            try {
                const profile = await initRoleBasedAuth();
                if (profile) {
                    displayUserInfo(profile);
                    setupRoleBasedContent();
                }
            } catch (error) {
                showMessage('Error: ' + error.message, 'error');
            }
        });

        function displayUserInfo(profile) {
            document.getElementById('current-user-name').textContent = profile.user.name;
            document.getElementById('current-user-email').textContent = profile.user.email;
            document.getElementById('current-user-roles').textContent = profile.roles.join(', ');
        }

        function setupRoleBasedContent() {
            if (isSuperAdmin()) {
                document.getElementById('super-admin-section').style.display = 'block';
                document.getElementById('company-section').style.display = 'block';
            } else if (isCompany()) {
                document.getElementById('company-section').style.display = 'block';
            }
        }

        function testSuperAdminAccess() {
            if (isSuperAdmin()) {
                showMessage('✅ Super Admin access confirmed!', 'success');
            } else {
                showMessage('❌ Access denied: Not super admin', 'error');
            }
        }

        function testCompanyAccess() {
            if (isCompany() || isSuperAdmin()) {
                showMessage('✅ Company access confirmed!', 'success');
            } else {
                showMessage('❌ Access denied: Not company user', 'error');
            }
        }

        function testCandidateAccess() {
            if (isCandidate() || isSuperAdmin()) {
                showMessage('✅ Candidate access confirmed!', 'success');
            } else {
                showMessage('❌ Access denied: Not candidate', 'error');
            }
        }

        async function refreshUserData() {
            try {
                const profile = await getCurrentUserProfile();
                if (profile) {
                    displayUserInfo(profile);
                    setupRoleBasedContent();
                    showMessage('✅ Data refreshed!', 'success');
                }
            } catch (error) {
                showMessage('❌ Refresh failed: ' + error.message, 'error');
            }
        }
    </script>
@endsection