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
            <a href="{{ route('loginView') }}" class="btn btn-sm btn-outline-secondary me-2 rounded-pill d-none d-lg-block">Log in</a>
            <a href="{{ route('registerView') }}" class="btn btn-sm btn-outline-secondary me-2 rounded-pill d-none d-lg-block">Register</a>
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
