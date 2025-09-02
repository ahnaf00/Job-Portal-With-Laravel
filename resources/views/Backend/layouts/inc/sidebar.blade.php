<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0"
            href=" https://demos.creative-tim.com/argon-dashboard-pro/pages/dashboards/default.html " target="_blank">
            <img src="../../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100"
                alt="main_logo">
            <span class="ms-1 font-weight-bold">Creative Tim</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
        <!-- Loading State -->
        <div id="sidebar-loading" class="text-center py-3">
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-sm mt-2">Loading navigation...</p>
        </div>

        <ul class="navbar-nav" id="sidebar-nav" style="display: none;">
            <!-- Roles Section - Only for Super Admin -->
            <li class="nav-item" id="roles-section">
                <a data-bs-toggle="collapse" href="#dashboardsExamples" class="nav-link "
                    aria-controls="dashboardsExamples" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-shop text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Roles</span>
                </a>
                <div class="collapse " id="dashboardsExamples">
                    <ul class="nav ms-4">
                        <li class="nav-item ">
                            <a class="nav-link " href="{{ route('getAllRoles') }}">
                                <span class="sidenav-mini-icon"> L </span>
                                <span class="sidenav-normal"> All Roles </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="{{ route('createRoleView') }}">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal"> Create Role </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="{{ route('assignPermissionsView') }}">
                                <span class="sidenav-mini-icon"> A </span>
                                <span class="sidenav-normal"> Assign Permissions </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Permissions Section - Only for Super Admin -->
            <li class="nav-item" id="permissions-section">
                <a data-bs-toggle="collapse" href="#permissionsExamples" class="nav-link"
                    aria-controls="permissionsExamples" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-key-25 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Permissions</span>
                </a>
                <div class="collapse" id="permissionsExamples">
                    <ul class="nav ms-4">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('getAllPermissions') }}">
                                <span class="sidenav-mini-icon"> L </span>
                                <span class="sidenav-normal"> All Permissions </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('createPermissionView') }}">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal"> Create Permission </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- RBAC Management Section - Only for Super Admin -->
            <li class="nav-item" id="rbac-section">
                <a data-bs-toggle="collapse" href="#rbacManagement" class="nav-link"
                    aria-controls="rbacManagement" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-circle-08 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">RBAC Management</span>
                </a>
                <div class="collapse" id="rbacManagement">
                    <ul class="nav ms-4">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('assignPermissionsView') }}">
                                <span class="sidenav-mini-icon"> A </span>
                                <span class="sidenav-normal"> Assign Permissions </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('assignRolesView') }}">
                                <span class="sidenav-mini-icon"> U </span>
                                <span class="sidenav-normal"> Assign Roles to Users </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Company Management Section - Only for Super Admin -->
            <li class="nav-item" id="company-management-section">
                <a data-bs-toggle="collapse" href="#companyManagement" class="nav-link"
                    aria-controls="companyManagement" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-building text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Company Management</span>
                </a>
                <div class="collapse" id="companyManagement">
                    <ul class="nav ms-4">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('getAllCompanies') }}">
                                <span class="sidenav-mini-icon"> L </span>
                                <span class="sidenav-normal"> All Companies </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('getPendingCompanies') }}">
                                <span class="sidenav-mini-icon"> P </span>
                                <span class="sidenav-normal"> Pending Verification </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('getVerifiedCompanies') }}">
                                <span class="sidenav-mini-icon"> V </span>
                                <span class="sidenav-normal"> Verified Companies </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Job Management Section - For Company and Super Admin -->
            <li class="nav-item" id="job-management-section">
                <a data-bs-toggle="collapse" href="#jobManagement" class="nav-link"
                    aria-controls="jobManagement" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-briefcase-24 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Job Management</span>
                </a>
                <div class="collapse" id="jobManagement">
                    <ul class="nav ms-4">
                        <li class="nav-item" id="all-jobs-item">
                            <a class="nav-link" href="{{ route('getAllJobs') }}">
                                <span class="sidenav-mini-icon"> L </span>
                                <span class="sidenav-normal"> All Jobs </span>
                            </a>
                        </li>
                        <li class="nav-item" id="create-job-item">
                            <a class="nav-link" href="{{ route('createJobView') }}">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal"> Create Job </span>
                            </a>
                        </li>
                        <li class="nav-item" id="my-jobs-item">
                            <a class="nav-link" href="{{ route('getMyJobs') }}">
                                <span class="sidenav-mini-icon"> M </span>
                                <span class="sidenav-normal"> My Jobs </span>
                            </a>
                        </li>
                        <li class="nav-item" id="draft-jobs-item">
                            <a class="nav-link" href="{{ route('getDraftJobs') }}">
                                <span class="sidenav-mini-icon"> D </span>
                                <span class="sidenav-normal"> Draft Jobs </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>

    <!-- Role-based Navigation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initRoleBasedNavigation();
        });

        async function initRoleBasedNavigation() {
            try {
                // Use the role-based-auth.js utility function
                const userProfile = await initRoleBasedAuth();
                if (userProfile) {
                    setupNavigationBasedOnRole(userProfile.roles);
                } else {
                    // Redirect to login if no user profile
                    window.location.href = "{{ route('loginView') }}";
                }
            } catch (error) {
                console.error('Failed to load user profile:', error);
                // Hide navigation and show error
                document.getElementById('sidebar-loading').innerHTML = 
                    '<p class="text-danger text-sm">Failed to load navigation</p>';
            }
        }

        function setupNavigationBasedOnRole(userRoles) {
            // Hide loading and show navigation
            document.getElementById('sidebar-loading').style.display = 'none';
            document.getElementById('sidebar-nav').style.display = 'block';

            const isSuperAdmin = userRoles.includes('super_admin');
            const isCompany = userRoles.includes('company');
            const isCandidate = userRoles.includes('candidate');

            // Role/Permission sections - Only for Super Admin
            const roleSections = [
                'roles-section',
                'permissions-section', 
                'rbac-section',
                'company-management-section'
            ];
            
            roleSections.forEach(sectionId => {
                const section = document.getElementById(sectionId);
                if (section) {
                    section.style.display = isSuperAdmin ? 'block' : 'none';
                }
            });

            // Job Management section - For Company and Super Admin
            const jobManagementSection = document.getElementById('job-management-section');
            if (jobManagementSection) {
                if (isSuperAdmin || isCompany) {
                    jobManagementSection.style.display = 'block';
                    
                    // Within job management, "All Jobs" is only for super admin
                    const allJobsItem = document.getElementById('all-jobs-item');
                    if (allJobsItem) {
                        allJobsItem.style.display = isSuperAdmin ? 'block' : 'none';
                    }
                    
                    // Company-specific job items
                    const companyJobItems = ['create-job-item', 'my-jobs-item', 'draft-jobs-item'];
                    companyJobItems.forEach(itemId => {
                        const item = document.getElementById(itemId);
                        if (item) {
                            item.style.display = (isCompany || isSuperAdmin) ? 'block' : 'none';
                        }
                    });
                } else {
                    jobManagementSection.style.display = 'none';
                }
            }

            // Note: User info is already stored globally by role-based-auth.js
            // and utility functions are available globally
        }
    </script>

</aside>
