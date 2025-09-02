@extends('backend.layouts.master')
@section('content')
    <div class="col-12 mt-5">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Edit Job</h6>
                        <p class="text-sm mb-0">Update job information and settings</p>
                    </div>
                    <div>
                        <a href="{{ route('getMyJobs') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to My Jobs
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Loading State -->
                <div id="loadingState" class="text-center py-5">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Loading job details...</p>
                </div>

                <!-- Company Verification Alert -->
                <div id="companyStatusAlert" class="alert alert-warning" style="display: none;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <strong>Company Verification Required</strong>
                            <p class="mb-0">Your company needs to be verified before you can publish jobs. You can still update and save as draft.</p>
                        </div>
                    </div>
                </div>

                <!-- Job Edit Form -->
                <form id="jobForm" style="display: none;">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-8">
                            <!-- Basic Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Job Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="title" class="form-label">Job Title *</label>
                                            <input type="text" class="form-control" id="title" name="title" required placeholder="e.g. Senior Software Engineer">
                                            <div class="form-text">Enter a clear and descriptive job title</div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="category_id" class="form-label">Job Category *</label>
                                            <select class="form-control" id="category_id" name="category_id" required>
                                                <option value="">Select a category</option>
                                                <!-- Categories will be loaded dynamically -->
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="job_type" class="form-label">Job Type *</label>
                                            <select class="form-control" id="job_type" name="job_type" required>
                                                <option value="">Select job type</option>
                                                <option value="full-time">Full-time</option>
                                                <option value="part-time">Part-time</option>
                                                <option value="remote">Remote</option>
                                                <option value="contract">Contract</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="location" class="form-label">Location *</label>
                                            <input type="text" class="form-control" id="location" name="location" required placeholder="e.g. New York, NY or Remote">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="salary_min" class="form-label">Minimum Salary</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" class="form-control" id="salary_min" name="salary_min" placeholder="50000">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="salary_max" class="form-label">Maximum Salary</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" class="form-control" id="salary_max" name="salary_max" placeholder="80000">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Job Description *</label>
                                        <textarea class="form-control" id="description" name="description" rows="8" required placeholder="Describe the role, responsibilities, requirements, and benefits..."></textarea>
                                        <div class="form-text">Provide a detailed description of the job including responsibilities, requirements, and benefits</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-4">
                            <!-- Job Settings -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Job Settings</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_published" name="is_published">
                                        <label class="form-check-label" for="is_published">
                                            Published
                                        </label>
                                        <div class="form-text">Toggle to publish/unpublish job</div>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                                        <label class="form-check-label" for="is_featured">
                                            Featured job
                                        </label>
                                        <div class="form-text">Featured jobs get more visibility</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Statistics -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Job Statistics</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border-end">
                                                <h5 class="mb-0" id="applicationsCount">0</h5>
                                                <p class="text-sm mb-0">Applications</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h5 class="mb-0" id="jobViews">N/A</h5>
                                            <p class="text-sm mb-0">Views</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Company Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Company Information</h6>
                                </div>
                                <div class="card-body" id="companyPreview">
                                    <div class="text-center">
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="text-sm mt-2">Loading company info...</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="card">
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary w-100 mb-2" onclick="updateJob()">
                                        <i class="fas fa-save me-2"></i>Update Job
                                    </button>
                                    <button type="button" class="btn btn-outline-primary w-100 mb-2" onclick="previewJob()">
                                        <i class="fas fa-eye me-2"></i>Preview Job
                                    </button>
                                    <div class="dropdown w-100">
                                        <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="quickActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-bolt me-2"></i>Quick Actions
                                        </button>
                                        <ul class="dropdown-menu w-100" aria-labelledby="quickActionsDropdown">
                                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="togglePublishStatus()">
                                                <i class="fas fa-toggle-on me-2"></i>Toggle Publish Status
                                            </a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="toggleFeaturedStatus()">
                                                <i class="fas fa-star me-2"></i>Toggle Featured Status
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteJob()">
                                                <i class="fas fa-trash me-2"></i>Delete Job
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Job Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Job Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="previewModalBody">
                    <!-- Job preview will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateJob()">
                        <i class="fas fa-save me-2"></i>Update Job
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let jobCategories = [];
    let userCompany = null;
    let currentJob = null;
    let jobId = null;
    let isSubmitting = false;

    // Get job ID from URL path
    function getJobIdFromUrl() {
        // Get job ID from path like /jobs/{id}/edit
        const pathSegments = window.location.pathname.split('/');
        const jobsIndex = pathSegments.indexOf('jobs');
        if (jobsIndex !== -1 && pathSegments.length > jobsIndex + 1) {
            return pathSegments[jobsIndex + 1];
        }
        
        // Fallback to URL parameters if path doesn't contain ID
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('id');
    }

    // Load initial data
    async function loadInitialData() {
        const token = localStorage.getItem('access_token');
        
        if (!token) {
            alert('You are not authenticated. Please log in.');
            window.location.href = "{{ route('loginView') }}";
            return;
        }

        jobId = getJobIdFromUrl();
        if (!jobId) {
            alert('No job ID provided. URL format should be /jobs/{id}/edit');
            window.location.href = "{{ route('getMyJobs') }}";
            return;
        }

        try {
            // Load job categories, company info, and job details in parallel
            const [categoriesResponse, companyResponse, jobResponse] = await Promise.all([
                fetch('http://127.0.0.1:8000/api/job-categories', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                }),
                fetch('http://127.0.0.1:8000/api/companies/my-company', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                }),
                fetch(`http://127.0.0.1:8000/api/jobs/${jobId}`, {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                })
            ]);

            if (!categoriesResponse.ok || !companyResponse.ok || !jobResponse.ok) {
                throw new Error('Failed to load required data');
            }

            jobCategories = await categoriesResponse.json();
            userCompany = await companyResponse.json();
            currentJob = await jobResponse.json();

            populateCategories();
            populateCompanyInfo();
            populateJobForm();
            
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('jobForm').style.display = 'block';

        } catch (error) {
            console.error('Error loading data:', error);
            alert('Failed to load job details. Please try again.');
            window.location.href = "{{ route('getMyJobs') }}";
        }
    }

    // Populate job categories dropdown
    function populateCategories() {
        const categorySelect = document.getElementById('category_id');
        categorySelect.innerHTML = '<option value="">Select a category</option>';
        
        jobCategories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });
    }

    // Populate company information
    function populateCompanyInfo() {
        const companyPreview = document.getElementById('companyPreview');
        const verificationAlert = document.getElementById('companyStatusAlert');
        
        if (userCompany) {
            companyPreview.innerHTML = `
                <div class="text-center mb-3">
                    <i class="fas fa-building fa-2x text-primary mb-2"></i>
                    <h6 class="mb-1">${userCompany.name || 'Company Name'}</h6>
                    <div class="badge ${userCompany.is_verified ? 'bg-success' : 'bg-warning'} mb-2">
                        <i class="fas ${userCompany.is_verified ? 'fa-check-circle' : 'fa-clock'} me-1"></i>
                        ${userCompany.is_verified ? 'Verified' : 'Pending Verification'}
                    </div>
                </div>
                <div class="small text-muted">
                    <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>${userCompany.address || 'No address provided'}</p>
                    ${userCompany.website ? `<p class="mb-1"><i class="fas fa-globe me-2"></i>${userCompany.website}</p>` : ''}
                </div>
            `;

            if (!userCompany.is_verified) {
                verificationAlert.style.display = 'block';
                // Disable publish checkbox if company not verified
                document.getElementById('is_published').disabled = true;
            }
        }
    }

    // Populate job form with current job data
    function populateJobForm() {
        if (!currentJob) return;

        document.getElementById('title').value = currentJob.title || '';
        document.getElementById('category_id').value = currentJob.category_id || '';
        document.getElementById('job_type').value = currentJob.job_type || '';
        document.getElementById('location').value = currentJob.location || '';
        document.getElementById('salary_min').value = currentJob.salary_min || '';
        document.getElementById('salary_max').value = currentJob.salary_max || '';
        document.getElementById('description').value = currentJob.description || '';
        document.getElementById('is_published').checked = currentJob.is_published || false;
        document.getElementById('is_featured').checked = currentJob.is_featured || false;

        // Update statistics
        document.getElementById('applicationsCount').textContent = currentJob.job_applications?.length || 0;
    }

    // Update job
    async function updateJob() {
        if (isSubmitting) return;
        
        const token = localStorage.getItem('access_token');
        if (!token) {
            alert('You are not authenticated. Please log in.');
            return;
        }

        // Validate form
        const form = document.getElementById('jobForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        isSubmitting = true;
        const submitButton = event.target;
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
        submitButton.disabled = true;

        try {
            const formData = {
                title: document.getElementById('title').value,
                category_id: document.getElementById('category_id').value,
                job_type: document.getElementById('job_type').value,
                location: document.getElementById('location').value,
                salary_min: document.getElementById('salary_min').value || null,
                salary_max: document.getElementById('salary_max').value || null,
                description: document.getElementById('description').value,
                is_published: document.getElementById('is_published').checked,
                is_featured: document.getElementById('is_featured').checked
            };

            // Check if trying to publish with unverified company
            if (formData.is_published && userCompany && !userCompany.is_verified) {
                alert('Your company needs to be verified before you can publish jobs. The job will remain as a draft.');
                formData.is_published = false;
                document.getElementById('is_published').checked = false;
            }

            const response = await fetch(`http://127.0.0.1:8000/api/jobs/${jobId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                currentJob = data;
                showMessage('Job updated successfully!', 'success');
                
                // Optionally redirect after a delay
                setTimeout(() => {
                    window.location.href = "{{ route('getMyJobs') }}";
                }, 1500);
            } else {
                throw new Error(data.error || 'Failed to update job');
            }

        } catch (error) {
            console.error('Error updating job:', error);
            showMessage('Failed to update job: ' + error.message, 'error');
        } finally {
            isSubmitting = false;
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }
    }

    // Toggle publish status
    async function togglePublishStatus() {
        if (!currentJob) return;

        const token = localStorage.getItem('access_token');
        const action = currentJob.is_published ? 'unpublish' : 'publish';
        
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/jobs/${jobId}/${action}`, {
                method: 'PATCH',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                currentJob.is_published = data.job.is_published;
                document.getElementById('is_published').checked = currentJob.is_published;
                showMessage(data.message, 'success');
            } else {
                throw new Error(data.error || 'Failed to toggle publish status');
            }
        } catch (error) {
            console.error('Error toggling publish status:', error);
            showMessage('Failed to toggle publish status: ' + error.message, 'error');
        }
    }

    // Toggle featured status
    async function toggleFeaturedStatus() {
        if (!currentJob) return;

        const token = localStorage.getItem('access_token');
        
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/jobs/${jobId}/toggle-featured`, {
                method: 'PATCH',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                currentJob.is_featured = data.job.is_featured;
                document.getElementById('is_featured').checked = currentJob.is_featured;
                showMessage(data.message, 'success');
            } else {
                throw new Error(data.error || 'Failed to toggle featured status');
            }
        } catch (error) {
            console.error('Error toggling featured status:', error);
            showMessage('Failed to toggle featured status: ' + error.message, 'error');
        }
    }

    // Delete job
    async function deleteJob() {
        if (!currentJob) return;

        if (!confirm('Are you sure you want to delete this job? This action cannot be undone.')) {
            return;
        }

        const token = localStorage.getItem('access_token');
        
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/jobs/${jobId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                showMessage('Job deleted successfully!', 'success');
                setTimeout(() => {
                    window.location.href = "{{ route('getMyJobs') }}";
                }, 1000);
            } else {
                throw new Error(data.error || 'Failed to delete job');
            }
        } catch (error) {
            console.error('Error deleting job:', error);
            showMessage('Failed to delete job: ' + error.message, 'error');
        }
    }

    // Preview job
    function previewJob() {
        const formData = {
            title: document.getElementById('title').value,
            job_type: document.getElementById('job_type').value,
            location: document.getElementById('location').value,
            salary_min: document.getElementById('salary_min').value,
            salary_max: document.getElementById('salary_max').value,
            description: document.getElementById('description').value,
            is_published: document.getElementById('is_published').checked,
            is_featured: document.getElementById('is_featured').checked
        };

        const categorySelect = document.getElementById('category_id');
        const selectedCategory = jobCategories.find(cat => cat.id == categorySelect.value);

        let salaryRange = '';
        if (formData.salary_min && formData.salary_max) {
            salaryRange = `$${parseInt(formData.salary_min).toLocaleString()} - $${parseInt(formData.salary_max).toLocaleString()}`;
        } else if (formData.salary_min) {
            salaryRange = `From $${parseInt(formData.salary_min).toLocaleString()}`;
        } else if (formData.salary_max) {
            salaryRange = `Up to $${parseInt(formData.salary_max).toLocaleString()}`;
        }

        const previewHTML = `
            <div class="job-preview">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h4 class="mb-1">${formData.title || 'Job Title'}</h4>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <span class="badge bg-primary">${selectedCategory?.name || 'Category'}</span>
                            <span class="badge bg-secondary">${formData.job_type || 'Job Type'}</span>
                            ${formData.is_featured ? '<span class="badge bg-warning">Featured</span>' : ''}
                            <span class="badge ${formData.is_published ? 'bg-success' : 'bg-secondary'}">
                                ${formData.is_published ? 'Published' : 'Draft'}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i><strong>Location:</strong> ${formData.location || 'Not specified'}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><i class="fas fa-dollar-sign me-2"></i><strong>Salary:</strong> ${salaryRange || 'Not specified'}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <h6>Company Information</h6>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-building me-2"></i>
                        <span>${userCompany?.name || 'Company Name'}</span>
                        ${userCompany?.is_verified ? '<i class="fas fa-check-circle text-success ms-2" title="Verified Company"></i>' : ''}
                    </div>
                </div>

                <div class="mb-3">
                    <h6>Job Description</h6>
                    <div class="job-description">
                        ${formData.description ? formData.description.replace(/\n/g, '<br>') : 'No description provided'}
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Applications: ${currentJob?.job_applications?.length || 0}
                    </small>
                    <small class="text-muted">
                        Created: ${currentJob ? new Date(currentJob.created_at).toLocaleDateString() : 'Just now'}
                    </small>
                </div>
            </div>
        `;

        document.getElementById('previewModalBody').innerHTML = previewHTML;
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    }

    // Utility function to show messages
    function showMessage(message, type = 'info') {
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
        alertDiv.style.position = 'fixed';
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '9999';
        alertDiv.style.minWidth = '300px';
        
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv && alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Load data when page loads
    document.addEventListener('DOMContentLoaded', loadInitialData);
    </script>
@endsection