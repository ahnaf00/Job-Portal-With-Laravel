<nav class="navbar navbar-main navbar-expand-lg  px-0 mx-4 shadow-none border-radius-xl z-index-sticky" id="navbarBlur"
    data-scroll="false">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm">
                    <a class="text-white" href="javascript:;">
                        <i class="ni ni-box-2"></i>
                    </a>
                </li>
                <li class="breadcrumb-item text-sm text-white"><a class="opacity-5 text-white"
                        href="javascript:;">Pages</a></li>
                <li class="breadcrumb-item text-sm text-white active" aria-current="page">Overview</li>
            </ol>
            <h6 class="font-weight-bolder mb-0 text-white">Overview</h6>
        </nav>
        <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none ">
            <a href="javascript:;" class="nav-link p-0">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                </div>
            </a>
        </div>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="input-group">
                    <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="Type here...">
                </div>
            </div>
            <ul class="navbar-nav  justify-content-end">
                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown pe-2 d-flex align-items-center" id="user-profile-dropdown" style="display: none;">
                    <a href="javascript:;" class="nav-link text-white p-0 d-flex align-items-center" id="userProfileButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar avatar-sm bg-gradient-primary me-2">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="d-flex flex-column text-start" id="user-info">
                            <span class="font-weight-bold" id="user-display-name">Loading...</span>
                            <small class="opacity-7" id="user-display-role">Loading...</small>
                        </div>
                        <i class="fa fa-chevron-down ms-2"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="userProfileButton">
                        <li class="mb-2">
                            <div class="dropdown-item-text border-radius-md">
                                <div class="d-flex py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold" id="dropdown-user-name">User Name</span>
                                        </h6>
                                        <p class="text-xs text-secondary mb-0" id="dropdown-user-email">
                                            user@example.com
                                        </p>
                                        <div class="mt-1">
                                            <span class="badge badge-sm bg-gradient-primary" id="dropdown-user-roles">
                                                Role
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item border-radius-md" href="#" onclick="viewProfile()">
                                <i class="fa fa-user me-2"></i>
                                View Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item border-radius-md" href="#" onclick="editProfile()">
                                <i class="fa fa-edit me-2"></i>
                                Edit Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item border-radius-md text-danger" href="#" onclick="logoutUser()">
                                <i class="fa fa-sign-out-alt me-2"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Loading State -->
                {{-- <li class="nav-item d-flex align-items-center" id="navbar-loading">
                    <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="text-white">Loading...</span>
                </li> --}}

                <!-- Fallback Sign In (shown if not authenticated) -->
                <li class="nav-item d-flex align-items-center" id="signin-fallback" style="display: none;">
                    <a href="{{ route('loginView') }}"
                        class="nav-link text-white font-weight-bold px-0">
                        <i class="fa fa-user me-sm-1"></i>
                        <span class="d-sm-inline d-none">Sign In</span>
                    </a>
                </li>

                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                        </div>
                    </a>
                </li>
                <li class="nav-item px-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white p-0">
                        <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                    </a>
                </li>
                <li class="nav-item dropdown pe-2 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell cursor-pointer"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="../../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 "
                                            alt="user image">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">New message</span> from Laur
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            13 minutes ago
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="../../assets/img/small-logos/logo-spotify.svg"
                                            class="avatar avatar-sm bg-gradient-dark  me-3 " alt="logo spotify">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">New album</span> by Travis Scott
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            1 day
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>credit-card</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF"
                                                    fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(453.000000, 454.000000)">
                                                            <path class="color-background"
                                                                d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"
                                                                opacity="0.593633743"></path>
                                                            <path class="color-background"
                                                                d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            Payment successfully completed
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            2 days
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    // Initialize navbar user profile on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Short delay to ensure role-based-auth.js is loaded
        setTimeout(initNavbarUserProfile, 100);

        // Listen for user profile loaded event from sidebar
        window.addEventListener('userProfileLoaded', function(event) {
            updateNavbarUserProfile(event.detail);
        });
    });

    async function initNavbarUserProfile() {
        try {
            // Check if we already have user data from role-based-auth.js
            if (window.currentUser) {
                updateNavbarUserProfile(window.currentUser);
                return;
            }

            // If no current user, try to get profile
            const profile = await getCurrentUserProfile();
            if (profile) {
                updateNavbarUserProfile({
                    user: profile.user,
                    roles: profile.roles
                });
            } else {
                showSignInFallback();
            }
        } catch (error) {
            console.error('Failed to load navbar user profile:', error);
            showSignInFallback();
        }
    }

    function updateNavbarUserProfile(userInfo) {
        // Hide loading state
        document.getElementById('navbar-loading').style.display = 'none';

        // Hide signin fallback
        document.getElementById('signin-fallback').style.display = 'none';

        // Show user profile dropdown
        document.getElementById('user-profile-dropdown').style.display = 'flex';

        // Update user display name and role
        document.getElementById('user-display-name').textContent = userInfo.user.name;
        document.getElementById('user-display-role').textContent = userInfo.roles.join(', ');

        // Update dropdown details
        document.getElementById('dropdown-user-name').textContent   = userInfo.user.name;
        document.getElementById('dropdown-user-email').textContent  = userInfo.user.email;
        document.getElementById('dropdown-user-roles').textContent  = userInfo.roles.join(', ');
    }

    function showSignInFallback() {
        document.getElementById('navbar-loading').style.display = 'none';
        document.getElementById('user-profile-dropdown').style.display = 'none';
        document.getElementById('signin-fallback').style.display = 'flex';
    }

    function viewProfile() {
        // Navigate to profile view page
        showMessage('Profile view functionality coming soon!', 'info');
    }

    function editProfile() {
        // Navigate to profile edit page
        showMessage('Profile edit functionality coming soon!', 'info');
    }

    // Helper function to show messages
    function showMessage(message, type = 'info') {
        const alertType = type === 'error' ? 'danger' : type;
        const alertHtml = `
            <div class="alert alert-${alertType} alert-dismissible fade show position-fixed"
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', alertHtml);

        // Auto dismiss after 3 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert:last-of-type');
            if (alert) {
                alert.remove();
            }
        }, 3000);
    }

    // Note: logoutUser() is already defined in role-based-auth.js
</script>
