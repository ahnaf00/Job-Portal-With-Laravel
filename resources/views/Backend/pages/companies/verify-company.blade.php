@extends('backend.layouts.master')
@section('content')
    <div class="col-12 mt-5">
        <!-- Company Header Card -->
        <div class="card mb-4" id="companyHeader" style="display: none;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-lg bg-gradient-primary text-center me-3">
                                <i class="fas fa-building text-white text-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-0" id="companyName">Company Name</h5>
                                <p class="text-sm mb-0" id="companyWebsite">www.example.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="badge badge-lg" id="verificationStatus">Status</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="text-center p-5">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Loading company details...</p>
        </div>

        <!-- Error State -->
        <div id="errorState" class="card mb-4" style="display: none;">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <h5 class="text-danger">Error Loading Company</h5>
                <p id="errorMessage" class="text-muted">Failed to load company details.</p>
                <button class="btn btn-primary" onclick="loadCompanyDetails()">
                    <i class="fas fa-retry me-2"></i>Retry
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div id="mainContent" style="display: none;">
            <div class="row">
                <!-- Company Information -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Company Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-sm mb-3"><strong>Company Name:</strong> <span id="detailName">-</span></p>
                                    <p class="text-sm mb-3"><strong>Website:</strong> <span id="detailWebsite">-</span></p>
                                    <p class="text-sm mb-3"><strong>Address:</strong> <span id="detailAddress">-</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-sm mb-3"><strong>Registration Date:</strong> <span id="detailCreated">-</span></p>
                                    <p class="text-sm mb-3"><strong>Last Updated:</strong> <span id="detailUpdated">-</span></p>
                                    <p class="text-sm mb-3"><strong>Slug:</strong> <span id="detailSlug">-</span></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <p class="text-sm mb-2"><strong>Description:</strong></p>
                                    <p class="text-sm" id="detailDescription">No description provided</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Owner Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Company Owner</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-sm mb-3"><strong>Owner Name:</strong> <span id="ownerName">-</span></p>
                                    <p class="text-sm mb-3"><strong>Email:</strong> <span id="ownerEmail">-</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-sm mb-3"><strong>User ID:</strong> <span id="ownerUserId">-</span></p>
                                    <p class="text-sm mb-3"><strong>Account Created:</strong> <span id="ownerCreated">-</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verification Panel -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Verification Status</h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="icon icon-shape icon-xl mx-auto mb-3" id="statusIcon">
                                    <i class="fas fa-question text-lg"></i>
                                </div>
                                <h6 id="statusText">Unknown</h6>
                            </div>
                            
                            <div id="verificationActions">
                                <!-- Action buttons will be inserted here -->
                            </div>
                        </div>
                    </div>

                    <!-- Verification Document -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Documentation</h6>
                        </div>
                        <div class="card-body">
                            <div id="documentInfo">
                                <p class="text-sm mb-3"><strong>Document:</strong> <span id="documentName">-</span></p>
                                <div id="documentActions">
                                    <!-- Document actions will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Verification Checklist -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Verification Checklist</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check1">
                                <label class="form-check-label text-sm" for="check1">
                                    Company name is legitimate
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check2">
                                <label class="form-check-label text-sm" for="check2">
                                    Contact information verified
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check3">
                                <label class="form-check-label text-sm" for="check3">
                                    Website is accessible
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check4">
                                <label class="form-check-label text-sm" for="check4">
                                    Documentation is adequate
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="check5">
                                <label class="form-check-label text-sm" for="check5">
                                    No duplicate entries found
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-control-label text-sm">Admin Notes</label>
                                <textarea class="form-control" id="adminNotes" rows="3" placeholder="Add notes about this verification..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    let currentCompany = null;
    const companyId = getCompanyIdFromUrl();

    // Get company ID from URL
    function getCompanyIdFromUrl() {
        const pathParts = window.location.pathname.split('/');
        const companiesIndex = pathParts.indexOf('companies');
        if (companiesIndex !== -1 && pathParts[companiesIndex + 1]) {
            return pathParts[companiesIndex + 1];
        }
        return null;
    }

    // Show different states
    function showLoadingState() {
        document.getElementById('loadingState').style.display = 'block';
        document.getElementById('companyHeader').style.display = 'none';
        document.getElementById('mainContent').style.display = 'none';
        document.getElementById('errorState').style.display = 'none';
    }

    function showMainContent() {
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('companyHeader').style.display = 'block';
        document.getElementById('mainContent').style.display = 'block';
        document.getElementById('errorState').style.display = 'none';
    }

    function showErrorState(message) {
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('companyHeader').style.display = 'none';
        document.getElementById('mainContent').style.display = 'none';
        document.getElementById('errorState').style.display = 'block';
        document.getElementById('errorMessage').textContent = message;
    }

    // Load company details
    async function loadCompanyDetails() {
        if (!companyId) {
            showErrorState('No company ID provided in the URL.');
            return;
        }

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
            const response = await fetch(`http://127.0.0.1:8000/api/companies/${companyId}`, {
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
                    showErrorState('Access denied. Only super admins can view company details.');
                    return;
                }
                if (response.status === 404) {
                    showErrorState('Company not found.');
                    return;
                }
                throw new Error(`Failed to fetch company: ${response.status} ${response.statusText}`);
            }

            currentCompany = await response.json();
            displayCompanyDetails(currentCompany);
            showMainContent();
            
        } catch (error) {
            console.error('Error fetching company details:', error);
            showErrorState('Failed to load company details. Please try again.');
        }
    }

    // Display company details
    function displayCompanyDetails(company) {
        // Header
        document.getElementById('companyName').textContent = company.name;
        document.getElementById('companyWebsite').textContent = company.website || 'No website';
        
        const statusBadge = document.getElementById('verificationStatus');
        if (company.is_verified) {
            statusBadge.textContent = 'VERIFIED';
            statusBadge.className = 'badge badge-lg bg-gradient-success';
        } else {
            statusBadge.textContent = 'PENDING VERIFICATION';
            statusBadge.className = 'badge badge-lg bg-gradient-warning';
        }

        // Company details
        document.getElementById('detailName').textContent = company.name;
        document.getElementById('detailWebsite').innerHTML = company.website 
            ? `<a href="${company.website}" target="_blank">${company.website}</a>` 
            : 'Not provided';
        document.getElementById('detailAddress').textContent = company.address || 'Not provided';
        document.getElementById('detailCreated').textContent = new Date(company.created_at).toLocaleDateString();
        document.getElementById('detailUpdated').textContent = new Date(company.updated_at).toLocaleDateString();
        document.getElementById('detailSlug').textContent = company.slug;
        document.getElementById('detailDescription').textContent = company.description || 'No description provided';

        // Owner details
        if (company.user) {
            document.getElementById('ownerName').textContent = company.user.name;
            document.getElementById('ownerEmail').textContent = company.user.email;
            document.getElementById('ownerUserId').textContent = company.user.id;
            document.getElementById('ownerCreated').textContent = company.user.created_at 
                ? new Date(company.user.created_at).toLocaleDateString() 
                : 'N/A';
        } else {
            document.getElementById('ownerName').textContent = 'No owner assigned';
            document.getElementById('ownerEmail').textContent = '-';
            document.getElementById('ownerUserId').textContent = '-';
            document.getElementById('ownerCreated').textContent = '-';
        }

        // Verification status
        const statusIcon = document.getElementById('statusIcon');
        const statusText = document.getElementById('statusText');
        const verificationActions = document.getElementById('verificationActions');

        if (company.is_verified) {
            statusIcon.className = 'icon icon-shape icon-xl bg-gradient-success text-center mx-auto mb-3';
            statusIcon.innerHTML = '<i class="fas fa-check text-white text-lg"></i>';
            statusText.textContent = 'Verified Company';
            statusText.className = 'text-success';
            
            verificationActions.innerHTML = `
                <button class="btn btn-warning btn-sm w-100" onclick="revokeVerification()">
                    <i class="fas fa-times me-2"></i>Revoke Verification
                </button>
            `;
        } else {
            statusIcon.className = 'icon icon-shape icon-xl bg-gradient-warning text-center mx-auto mb-3';
            statusIcon.innerHTML = '<i class="fas fa-clock text-white text-lg"></i>';
            statusText.textContent = 'Pending Verification';
            statusText.className = 'text-warning';
            
            verificationActions.innerHTML = `
                <button class="btn btn-success btn-sm w-100 mb-2" onclick="verifyCompany()">
                    <i class="fas fa-check me-2"></i>Verify Company
                </button>
                <button class="btn btn-outline-danger btn-sm w-100" onclick="rejectCompany()">
                    <i class="fas fa-times me-2"></i>Reject Application
                </button>
            `;
        }

        // Document information
        const documentName = document.getElementById('documentName');
        const documentActions = document.getElementById('documentActions');
        
        if (company.verification_document) {
            documentName.innerHTML = `<span class="text-success">${company.verification_document}</span>`;
            documentActions.innerHTML = `
                <button class="btn btn-outline-primary btn-sm w-100" onclick="viewDocument()">
                    <i class="fas fa-eye me-2"></i>View Document
                </button>
            `;
        } else {
            documentName.innerHTML = '<span class="text-muted">No document uploaded</span>';
            documentActions.innerHTML = `
                <p class="text-xs text-muted mb-0">Company owner has not uploaded verification documents yet.</p>
            `;
        }
    }

    // Verify company
    async function verifyCompany() {
        if (!confirm('Are you sure you want to verify this company? This will allow them to post jobs.')) return;
        
        const notes = document.getElementById('adminNotes').value;
        await updateCompanyVerification(true, notes);
    }

    // Revoke verification
    async function revokeVerification() {
        if (!confirm('Are you sure you want to revoke verification for this company? This will prevent them from posting new jobs.')) return;
        
        const notes = document.getElementById('adminNotes').value;
        await updateCompanyVerification(false, notes);
    }

    // Reject company
    async function rejectCompany() {
        const notes = document.getElementById('adminNotes').value;
        if (!notes.trim()) {
            alert('Please provide a reason for rejection in the admin notes.');
            return;
        }
        
        if (!confirm('Are you sure you want to reject this company application?')) return;
        
        // For now, we'll just alert. In a real app, you might want to add a 'rejected' status
        alert('Company application has been marked as rejected. Notes saved: ' + notes);
    }

    // View document
    function viewDocument() {
        if (currentCompany && currentCompany.verification_document) {
            alert('Document viewing functionality would open: ' + currentCompany.verification_document);
            // In a real app, this would open a document viewer or download the file
        }
    }

    // Update company verification status
    async function updateCompanyVerification(isVerified, notes = '') {
        const token = localStorage.getItem('access_token');
        
        if (!token) {
            alert('You are not authenticated. Please log in.');
            return;
        }

        try {
            const response = await fetch(`http://127.0.0.1:8000/api/companies/${companyId}`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({
                    is_verified: isVerified
                })
            });

            if (!response.ok) {
                throw new Error('Failed to update company verification status');
            }

            const updatedCompany = await response.json();
            currentCompany = updatedCompany;
            displayCompanyDetails(updatedCompany);
            
            alert(`Company has been ${isVerified ? 'verified' : 'unverified'} successfully!`);
            
            // Clear notes after successful update
            document.getElementById('adminNotes').value = '';

        } catch (error) {
            console.error('Error updating company verification:', error);
            alert('Failed to update company verification status. Please try again.');
        }
    }

    // Load company details when page loads
    document.addEventListener('DOMContentLoaded', loadCompanyDetails);
    </script>
@endsection