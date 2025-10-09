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
                                <li class="breadcrumb-item"><a href="/" class="text-white-50">Home</a></li>
                                <li class="breadcrumb-item active" id="breadcrumbCategory">Jobs</li>
                            </ol>
                        </nav>
                        <h1 class="display-6 fw-bold mb-2" id="pageTitle">Browse Jobs</h1>
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

                <!-- Results Summary -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1" id="resultsCount">Loading jobs...</h5>
                                <small class="text-muted" id="resultsCategory"></small>
                            </div>
                            <div>
                                <button class="btn btn-outline-secondary btn-sm" id="clearFiltersBtn" style="display: none;">
                                    <i class="fas fa-times me-1"></i>Clear Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div class="row" id="loadingContainer">
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading jobs...</span>
                        </div>
                        <p class="text-muted">Loading available jobs...</p>
                    </div>
                </div>

                <!-- Jobs Container -->
                <div class="row g-4" id="jobsContainer" style="display: none;">
                    <!-- Jobs will be loaded here -->
                </div>

                <!-- No Results State -->
                <div class="row" id="noResultsContainer" style="display: none;">
                    <div class="col-12 text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-search fa-4x text-muted"></i>
                        </div>
                        <h4 class="mb-2">No Jobs Found</h4>
                        <p class="text-muted mb-4">We couldn't find any jobs matching your criteria.</p>
                        <button class="btn btn-primary" onclick="clearAllFilters()">
                            <i class="fas fa-refresh me-2"></i>View All Jobs
                        </button>
                    </div>
                </div>

                <!-- Error State -->
                <div class="row" id="errorContainer" style="display: none;">
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span id="errorMessage">Unable to load jobs. Please try again later.</span>
                        </div>
                        <button class="btn btn-outline-primary" onclick="loadJobs()">
                            <i class="fas fa-refresh me-2"></i>Try Again
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @include('frontend.pages.footer')
@endsection

