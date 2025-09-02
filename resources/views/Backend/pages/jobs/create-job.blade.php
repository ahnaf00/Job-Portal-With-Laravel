@extends('backend.layouts.master')
@section('content')
    <div class="col-12 mt-5">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Create New Job</h6>
                        <p class="text-sm mb-0">Post a new job opening for your company</p>
                    </div>
                    <div>
                        <a href="{{ route('getMyJobs') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to My Jobs
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Company Verification Alert -->
                <div id="companyStatusAlert" class="alert alert-warning" style="display: none;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <strong>Company Verification Required</strong>
                            <p class="mb-0">Your company needs to be verified before you can publish jobs. You can still create and save as draft.</p>
                        </div>
                    </div>
                </div>

                <!-- Job Creation Form -->
                <form id="jobForm">
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
                                            Publish immediately
                                        </label>
                                        <div class="form-text">Uncheck to save as draft</div>
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

                            <!-- Company Preview -->
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
                                    <button type="button" class="btn btn-primary w-100 mb-2" onclick="submitJob(true)">
                                        <i class="fas fa-check me-2"></i>Create & Publish Job
                                    </button>
                                    <button type="button" class="btn btn-outline-primary w-100 mb-2" onclick="submitJob(false)">
                                        <i class="fas fa-save me-2"></i>Save as Draft
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary w-100" onclick="previewJob()">
                                        <i class="fas fa-eye me-2"></i>Preview Job
                                    </button>
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
                    <button type="button" class="btn btn-primary" onclick="submitJob(true)">
                        <i class="fas fa-check me-2"></i>Create & Publish
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let jobCategories = [];
    let userCompany = null;
    let isSubmitting = false;

    // Load initial data
    async function loadInitialData() {
        const token = localStorage.getItem('access_token');
        
        if (!token) {
            alert('You are not authenticated. Please log in.');
            window.location.href = "{{ route('loginView') }}";
            return;
        }

        try {
            // Load categories and company info in parallel
            const [categoriesResponse, companyResponse] = await Promise.all([
                fetch('http://127.0.0.1:8000/api/job-categories', {
                    headers: { 'Accept': 'application/json' }
                }),
                fetch('http://127.0.0.1:8000/api/companies/my-company', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                })
            ]);

            // Load categories
            if (categoriesResponse.ok) {
                jobCategories = await categoriesResponse.json();
                loadCategories();
            }

            // Load company info
            if (companyResponse.ok) {
                userCompany = await companyResponse.json();
                displayCompanyInfo();
                
                // Check verification status and show appropriate alerts
                if (!userCompany.is_verified) {
                    document.getElementById('companyStatusAlert').style.display = 'block';
                    // Disable publish checkbox for unverified companies
                    document.getElementById('is_published').disabled = true;
                    // Update publish button text and behavior
                    updatePublishButtons(false);
                } else {
                    updatePublishButtons(true);
                }
            } else if (companyResponse.status === 403 || companyResponse.status === 404) {
                document.getElementById('companyPreview').innerHTML = `
                    <div class="alert alert-danger">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <div>
                                <strong>Company Profile Required</strong>
                                <p class="mb-0">You need to have a company profile to post jobs. Please contact an administrator.</p>
                            </div>
                        </div>
                    </div>
                `;
                // Disable all form elements
                document.getElementById('jobForm').style.display = 'none';
            }

        } catch (error) {
            console.error('Error loading initial data:', error);
            document.getElementById('companyPreview').innerHTML = `
                <div class="alert alert-danger">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <strong>Error Loading Data</strong>
                            <p class="mb-0">Failed to load company information. Please refresh the page.</p>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    // Load categories into dropdown
    function loadCategories() {
        const categorySelect = document.getElementById('category_id');
        jobCategories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });
    }

    // Update publish buttons based on verification status
    function updatePublishButtons(isVerified) {
        const publishBtn = document.querySelector('button[onclick="submitJob(true)"]');
        const draftBtn = document.querySelector('button[onclick="submitJob(false)"]');
        
        if (!isVerified) {
            publishBtn.disabled = true;
            publishBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Requires Verification';
            publishBtn.title = 'Company must be verified to publish jobs';
            publishBtn.classList.add('btn-secondary');
            publishBtn.classList.remove('btn-primary');
        } else {
            publishBtn.disabled = false;
            publishBtn.innerHTML = '<i class="fas fa-check me-2"></i>Create & Publish Job';
            publishBtn.title = '';
            publishBtn.classList.add('btn-primary');
            publishBtn.classList.remove('btn-secondary');
        }
    }

    // Display company information
    function displayCompanyInfo() {
        const companyPreview = document.getElementById('companyPreview');
        const verificationBadge = userCompany.is_verified 
            ? '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Verified</span>'
            : '<span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Pending Verification</span>';
            
        companyPreview.innerHTML = `
            <div class="text-center mb-3">
                <i class="fas fa-building fa-2x text-primary mb-2"></i>
                <h6 class="mb-1">${userCompany.name}</h6>
                ${verificationBadge}
            </div>
            <div class="small text-muted">
                <p class="mb-1"><i class="fas fa-globe me-2"></i>${userCompany.website || 'No website'}</p>
                <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>${userCompany.address || 'No address'}</p>
                <p class="mb-0"><i class="fas fa-calendar me-2"></i>Joined ${new Date(userCompany.created_at).toLocaleDateString()}</p>
            </div>
            ${!userCompany.is_verified ? `
                <div class="alert alert-warning alert-sm mt-3 mb-0">
                    <small><i class="fas fa-info-circle me-1"></i>Verification required to publish jobs</small>
                </div>
            ` : ''}
        `;
    }

    // Validate form
    function validateForm() {
        const requiredFields = ['title', 'category_id', 'job_type', 'location', 'description'];
        let isValid = true;
        let firstInvalidField = null;

        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                if (!firstInvalidField) {
                    firstInvalidField = field;
                }
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Validate salary range
        const salaryMin = document.getElementById('salary_min').value;
        const salaryMax = document.getElementById('salary_max').value;
        
        if (salaryMin && salaryMax && parseInt(salaryMin) > parseInt(salaryMax)) {
            alert('Minimum salary cannot be greater than maximum salary.');
            isValid = false;
        }

        if (!isValid && firstInvalidField) {
            firstInvalidField.focus();
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        return isValid;
    }

    // Preview job
    function previewJob() {
        if (!validateForm()) {
            return;
        }

        const formData = getFormData();
        const categoryName = jobCategories.find(cat => cat.id == formData.category_id)?.name || 'Uncategorized';
        
        const previewBody = document.getElementById('previewModalBody');
        previewBody.innerHTML = `
            <div class="job-preview">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h4 class="mb-1">${formData.title}</h4>
                        <p class="text-muted mb-2">${userCompany ? userCompany.name : 'Your Company'} • ${formData.location}</p>
                        <div class="d-flex flex-wrap">
                            <span class="badge bg-primary me-2 mb-1">${formData.job_type}</span>
                            <span class="badge bg-info me-2 mb-1">${categoryName}</span>
                            ${formData.is_featured ? '<span class="badge bg-warning mb-1">Featured</span>' : ''}
                        </div>
                    </div>
                    <div class="text-end">
                        ${formData.salary_min && formData.salary_max ? 
                            `<h6 class="text-success mb-0">$${parseInt(formData.salary_min).toLocaleString()} - $${parseInt(formData.salary_max).toLocaleString()}</h6>` : 
                            formData.salary_min ? 
                                `<h6 class="text-success mb-0">From $${parseInt(formData.salary_min).toLocaleString()}</h6>` : 
                                '<p class="text-muted mb-0">Salary not specified</p>'
                        }
                    </div>
                </div>
                
                <div class="border-top pt-3">
                    <h6>Job Description</h6>
                    <div class="job-description" style="white-space: pre-wrap;">${formData.description}</div>
                </div>
                
                <div class="border-top pt-3 mt-3">
                    <h6>Job Details</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Category:</strong> ${categoryName}</p>
                            <p class="mb-1"><strong>Type:</strong> ${formData.job_type}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Location:</strong> ${formData.location}</p>
                            <p class="mb-1"><strong>Status:</strong> ${formData.is_published ? 'Will be published' : 'Will be saved as draft'}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();
    }

    // Get form data
    function getFormData() {
        return {
            title: document.getElementById('title').value.trim(),
            category_id: document.getElementById('category_id').value,
            job_type: document.getElementById('job_type').value,
            location: document.getElementById('location').value.trim(),
            salary_min: document.getElementById('salary_min').value ? parseInt(document.getElementById('salary_min').value) : null,
            salary_max: document.getElementById('salary_max').value ? parseInt(document.getElementById('salary_max').value) : null,
            description: document.getElementById('description').value.trim(),
            is_featured: document.getElementById('is_featured').checked,
            is_published: document.getElementById('is_published').checked
        };
    }

    // Submit job
    async function submitJob(publish = null) {
        if (isSubmitting) return;
        
        if (!validateForm()) {
            return;
        }

        if (!userCompany) {
            alert('You need to have a company profile to post jobs.');
            return;
        }

        const token = localStorage.getItem('access_token');
        if (!token) {
            alert('You are not authenticated. Please log in.');
            window.location.href = "{{ route('loginView') }}";
            return;
        }

        // Override publish status if specified
        const formData = getFormData();
        if (publish !== null) {
            formData.is_published = publish;
        }

        // Check if trying to publish with unverified company
        if (formData.is_published && !userCompany.is_verified) {
            alert('Your company needs to be verified before you can publish jobs. The job will be saved as a draft instead.');
            formData.is_published = false;
        }

        isSubmitting = true;
        
        // Update button states
        const buttons = document.querySelectorAll('button[onclick*="submitJob"]');
        buttons.forEach(btn => {
            btn.disabled = true;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
            btn.setAttribute('data-original', originalText);
        });

        try {
            const response = await fetch('http://127.0.0.1:8000/api/jobs', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(formData)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || `Failed to create job: ${response.status}`);
            }

            const result = await response.json();
            
            // Show success message
            const status = result.is_published ? 'published' : 'saved as draft';
            alert(`Job ${status} successfully!`);
            
            // Redirect to my jobs
            window.location.href = "{{ route('getMyJobs') }}";

        } catch (error) {
            console.error('Error creating job:', error);
            alert('Failed to create job: ' + error.message);
        } finally {
            isSubmitting = false;
            
            // Restore button states
            buttons.forEach(btn => {
                btn.disabled = false;
                btn.innerHTML = btn.getAttribute('data-original');
                btn.removeAttribute('data-original');
            });
        }
    }

    // Load initial data when page loads
    document.addEventListener('DOMContentLoaded', loadInitialData);

    // Real-time form validation
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('is-invalid')) {
            if (e.target.value.trim()) {
                e.target.classList.remove('is-invalid');
            }
        }
    });
    </script>
@endsection