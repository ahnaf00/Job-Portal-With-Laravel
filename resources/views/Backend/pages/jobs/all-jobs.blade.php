@extends('backend.layouts.master')
@section('content')
    <div class="col-12 mt-5">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <div>
                    <h6>All Jobs</h6>
                    <p class="text-sm mb-0">Manage all job postings across the platform</p>
                </div>
                <div>
                    <a href="{{ route('createJobView') }}" class="btn btn-primary btn-sm me-2">
                        <i class="fas fa-plus me-2"></i>Create New Job
                    </a>
                    <a href="{{ route('getMyJobs') }}" class="btn btn-info btn-sm me-2">
                        <i class="fas fa-briefcase me-2"></i>My Jobs
                    </a>
                    <a href="{{ route('getDraftJobs') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-file-alt me-2"></i>Draft Jobs
                    </a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <!-- Search and Filter -->
                <div class="row px-4 mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control" id="searchJobs" placeholder="Search jobs..." onkeyup="filterJobs()">
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
                            <select class="form-control" id="filterCategory" onchange="filterJobs()">
                                <option value="">All Categories</option>
                                <!-- Categories will be loaded dynamically -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="form-control" id="sortJobs" onchange="sortJobs()">
                                <option value="date_desc">Newest First</option>
                                <option value="date_asc">Oldest First</option>
                                <option value="title_asc">Title (A-Z)</option>
                                <option value="title_desc">Title (Z-A)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="text-center p-4">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading jobs...</p>
                </div>

                <!-- Jobs Table -->
                <div id="jobsTable" class="table-responsive p-0" style="display: none;">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Job Title</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Company</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Applications</th>
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
                    <h5 class="text-muted">No jobs found</h5>
                    <p class="text-muted">Get started by creating your first job posting.</p>
                    <a href="{{ route('createJobView') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>Create Job
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
                    <p class="text-danger" id="errorMessage">Failed to load jobs.</p>
                    <button class="btn btn-primary btn-sm" onclick="fetchJobs()">
                        <i class="fas fa-retry me-2"></i>Retry
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Details Modal -->
    <div class="modal fade" id="jobModal" tabindex="-1" aria-labelledby="jobModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jobModalLabel">Job Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="jobModalBody">
                    <!-- Job details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="editJobBtn" onclick="editJob()">
                        <i class="fas fa-edit me-2"></i>Edit Job
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let allJobs = [];
    let filteredJobs = [];
    let jobCategories = [];
    let currentJob = null;

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

    // Fetch jobs from API
    async function fetchJobs() {
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
            // Fetch jobs and categories in parallel
            const [jobsResponse, categoriesResponse] = await Promise.all([
                fetch('http://127.0.0.1:8000/api/jobs-admin', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                }),
                fetch('http://127.0.0.1:8000/api/job-categories', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
            ]);

            if (!jobsResponse.ok) {
                if (jobsResponse.status === 401) {
                    showErrorState('Session expired. Please log in again.');
                    localStorage.removeItem('access_token');
                    setTimeout(() => {
                        window.location.href = "{{ route('loginView') }}";
                    }, 2000);
                    return;
                }
                if (jobsResponse.status === 403) {
                    showErrorState('Access denied. You need appropriate permissions to view jobs.');
                    return;
                }
                throw new Error(`Failed to fetch jobs: ${jobsResponse.status} ${jobsResponse.statusText}`);
            }

            allJobs = await jobsResponse.json();

            if (categoriesResponse.ok) {
                jobCategories = await categoriesResponse.json();
                loadCategories();
            }

            filteredJobs = [...allJobs];
            renderJobs(filteredJobs);

        } catch (error) {
            console.error('Error fetching jobs:', error);
            showErrorState('Failed to load jobs. Please try again.');
        }
    }

    // Load categories into filter dropdown
    function loadCategories() {
        const categorySelect = document.getElementById('filterCategory');
        jobCategories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });
    }

    // Render jobs in table
    function renderJobs(jobsData) {
        if (!Array.isArray(jobsData) || jobsData.length === 0) {
            if (allJobs.length === 0) {
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
                                <p class="text-xs text-secondary mb-0">${job.location}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">${job.company ? job.company.name : 'N/A'}</h6>
                                <p class="text-xs text-secondary mb-0">${job.job_type}</p>
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
                        <span class="text-secondary text-xs font-weight-bold">${createdDate}</span>
                    </td>
                    <td class="align-middle">
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="viewJob(${job.id})" title="View Details">
                            <i class="fas fa-eye"></i>View Details
                        </button>
                        <button class="btn btn-sm btn-outline-success me-1" onclick="editJob(${job.id})" title="Edit Job">
                            <i class="fas fa-edit"></i> Edit Job
                        </button>
                        ${!job.is_published ?
                            `<button class="btn btn-sm btn-outline-info me-1" onclick="publishJob(${job.id})" title="Publish">
                                <i class="fas fa-upload"></i> Publish
                            </button>` :
                            `<button class="btn btn-sm btn-outline-warning me-1" onclick="unpublishJob(${job.id})" title="Unpublish">
                                <i class="fas fa-download"></i> Unpublish
                            </button>`
                        }
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteJob(${job.id})" title="Delete">
                            <i class="fas fa-trash"></i> Delete
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
        const categoryFilter = document.getElementById('filterCategory').value;

        filteredJobs = allJobs.filter(job => {
            const matchesSearch = !searchTerm ||
                job.title.toLowerCase().includes(searchTerm) ||
                (job.company && job.company.name.toLowerCase().includes(searchTerm)) ||
                job.location.toLowerCase().includes(searchTerm);

            const matchesStatus = !statusFilter ||
                (statusFilter === 'published' && job.is_published) ||
                (statusFilter === 'draft' && !job.is_published) ||
                (statusFilter === 'featured' && job.is_featured);

            const matchesCategory = !categoryFilter ||
                (job.job_category && job.job_category.id == categoryFilter);

            return matchesSearch && matchesStatus && matchesCategory;
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
            case 'title_desc':
                filteredJobs.sort((a, b) => b.title.localeCompare(a.title));
                break;
            case 'date_desc':
                filteredJobs.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                break;
            case 'date_asc':
                filteredJobs.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                break;
        }

        renderJobs(filteredJobs);
    }

    // Clear filters
    function clearFilters() {
        document.getElementById('searchJobs').value = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('filterCategory').value = '';
        document.getElementById('sortJobs').value = 'date_desc';
        filteredJobs = [...allJobs];
        renderJobs(filteredJobs);
    }

    // View job details
    function viewJob(jobId) {
        const job = allJobs.find(j => j.id === jobId);
        if (!job) return;

        currentJob = job;

        const modalBody = document.getElementById('jobModalBody');
        modalBody.innerHTML = `
            <div class="row">
                <div class="col-md-8">
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Job Information</h6>
                    <p class="text-sm mb-2"><strong>Title:</strong> ${job.title}</p>
                    <p class="text-sm mb-2"><strong>Company:</strong> ${job.company ? job.company.name : 'N/A'}</p>
                    <p class="text-sm mb-2"><strong>Category:</strong> ${job.job_category ? job.job_category.name : 'Uncategorized'}</p>
                    <p class="text-sm mb-2"><strong>Location:</strong> ${job.location}</p>
                    <p class="text-sm mb-2"><strong>Job Type:</strong> ${job.job_type}</p>
                    <p class="text-sm mb-2"><strong>Salary Range:</strong> ${job.salary_min && job.salary_max ? `$${job.salary_min} - $${job.salary_max}` : 'Not specified'}</p>
                </div>
                <div class="col-md-4">
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Status & Stats</h6>
                    <p class="text-sm mb-2"><strong>Status:</strong>
                        <span class="badge ${job.is_published ? 'bg-success' : 'bg-warning'}">${job.is_published ? 'Published' : 'Draft'}</span>
                    </p>
                    <p class="text-sm mb-2"><strong>Featured:</strong>
                        <span class="badge ${job.is_featured ? 'bg-info' : 'bg-secondary'}">${job.is_featured ? 'Yes' : 'No'}</span>
                    </p>
                    <p class="text-sm mb-2"><strong>Applications:</strong> ${job.job_applications ? job.job_applications.length : 0}</p>
                    <p class="text-sm mb-2"><strong>Created:</strong> ${new Date(job.created_at).toLocaleDateString()}</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder">Description</h6>
                    <p class="text-sm">${job.description || 'No description provided'}</p>
                </div>
            </div>
        `;

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('jobModal'));
        modal.show();
    }

    // Edit job
    function editJob(jobId = null) {
        const id = jobId || (currentJob ? currentJob.id : null);
        if (id) {
            window.location.href = `/jobs/${id}/edit`;
        }
    }

    // Publish job
    async function publishJob(jobId) {
        if (!confirm('Are you sure you want to publish this job?')) return;
        await updateJobStatus(jobId, 'publish');
    }

    // Unpublish job
    async function unpublishJob(jobId) {
        if (!confirm('Are you sure you want to unpublish this job?')) return;
        await updateJobStatus(jobId, 'unpublish');
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
                allJobs = allJobs.filter(j => j.id !== jobId);
                filteredJobs = filteredJobs.filter(j => j.id !== jobId);
            } else {
                // Update job in local arrays
                const jobIndex = allJobs.findIndex(j => j.id === jobId);
                if (jobIndex !== -1) {
                    allJobs[jobIndex] = result.job;
                }
                const filteredIndex = filteredJobs.findIndex(j => j.id === jobId);
                if (filteredIndex !== -1) {
                    filteredJobs[filteredIndex] = result.job;
                }
            }

            // Re-render the table
            renderJobs(filteredJobs);

            alert(result.message || `Job ${action}ed successfully!`);

        } catch (error) {
            console.error(`Error ${action}ing job:`, error);
            alert(`Failed to ${action} job. Please try again.`);
        }
    }

    // Load jobs when page loads
    document.addEventListener('DOMContentLoaded', fetchJobs);
    </script>
@endsection
