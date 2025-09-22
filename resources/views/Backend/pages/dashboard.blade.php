@extends('backend.layouts.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Dashboard Overview</h6>
                    <p class="text-sm mb-0">Welcome to your dashboard</p>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="row p-3">
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Welcome</p>
                                                <h5 class="font-weight-bolder" id="welcome-user-name">Loading...</h5>
                                                <p class="mb-0">
                                                    <span class="text-success text-sm font-weight-bolder" id="user-role-display">Loading...</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                                <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Access Cards Based on Role -->
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4" id="super-admin-card" style="display: none;">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Admin Panel</p>
                                                <h5 class="font-weight-bolder">Full Access</h5>
                                                <p class="mb-0">
                                                    <a href="{{ route('getAllRoles') }}" class="text-primary text-sm font-weight-bolder">Manage Roles</a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                                <i class="ni ni-settings text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4" id="company-card" style="display: none;">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Job Management</p>
                                                <h5 class="font-weight-bolder">Create & Manage</h5>
                                                <p class="mb-0">
                                                    <a href="{{ route('createJobView') }}" class="text-success text-sm font-weight-bolder">Create Job</a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                                <i class="ni ni-briefcase-24 text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Test Demo</p>
                                                <h5 class="font-weight-bolder">Role Features</h5>
                                                <p class="mb-0">
                                                    <a href="{{ route('roleDemoView') }}" class="text-info text-sm font-weight-bolder">View Demo</a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                                <i class="ni ni-app text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            let retryCount = 0;
            const maxRetries = 3;
            
            async function initializeDashboard() {
                try {
                    // Check if token exists first
                    const token = localStorage.getItem('access_token');
                    if (!token) {
                        console.log('No token found, redirecting to login');
                        window.location.href = '/loginView';
                        return;
                    }
                    
                    // Try to get user profile
                    const profile = await getCurrentUserProfile();
                    if (profile) {
                        setCurrentUser(profile);
                        setupDashboard(profile);
                        console.log('Dashboard initialized successfully for:', profile.user.name);
                    } else {
                        throw new Error('Failed to get user profile');
                    }
                } catch (error) {
                    console.error('Dashboard initialization error (attempt ' + (retryCount + 1) + '):', error);
                    retryCount++;
                    
                    if (retryCount < maxRetries) {
                        console.log('Retrying dashboard initialization in 1 second...');
                        setTimeout(initializeDashboard, 1000);
                    } else {
                        console.error('Max retries reached. Token may be invalid.');
                        localStorage.removeItem('access_token');
                        alert('Session expired. Please login again.');
                        window.location.href = '/loginView';
                    }
                }
            }
            
            // Start initialization
            initializeDashboard();
        });

        function setupDashboard(profile) {
            // Update welcome section
            document.getElementById('welcome-user-name').textContent = profile.user.name;
            document.getElementById('user-role-display').textContent = profile.roles.join(', ');
            
            // Show role-specific cards
            if (isSuperAdmin()) {
                document.getElementById('super-admin-card').style.display = 'block';
                document.getElementById('company-card').style.display = 'block'; // Super admin can see company features too
            } else if (isCompany()) {
                document.getElementById('company-card').style.display = 'block';
            }
        }
    </script>
@endsection