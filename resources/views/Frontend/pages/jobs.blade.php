@extends('frontend.layouts.master')

@section('frontend-content')
    @include('Frontend.pages.header')
    <main>
        <!-- Page Header -->
        <section class="py-5 bg-gradient-primary text-white">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-light mb-3">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-black-50">Home</a></li>
                                <li class="breadcrumb-item active" id="breadcrumbCategory">Jobs</li>
                            </ol>
                        </nav>
                        <h1 class="display-6 fw-bold mb-2 text-body" id="pageTitle">{{ $category->name }}</h1>
                        <p class="lead mb-0" id="pageSubtitle">Discover exciting career opportunities</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="bg-white bg-opacity-10 rounded-4 p-3 d-inline-block">
                            <i class="fas fa-briefcase fa-3x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Jobs Listing -->
        <section class="py-5">
            <div class="container">
                <!-- Filter and Search Bar -->
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control" placeholder="Search jobs..." id="jobSearchInput">
                            <button class="btn btn-primary" type="button" id="searchButton">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <select class="form-select form-select-lg" id="categoryFilter">
                            <option value="">All Categories</option>
                        </select>
                    </div>
                </div>

                <!-- Jobs Container -->
                <div class="row g-4" id="jobsContainer" style="display: none;">
                    <!-- Jobs will be loaded here -->
                </div>
                @foreach ($jobs as $job)
                    <div class="col-md-6 my-3">
                        <div class="card job-card rounded-4 border-0 shadow-sm p-4 d-flex flex-row align-items-center">
                            <img src="https://placehold.co/80x80/285F5D/fff?text=C" class="rounded-3 me-3" alt="Company Logo">
                            <div class="flex-grow-1">
                                <h5 class="card-title fw-bold mb-1">{{ $job->title }}</h5>
                                <p class="card-text text-muted mb-1">Creative Agency, New York</p>
                                <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $job->job_type }}</span>
                            </div>
                            <div class="text-end">
                                <div class="text-primary fw-bold mb-1">${{$job->salary_min}} - ${{ $job->salary_max }}</div>
                                <small class="text-muted">2 days ago</small>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </section>
    </main>

    @include('frontend.pages.footer')
@endsection