<script>
    let allJobs = [];
    let allCategories = [];
    let currentCategoryId = null;
    let currentSearchTerm = '';

    // Get URL parameters
    function getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Update URL without reloading page
    function updateUrl(categoryId = null, search = null) {
        const params = new URLSearchParams();
        if (categoryId) params.set('category', categoryId);
        if (search) params.set('search', search);
        
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.replaceState({}, '', newUrl);
    }

    // Format salary range
    function formatSalary(minSalary, maxSalary) {
        if (!minSalary && !maxSalary) return 'Salary not disclosed';
        if (!minSalary) return `Up to $${maxSalary.toLocaleString()}`;
        if (!maxSalary) return `From $${minSalary.toLocaleString()}`;
        return `$${minSalary.toLocaleString()} - $${maxSalary.toLocaleString()}`;
    }

    // Format job type
    function formatJobType(jobType) {
        return jobType.split('-').map(word => 
            word.charAt(0).toUpperCase() + word.slice(1)
        ).join(' ');
    }

    // Get time ago
    function timeAgo(date) {
        const now = new Date();
        const posted = new Date(date);
        const diffTime = Math.abs(now - posted);
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 0) return 'Today';
        if (diffDays === 1) return '1 day ago';
        if (diffDays < 7) return `${diffDays} days ago`;
        if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`;
        return `${Math.floor(diffDays / 30)} months ago`;
    }

    // Render job card
    function renderJobCard(job, category) {
        const salaryText = formatSalary(job.salary_min, job.salary_max);
        const jobTypeText = formatJobType(job.job_type);
        const postedTime = timeAgo(job.created_at);
        const categoryName = category ? category.name : 'Unknown Category';
        
        return `
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 job-card" onclick="viewJobDetails(${job.id})">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1 fw-bold">${job.title}</h5>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-building me-1"></i>Company #${job.company_id}
                                </p>
                            </div>
                            ${job.is_featured ? '<span class="badge bg-warning text-dark">Featured</span>' : ''}
                        </div>
                        
                        <p class="card-text text-muted mb-3">${job.description.length > 150 ? job.description.substring(0, 150) + '...' : job.description}</p>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-sm-6">
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>${job.location}
                                </small>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>${jobTypeText}
                                </small>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted">
                                    <i class="fas fa-tag me-1"></i>${categoryName}
                                </small>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>${postedTime}
                                </small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fw-bold text-primary">${salaryText}</div>
                            <button class="btn btn-outline-primary btn-sm" onclick="event.stopPropagation(); applyForJob(${job.id})">
                                <i class="fas fa-paper-plane me-1"></i>Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Filter jobs
    function filterJobs() {
        let filteredJobs = [...allJobs];
        
        // Filter by category
        if (currentCategoryId) {
            filteredJobs = filteredJobs.filter(job => job.category_id == currentCategoryId);
        }
        
        // Filter by search term
        if (currentSearchTerm) {
            const searchLower = currentSearchTerm.toLowerCase();
            filteredJobs = filteredJobs.filter(job => 
                job.title.toLowerCase().includes(searchLower) ||
                job.description.toLowerCase().includes(searchLower) ||
                job.location.toLowerCase().includes(searchLower)
            );
        }
        
        return filteredJobs;
    }

    // Display jobs
    function displayJobs() {
        const filteredJobs = filterJobs();
        const container = document.getElementById('jobsContainer');
        const noResultsContainer = document.getElementById('noResultsContainer');
        const resultsCount = document.getElementById('resultsCount');
        const resultsCategory = document.getElementById('resultsCategory');
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        
        // Hide loading
        document.getElementById('loadingContainer').style.display = 'none';
        
        if (filteredJobs.length === 0) {
            container.style.display = 'none';
            noResultsContainer.style.display = 'block';
            resultsCount.textContent = 'No jobs found';
        } else {
            container.style.display = 'flex';
            noResultsContainer.style.display = 'none';
            
            container.innerHTML = '';
            filteredJobs.forEach(job => {
                const category = allCategories.find(cat => cat.id === job.category_id);
                container.insertAdjacentHTML('beforeend', renderJobCard(job, category));
            });
            
            // Update results count
            const jobText = filteredJobs.length === 1 ? 'job' : 'jobs';
            resultsCount.textContent = `${filteredJobs.length} ${jobText} found`;
        }
        
        // Update category info
        if (currentCategoryId) {
            const category = allCategories.find(cat => cat.id == currentCategoryId);
            resultsCategory.textContent = category ? `Category: ${category.name}` : '';
            clearFiltersBtn.style.display = 'inline-block';
        } else {
            resultsCategory.textContent = '';
            clearFiltersBtn.style.display = currentSearchTerm ? 'inline-block' : 'none';
        }
    }

    // Load categories for filter
    async function loadCategories() {
        try {
            const response = await fetch('/api/job-categories');
            if (!response.ok) throw new Error('Failed to fetch categories');
            
            allCategories = await response.json();
            
            // Populate category filter
            const categoryFilter = document.getElementById('categoryFilter');
            allCategories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categoryFilter.appendChild(option);
            });
            
            // Set initial category if specified in URL
            const categoryParam = getUrlParameter('category');
            if (categoryParam) {
                currentCategoryId = parseInt(categoryParam);
                categoryFilter.value = currentCategoryId;
                
                // Update page title
                const category = allCategories.find(cat => cat.id == currentCategoryId);
                if (category) {
                    document.getElementById('pageTitle').textContent = `${category.name} Jobs`;
                    document.getElementById('pageSubtitle').textContent = `Browse ${category.name.toLowerCase()} opportunities`;
                    document.getElementById('breadcrumbCategory').textContent = category.name;
                }
            }
            
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }

    // Load jobs
    async function loadJobs() {
        try {
            document.getElementById('errorContainer').style.display = 'none';
            
            const response = await fetch('/api/jobs');
            if (!response.ok) throw new Error('Failed to fetch jobs');
            
            allJobs = await response.json();
            
            // Set initial search if specified in URL
            const searchParam = getUrlParameter('search');
            if (searchParam) {
                currentSearchTerm = searchParam;
                document.getElementById('jobSearchInput').value = searchParam;
            }
            
            displayJobs();
            
        } catch (error) {
            console.error('Error loading jobs:', error);
            document.getElementById('loadingContainer').style.display = 'none';
            document.getElementById('errorContainer').style.display = 'block';
        }
    }

    // Clear all filters
    function clearAllFilters() {
        currentCategoryId = null;
        currentSearchTerm = '';
        document.getElementById('categoryFilter').value = '';
        document.getElementById('jobSearchInput').value = '';
        updateUrl();
        displayJobs();
        
        // Reset page title
        document.getElementById('pageTitle').textContent = 'Browse Jobs';
        document.getElementById('pageSubtitle').textContent = 'Discover exciting career opportunities';
        document.getElementById('breadcrumbCategory').textContent = 'Jobs';
    }

    // View job details (placeholder)
    function viewJobDetails(jobId) {
        console.log('View job details:', jobId);
        alert(`Job details for job ID: ${jobId}. This would normally redirect to a job details page.`);
    }

    // Apply for job (placeholder)
    function applyForJob(jobId) {
        console.log('Apply for job:', jobId);
        alert(`Apply for job ID: ${jobId}. This would normally open an application form.`);
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        loadCategories().then(() => {
            loadJobs();
        });

        // Search functionality
        const searchInput = document.getElementById('jobSearchInput');
        const searchButton = document.getElementById('searchButton');
        
        searchButton.addEventListener('click', function() {
            currentSearchTerm = searchInput.value.trim();
            updateUrl(currentCategoryId, currentSearchTerm || null);
            displayJobs();
        });
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchButton.click();
            }
        });

        // Category filter
        document.getElementById('categoryFilter').addEventListener('change', function() {
            currentCategoryId = this.value ? parseInt(this.value) : null;
            updateUrl(currentCategoryId, currentSearchTerm || null);
            displayJobs();
        });

        // Clear filters
        document.getElementById('clearFiltersBtn').addEventListener('click', clearAllFilters);
    });
</script>

<style>
    .job-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .job-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
    }
    
    .breadcrumb-light .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.5);
    }
    
    .spinner-border {
        width: 3rem;
        height: 3rem;
    }
</style>