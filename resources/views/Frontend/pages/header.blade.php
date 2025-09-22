<header class="py-3 sticky-top bg-white shadow-sm" role="banner">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="/" class="logo fw-bold fs-4 text-decoration-none">
            <span class="text-primary">Job</span><span class="text-secondary">cy.</span>
        </a>
        <nav class="d-none d-lg-block">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link text-dark dropdown-toggle" href="#" id="browseJobsDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Browse Jobs
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="browseJobsDropdown">
                        <li><a class="dropdown-item" href="/jobs">All Jobs</a></li>
                        <li><a class="dropdown-item" href="/jobs">Job Categories</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link text-dark dropdown-toggle" href="#" id="pagesDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Pages
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="pagesDropdown">
                        <li><a class="dropdown-item" href="#">About Us</a></li>
                        <li><a class="dropdown-item" href="#">Pricing</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="#">Contact</a>
                </li>
            </ul>
        </nav>
        <div class="d-flex align-items-center">
            <!-- Authentication Buttons - Shown when NOT logged in -->
            <div id="auth-buttons" class="d-flex align-items-center">
                <a href="{{ route('loginView') }}" class="btn btn-sm btn-outline-secondary me-2 rounded-pill d-none d-lg-block">Log in</a>
                <a href="{{ route('registerView') }}" class="btn btn-sm btn-outline-secondary me-2 rounded-pill d-none d-lg-block">Register</a>
            </div>
            
            <!-- User Profile Dropdown - Shown when logged in -->
            <div id="user-profile-section" class="dropdown d-none align-items-center me-2">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" 
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                         style="width: 32px; height: 32px; font-size: 14px;">
                        <i class="fa fa-user"></i>
                    </div>
                    <span class="ms-2 text-dark d-none d-lg-inline" id="user-display-name">User</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <div class="dropdown-item-text">
                            <div class="d-flex flex-column">
                                <span class="fw-bold" id="dropdown-user-name">User Name</span>
                                <small class="text-muted" id="dropdown-user-email">user@example.com</small>
                                <small class="text-primary" id="dropdown-user-roles">Role</small>
                            </div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a></li>
                    <li><a class="dropdown-item" href="#" onclick="viewProfile()"><i class="fa fa-user me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="logoutUser()"><i class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
            
            <a href="#" class="btn btn-sm btn-primary text-white rounded-pill">Post a Job</a>
            <button class="navbar-toggler d-lg-none ms-2" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="fa fa-bars text-secondary"></span>
            </button>
        </div>
    </div>
    <div class="collapse navbar-collapse d-lg-none" id="navbarNav">
        <ul class="navbar-nav mx-3">
            <li class="nav-item">
                <a class="nav-link" href="/">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/jobs">Browse Jobs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Pages</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Contact</a>
            </li>
        </ul>
    </div>
</header>

<script>
    // Frontend Header Authentication Handler
    document.addEventListener('DOMContentLoaded', function() {
        checkAuthenticationStatus();
    });

    async function checkAuthenticationStatus() {
        const token = localStorage.getItem('access_token');
        if (!token) {
            showAuthButtons();
            return;
        }

        try {
            const response = await fetch('http://127.0.0.1:8000/api/profile', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const profile = await response.json();
                showUserProfile(profile);
            } else {
                // Token is invalid, remove it and show auth buttons
                localStorage.removeItem('access_token');
                showAuthButtons();
            }
        } catch (error) {
            console.error('Error checking authentication:', error);
            showAuthButtons();
        }
    }

    function showAuthButtons() {
        document.getElementById('auth-buttons').classList.remove('d-none');
        document.getElementById('user-profile-section').classList.add('d-none');
    }

    function showUserProfile(profile) {
        document.getElementById('auth-buttons').classList.add('d-none');
        document.getElementById('user-profile-section').classList.remove('d-none');
        document.getElementById('user-profile-section').classList.add('d-flex');
        
        // Update profile information
        document.getElementById('user-display-name').textContent = profile.user.name;
        document.getElementById('dropdown-user-name').textContent = profile.user.name;
        document.getElementById('dropdown-user-email').textContent = profile.user.email;
        document.getElementById('dropdown-user-roles').textContent = profile.roles.join(', ');
    }

    function viewProfile() {
        alert('Profile view functionality coming soon!');
    }

    async function logoutUser() {
        const token = localStorage.getItem('access_token');
        if (token) {
            try {
                await fetch('http://127.0.0.1:8000/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
            } catch (error) {
                console.error('Logout error:', error);
            }
        }
        
        localStorage.removeItem('access_token');
        window.location.href = "/";
    }
</script>
