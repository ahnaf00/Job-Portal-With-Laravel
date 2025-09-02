@extends('backend.layouts.master')
@section('content')
    <div class="col-12 mt-5">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <div>
                    <h6>My Jobs</h6>
                    <p class="text-sm mb-0">Manage your company's job postings</p>
                </div>
                <div>
                    <a href="{{ route('createJobView') }}" class="btn btn-primary btn-sm me-2">
                        <i class="fas fa-plus me-2"></i>Create New Job
                    </a>
                    <a href="{{ route('getDraftJobs') }}" class="btn btn-warning btn-sm me-2">
                        <i class="fas fa-file-alt me-2"></i>Draft Jobs
                    </a>
                    <a href="{{ route('getAllJobs') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-list me-2"></i>All Jobs
                    </a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <!-- Search and Filter -->
                <div class="row px-4 mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" id="searchJobs" placeholder="Search your jobs..." onkeyup="filterJobs()">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control" id="filterStatus" onchange="filterJobs()">
                                <option value="">All Status</option>
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                                <option value="featured">Featured</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control" id="sortJobs" onchange="sortJobs()">
                                <option value="date_desc">Newest First</option>
                                <option value="date_asc">Oldest First</option>
                                <option value="title_asc">Title (A-Z)</option>
                                <option value="applications_desc">Most Applications</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Company Status Alert -->
                <div id="companyStatusAlert" class="alert alert-warning mx-4" style="display: none;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <strong>Company Verification Required</strong>
                            <p class="mb-0">Your company needs to be verified before you can publish jobs. You can still create and save drafts.</p>
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading your jobs...</p>
                </div>

                <!-- Jobs Table -->
                <div id="jobsTable" class="table-responsive p-0" style="display: none;">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Job Title</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Applications</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Views</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="jobsBody">
                            <!-- Jobs will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="text-center p-4" style="display: none;">
                    <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No jobs posted yet</h5>
                    <p class="text-muted">Create your first job posting to attract talented candidates.</p>
                    <a href="{{ route('createJobView') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>Create Your First Job
                    </a>
                </div>

                <!-- No Results State -->
                <div id="noResultsState" class="text-center p-4" style="display: none;">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No jobs match your search</h5>
                    <p class="text-muted">Try adjusting your search terms or filters.</p>
                    <button class="btn btn-primary btn-sm" onclick="clearFilters()">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </button>
                </div>

                <!-- Error State -->
                <div id="errorState" class="text-center p-4" style="display: none;">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="text-danger" id="errorMessage">Failed to load your jobs.</p>
                    <button class="btn btn-primary btn-sm" onclick="fetchMyJobs()">
                        <i class="fas fa-retry me-2"></i>Retry
                    </button>
                </div>
            </div>
        </div>

        <!-- Job Statistics Card -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Jobs</p>
                                    <h5 class="font-weight-bolder mb-0" id="totalJobs">0</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                    <i class="ni ni-briefcase-24 text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Published</p>
                                    <h5 class="font-weight-bolder mb-0" id="publishedJobs">0</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="ni ni-check-bold text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Draft Jobs</p>
                                    <h5 class="font-weight-bolder mb-0" id="draftJobs">0</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                    <i class="ni ni-single-copy-04 text-lg opacity-10"></i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Applications</p>
                                    <h5 class="font-weight-bolder mb-0" id="totalApplications">0</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i class="ni ni-single-02 text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    let myJobs = [];
    let filteredJobs = [];
    let userCompany = null;

    // Show different states
    function showLoadingState() {
        document.getElementById('loadingState').style.display = 'block';
        document.getElementById('jobsTable').style.display = 'none';
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('noResultsState').style.display = 'none';
        document.getElementById('errorState').style.display = 'none';
    }

    function showJobsTable() {
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('jobsTable').style.display = 'block';
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('noResultsState').style.display = 'none';
        document.getElementById('errorState').style.display = 'none';
    }

    function showEmptyState() {
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('jobsTable').style.display = 'none';
        document.getElementById('emptyState').style.display = 'block';
        document.getElementById('noResultsState').style.display = 'none';
        document.getElementById('errorState').style.display = 'none';
    }

    function showNoResultsState() {
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('jobsTable').style.display = 'none';
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('noResultsState').style.display = 'block';
        document.getElementById('errorState').style.display = 'none';
    }

    function showErrorState(message) {
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('jobsTable').style.display = 'none';
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('noResultsState').style.display = 'none';
        document.getElementById('errorState').style.display = 'block';
        document.getElementById('errorMessage').textContent = message;
    }

    // Fetch my jobs from API
    async function fetchMyJobs() {
        const token = localStorage.getItem('access_token');
        
        if (!token) {
            showErrorState('You are not authenticated. Please log in.');
            setTimeout(() => {
                window.location.href = "{{ route('loginView') }}";
            }, 2000);
            return;
        }

        showLoadingState();

        try {
            const response = await fetch('http://127.0.0.1:8000/api/jobs-my', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });

            if (!response.ok) {
                if (response.status === 401) {
                    showErrorState('Session expired. Please log in again.');
                    localStorage.removeItem('access_token');
                    setTimeout(() => {
                        window.location.href = "{{ route('loginView') }}";
                    }, 2000);
                    return;
                }
                if (response.status === 403) {
                    showErrorState('Access denied. You need to be a company user to view jobs.');
                    return;
                }
                if (response.status === 404) {
                    showErrorState('No company associated with your account. Please contact administrator.');
                    return;
                }
                throw new Error(`Failed to fetch jobs: ${response.status} ${response.statusText}`);
            }

            myJobs = await response.json();
            filteredJobs = [...myJobs];
            
            // Check company verification status
            if (myJobs.length > 0 && myJobs[0].company) {
                userCompany = myJobs[0].company;
                if (!userCompany.is_verified) {
                    document.getElementById('companyStatusAlert').style.display = 'block';
                }
            }

            updateStatistics();
            renderJobs(filteredJobs);
            
        } catch (error) {
            console.error('Error fetching my jobs:', error);
            showErrorState('Failed to load your jobs. Please try again.');
        }
    }

    // Update statistics
    function updateStatistics() {
        const total = myJobs.length;
        const published = myJobs.filter(job => job.is_published).length;
        const drafts = myJobs.filter(job => !job.is_published).length;
        const totalApplications = myJobs.reduce((sum, job) => sum + (job.job_applications ? job.job_applications.length : 0), 0);

        document.getElementById('totalJobs').textContent = total;
        document.getElementById('publishedJobs').textContent = published;
        document.getElementById('draftJobs').textContent = drafts;
        document.getElementById('totalApplications').textContent = totalApplications;
    }

    // Render jobs in table
    function renderJobs(jobsData) {
        if (!Array.isArray(jobsData) || jobsData.length === 0) {
            if (myJobs.length === 0) {
                showEmptyState();
            } else {
                showNoResultsState();
            }
            return;
        }

        const tbody = document.getElementById('jobsBody');
        tbody.innerHTML = '';

        jobsData.forEach((job, index) => {
            const statusBadges = [];
            
            if (job.is_published) {
                statusBadges.push('<span class="badge badge-sm bg-gradient-success me-1">Published</span>');
            } else {
                statusBadges.push('<span class="badge badge-sm bg-gradient-warning me-1">Draft</span>');
            }
            
            if (job.is_featured) {
                statusBadges.push('<span class="badge badge-sm bg-gradient-info">Featured</span>');
            }

            const createdDate = new Date(job.created_at).toLocaleDateString();
            const applicationsCount = job.job_applications ? job.job_applications.length : 0;

            const row = `
                <tr>
                    <td>
                        <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">${index + 1}</h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">${job.title}</h6>
                                <p class="text-xs text-secondary mb-0">${job.location} • ${job.job_type}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-secondary text-xs font-weight-bold">${job.job_category ? job.job_category.name : 'Uncategorized'}</span>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap">
                            ${statusBadges.join('')}
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="text-secondary text-xs font-weight-bold">${applicationsCount}</span>
                            <i class="fas fa-users ms-1 text-xs"></i>
                        </div>
                    </td>
                    <td>
                        <span class="text-secondary text-xs font-weight-bold">-</span>
                    </td>
                    <td>
                        <span class="text-secondary text-xs font-weight-bold">${createdDate}</span>
                    </td>
                    <td class="align-middle">
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="editJob(${job.id})" title="Edit Job">
                            <i class="fas fa-edit"></i>
                        </button>
                        ${!job.is_published ? 
                            `<button class="btn btn-sm btn-outline-success me-1" onclick="publishJob(${job.id})" title="Publish" ${!userCompany?.is_verified ? 'disabled' : ''}>
                                <i class="fas fa-upload"></i>
                            </button>` : 
                            `<button class="btn btn-sm btn-outline-warning me-1" onclick="unpublishJob(${job.id})" title="Unpublish">
                                <i class="fas fa-download"></i>
                            </button>`
                        }
                        <button class="btn btn-sm btn-outline-info me-1" onclick="toggleFeatured(${job.id})" title="Toggle Featured">
                            <i class="fas ${job.is_featured ? 'fa-star' : 'fa-star-o'}"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteJob(${job.id})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });

        showJobsTable();
    }

    // Filter and search jobs
    function filterJobs() {
        const searchTerm = document.getElementById('searchJobs').value.toLowerCase();
        const statusFilter = document.getElementById('filterStatus').value;
        
        filteredJobs = myJobs.filter(job => {
            const matchesSearch = !searchTerm || 
                job.title.toLowerCase().includes(searchTerm) ||
                job.location.toLowerCase().includes(searchTerm) ||
                (job.job_category && job.job_category.name.toLowerCase().includes(searchTerm));
            
            const matchesStatus = !statusFilter || 
                (statusFilter === 'published' && job.is_published) ||
                (statusFilter === 'draft' && !job.is_published) ||
                (statusFilter === 'featured' && job.is_featured);
            
            return matchesSearch && matchesStatus;
        });
        
        renderJobs(filteredJobs);
    }

    // Sort jobs
    function sortJobs() {
        const sortValue = document.getElementById('sortJobs').value;
        
        switch(sortValue) {
            case 'title_asc':
                filteredJobs.sort((a, b) => a.title.localeCompare(b.title));
                break;
            case 'date_desc':
                filteredJobs.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                break;
            case 'date_asc':
                filteredJobs.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                break;
            case 'applications_desc':
                filteredJobs.sort((a, b) => {
                    const aCount = a.job_applications ? a.job_applications.length : 0;
                    const bCount = b.job_applications ? b.job_applications.length : 0;
                    return bCount - aCount;
                });
                break;
        }
        
        renderJobs(filteredJobs);
    }

    // Clear filters
    function clearFilters() {
        document.getElementById('searchJobs').value = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('sortJobs').value = 'date_desc';
        filteredJobs = [...myJobs];
        renderJobs(filteredJobs);
    }

    // Edit job
    function editJob(jobId) {
        window.location.href = `/jobs/${jobId}/edit`;
    }

    // Publish job
    async function publishJob(jobId) {
        if (!userCompany?.is_verified) {
            alert('Your company needs to be verified before you can publish jobs.');
            return;
        }
        if (!confirm('Are you sure you want to publish this job?')) return;
        await updateJobStatus(jobId, 'publish');
    }

    // Unpublish job
    async function unpublishJob(jobId) {
        if (!confirm('Are you sure you want to unpublish this job?')) return;
        await updateJobStatus(jobId, 'unpublish');
    }

    // Toggle featured status
    async function toggleFeatured(jobId) {
        await updateJobStatus(jobId, 'toggle-featured');
    }

    // Delete job
    async function deleteJob(jobId) {
        if (!confirm('Are you sure you want to delete this job? This action cannot be undone.')) return;
        await updateJobStatus(jobId, 'delete');
    }

    // Update job status
    async function updateJobStatus(jobId, action) {
        const token = localStorage.getItem('access_token');
        
        if (!token) {
            alert('You are not authenticated. Please log in.');
            return;
        }

        try {
            let url, method;
            
            switch(action) {
                case 'publish':
                    url = `http://127.0.0.1:8000/api/jobs/${jobId}/publish`;
                    method = 'PATCH';
                    break;
                case 'unpublish':
                    url = `http://127.0.0.1:8000/api/jobs/${jobId}/unpublish`;
                    method = 'PATCH';
                    break;
                case 'toggle-featured':
                    url = `http://127.0.0.1:8000/api/jobs/${jobId}/toggle-featured`;
                    method = 'PATCH';
                    break;
                case 'delete':
                    url = `http://127.0.0.1:8000/api/jobs/${jobId}`;
                    method = 'DELETE';
                    break;
                default:
                    return;
            }

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });

            if (!response.ok) {
                throw new Error(`Failed to ${action} job`);
            }

            const result = await response.json();
            
            if (action === 'delete') {
                // Remove job from local arrays
                myJobs = myJobs.filter(j => j.id !== jobId);
                filteredJobs = filteredJobs.filter(j => j.id !== jobId);
            } else {
                // Update job in local arrays
                const jobIndex = myJobs.findIndex(j => j.id === jobId);
                if (jobIndex !== -1) {
                    myJobs[jobIndex] = result.job;
                }
                const filteredIndex = filteredJobs.findIndex(j => j.id === jobId);
                if (filteredIndex !== -1) {
                    filteredJobs[filteredIndex] = result.job;
                }
            }
            
            // Update statistics and re-render
            updateStatistics();
            renderJobs(filteredJobs);
            
            alert(result.message || `Job ${action}ed successfully!`);

        } catch (error) {
            console.error(`Error ${action}ing job:`, error);
            alert(`Failed to ${action} job. Please try again.`);
        }
    }

    // Load my jobs when page loads
    document.addEventListener('DOMContentLoaded', fetchMyJobs);
    </script>
@endsection