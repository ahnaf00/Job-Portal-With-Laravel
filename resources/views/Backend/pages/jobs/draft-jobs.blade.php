@extends('backend.layouts.master')
@section('content')
    <div class="col-12 mt-5">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <div>
                    <h6>Draft Jobs</h6>
                    <p class="text-sm mb-0">Jobs saved as drafts that haven't been published yet</p>
                </div>
                <div>
                    <a href="{{ route('createJobView') }}" class="btn btn-primary btn-sm me-2">
                        <i class="fas fa-plus me-2"></i>Create New Job
                    </a>
                    <a href="{{ route('getMyJobs') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-briefcase me-2"></i>My Jobs
                    </a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <!-- Search and Filter -->
                <div class="row px-4 mb-3">
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="text" class="form-control" id="searchJobs" placeholder="Search draft jobs..." onkeyup="filterJobs()">
                        </div>
                    </div>
                    <div class="col-md-4">
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

                <!-- Company Status Alert -->
                <div id="companyStatusAlert" class="alert alert-info mx-4" style="display: none;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>
                            <strong>Company Verification Status</strong>
                            <p class="mb-0" id="verificationMessage">Loading verification status...</p>
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="text-center p-4">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading draft jobs...</p>
                </div>

                <!-- Jobs Table -->
                <div id="jobsTable" class="table-responsive p-0" style="display: none;">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Job Title</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Location</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Last Modified</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="jobsBody">
                            <!-- Draft jobs will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="text-center p-4" style="display: none;">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No draft jobs</h5>
                    <p class="text-muted">All your jobs are published or you haven't created any drafts yet.</p>
                    <a href="{{ route('createJobView') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>Create New Job
                    </a>
                </div>

                <!-- No Results State -->
                <div id="noResultsState" class="text-center p-4" style="display: none;">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No drafts match your search</h5>
                    <p class="text-muted">Try adjusting your search terms.</p>
                    <button class="btn btn-primary btn-sm" onclick="clearFilters()">
                        <i class="fas fa-times me-2"></i>Clear Search
                    </button>
                </div>

                <!-- Error State -->
                <div id="errorState" class="text-center p-4" style="display: none;">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="text-danger" id="errorMessage">Failed to load draft jobs.</p>
                    <button class="btn btn-primary btn-sm" onclick="fetchDraftJobs()">
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
                    <h5 class="modal-title" id="jobModalLabel">Draft Job Details</h5>
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
                    <button type="button" class="btn btn-success" id="publishJobBtn" onclick="publishJob()">
                        <i class="fas fa-upload me-2"></i>Publish Job
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let draftJobs = [];
    let filteredJobs = [];
    let userCompany = null;
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

    // Fetch draft jobs from API
    async function fetchDraftJobs() {
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
            const response = await fetch('http://127.0.0.1:8000/api/jobs-drafts', {
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
                throw new Error(`Failed to fetch draft jobs: ${response.status} ${response.statusText}`);
            }

            draftJobs = await response.json();
            filteredJobs = [...draftJobs];

            // Check company verification status
            if (draftJobs.length > 0 && draftJobs[0].company) {
                userCompany = draftJobs[0].company;
                updateCompanyStatus();
            } else {
                // Try to get company info from my jobs endpoint
                try {
                    const myJobsResponse = await fetch('http://127.0.0.1:8000/api/jobs-my', {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        }
                    });
                    if (myJobsResponse.ok) {
                        const myJobs = await myJobsResponse.json();
                        if (myJobs.length > 0 && myJobs[0].company) {
                            userCompany = myJobs[0].company;
                            updateCompanyStatus();
                        }
                    }
                } catch (e) {
                    console.warn('Could not fetch company info:', e);
                }
            }

            renderJobs(filteredJobs);

        } catch (error) {
            console.error('Error fetching draft jobs:', error);
            showErrorState('Failed to load draft jobs. Please try again.');
        }
    }

    // Update company status alert
    function updateCompanyStatus() {
        const alert = document.getElementById('companyStatusAlert');
        const message = document.getElementById('verificationMessage');

        if (userCompany) {
            if (userCompany.is_verified) {
                alert.className = 'alert alert-success mx-4';
                message.textContent = 'Your company is verified! You can publish jobs immediately.';
            } else {
                alert.className = 'alert alert-warning mx-4';
                message.textContent = 'Your company is pending verification. You can create drafts but cannot publish until verified.';
            }
            alert.style.display = 'block';
        }
    }

    // Render draft jobs in table
    function renderJobs(jobsData) {
        if (!Array.isArray(jobsData) || jobsData.length === 0) {
            if (draftJobs.length === 0) {
                showEmptyState();
            } else {
                showNoResultsState();
            }
            return;
        }

        const tbody = document.getElementById('jobsBody');
        tbody.innerHTML = '';

        jobsData.forEach((job, index) => {
            const createdDate = new Date(job.created_at).toLocaleDateString();
            const updatedDate = new Date(job.updated_at).toLocaleDateString();

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
                                <p class="text-xs text-secondary mb-0">${job.job_type}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-secondary text-xs font-weight-bold">${job.job_category ? job.job_category.name : 'Uncategorized'}</span>
                    </td>
                    <td>
                        <span class="text-secondary text-xs font-weight-bold">${job.location}</span>
                    </td>
                    <td>
                        <span class="text-secondary text-xs font-weight-bold">${createdDate}</span>
                    </td>
                    <td>
                        <span class="text-secondary text-xs font-weight-bold">${updatedDate}</span>
                    </td>
                    <td class="align-middle">
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="viewJob(${job.id})" title="View Details">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                        <button class="btn btn-sm btn-outline-success me-1" onclick="editJobDirect(${job.id})" title="Edit Job">
                            <i class="fas fa-edit"></i> Edit Job
                        </button>
                        <button class="btn btn-sm btn-outline-info me-1" onclick="publishJobDirect(${job.id})" title="Publish" ${!userCompany?.is_verified ? 'disabled' : ''}>
                            <i class="fas fa-upload"></i> Publish
                        </button>
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

        filteredJobs = draftJobs.filter(job => {
            return !searchTerm ||
                job.title.toLowerCase().includes(searchTerm) ||
                job.location.toLowerCase().includes(searchTerm) ||
                (job.job_category && job.job_category.name.toLowerCase().includes(searchTerm));
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
        document.getElementById('sortJobs').value = 'date_desc';
        filteredJobs = [...draftJobs];
        renderJobs(filteredJobs);
    }

    // View job details
    function viewJob(jobId) {
        const job = draftJobs.find(j => j.id === jobId);
        if (!job) return;

        currentJob = job;

        const modalBody = document.getElementById('jobModalBody');
        modalBody.innerHTML = `
            <div class="row">
                <div class="col-md-8">
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Job Information</h6>
                    <p class="text-sm mb-2"><strong>Title:</strong> ${job.title}</p>
                    <p class="text-sm mb-2"><strong>Category:</strong> ${job.job_category ? job.job_category.name : 'Uncategorized'}</p>
                    <p class="text-sm mb-2"><strong>Location:</strong> ${job.location}</p>
                    <p class="text-sm mb-2"><strong>Job Type:</strong> ${job.job_type}</p>
                    <p class="text-sm mb-2"><strong>Salary Range:</strong> ${job.salary_min && job.salary_max ? `$${job.salary_min} - $${job.salary_max}` : 'Not specified'}</p>
                </div>
                <div class="col-md-4">
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Status & Info</h6>
                    <p class="text-sm mb-2"><strong>Status:</strong>
                        <span class="badge bg-warning">Draft</span>
                    </p>
                    <p class="text-sm mb-2"><strong>Featured:</strong>
                        <span class="badge ${job.is_featured ? 'bg-info' : 'bg-secondary'}">${job.is_featured ? 'Yes' : 'No'}</span>
                    </p>
                    <p class="text-sm mb-2"><strong>Created:</strong> ${new Date(job.created_at).toLocaleDateString()}</p>
                    <p class="text-sm mb-2"><strong>Modified:</strong> ${new Date(job.updated_at).toLocaleDateString()}</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder">Description</h6>
                    <div class="job-description" style="white-space: pre-wrap; max-height: 200px; overflow-y: auto;">${job.description || 'No description provided'}</div>
                </div>
            </div>
        `;

        // Update modal buttons based on company verification
        const publishBtn = document.getElementById('publishJobBtn');
        if (!userCompany?.is_verified) {
            publishBtn.disabled = true;
            publishBtn.title = 'Company must be verified to publish jobs';
        } else {
            publishBtn.disabled = false;
            publishBtn.title = 'Publish this job';
        }

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('jobModal'));
        modal.show();
    }

    // Edit job (from modal)
    function editJob() {
        if (currentJob) {
            window.location.href = `/jobs/${currentJob.id}/edit`;
        }
    }

    // Edit job (direct)
    function editJobDirect(jobId) {
        window.location.href = `/jobs/${jobId}/edit`;
    }

    // Publish job (from modal)
    function publishJob() {
        if (currentJob) {
            publishJobDirect(currentJob.id);
        }
    }

    // Publish job (direct)
    async function publishJobDirect(jobId) {
        if (!userCompany?.is_verified) {
            alert('Your company needs to be verified before you can publish jobs.');
            return;
        }

        if (!confirm('Are you sure you want to publish this job?')) return;
        await updateJobStatus(jobId, 'publish');
    }

    // Delete job
    async function deleteJob(jobId) {
        if (!confirm('Are you sure you want to delete this draft job? This action cannot be undone.')) return;
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

            // Remove job from local arrays (either deleted or published)
            draftJobs = draftJobs.filter(j => j.id !== jobId);
            filteredJobs = filteredJobs.filter(j => j.id !== jobId);

            // Re-render the table
            renderJobs(filteredJobs);

            // Close modal if open
            const modal = bootstrap.Modal.getInstance(document.getElementById('jobModal'));
            if (modal) {
                modal.hide();
            }

            alert(result.message || `Job ${action}ed successfully!`);

        } catch (error) {
            console.error(`Error ${action}ing job:`, error);
            alert(`Failed to ${action} job. Please try again.`);
        }
    }

    // Load draft jobs when page loads
    document.addEventListener('DOMContentLoaded', fetchDraftJobs);
    </script>
@endsection
