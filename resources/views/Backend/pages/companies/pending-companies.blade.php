@extends('backend.layouts.master')
@section('content')
    <div class="col-12 mt-5">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <div>
                    <h6>Pending Company Verifications</h6>
                    <p class="text-sm mb-0">Companies waiting for verification approval</p>
                </div>
                <div>
                    <a href="{{ route('getAllCompanies') }}" class="btn btn-info btn-sm me-2">
                        <i class="fas fa-list me-2"></i>All Companies
                    </a>
                    <a href="{{ route('getVerifiedCompanies') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-check-circle me-2"></i>Verified Companies
                    </a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <!-- Loading State -->
                <div id="loadingState" class="text-center p-4">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading pending companies...</p>
                </div>

                <!-- Companies Table -->
                <div id="companiesTable" class="table-responsive p-0" style="display: none;">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Company</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Owner</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Document</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Submitted</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="companiesBody">
                            <!-- Pending companies will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="text-center p-4" style="display: none;">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 class="text-muted">All caught up!</h5>
                    <p class="text-muted">No companies are pending verification at the moment.</p>
                    <a href="{{ route('getAllCompanies') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-list me-2"></i>View All Companies
                    </a>
                </div>

                <!-- Error State -->
                <div id="errorState" class="text-center p-4" style="display: none;">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="text-danger" id="errorMessage">Failed to load pending companies.</p>
                    <button class="btn btn-primary btn-sm" onclick="fetchPendingCompanies()">
                        <i class="fas fa-retry me-2"></i>Retry
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Verification Modal -->
    <div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verificationModalLabel">Company Verification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="verificationModalBody">
                    <!-- Company verification details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger me-2" id="rejectBtn" onclick="rejectCompany()">
                        <i class="fas fa-times me-2"></i>Reject
                    </button>
                    <button type="button" class="btn btn-success" id="approveBtn" onclick="approveCompany()">
                        <i class="fas fa-check me-2"></i>Approve & Verify
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let pendingCompanies = [];
    let currentCompany = null;

    // Show different states
    function showLoadingState() {
        document.getElementById('loadingState').style.display = 'block';
        document.getElementById('companiesTable').style.display = 'none';
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('errorState').style.display = 'none';
    }

    function showCompaniesTable() {
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('companiesTable').style.display = 'block';
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('errorState').style.display = 'none';
    }

    function showEmptyState() {
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('companiesTable').style.display = 'none';
        document.getElementById('emptyState').style.display = 'block';
        document.getElementById('errorState').style.display = 'none';
    }

    function showErrorState(message) {
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('companiesTable').style.display = 'none';
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('errorState').style.display = 'block';
        document.getElementById('errorMessage').textContent = message;
    }

    // Fetch pending companies from API
    async function fetchPendingCompanies() {
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
            const response = await fetch('http://127.0.0.1:8000/api/companies', {
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
                    showErrorState('Access denied. Only super admins can view companies.');
                    return;
                }
                throw new Error(`Failed to fetch companies: ${response.status} ${response.statusText}`);
            }

            const allCompanies = await response.json();
            // Filter only pending companies
            pendingCompanies = allCompanies.filter(company => !company.is_verified);
            renderPendingCompanies(pendingCompanies);

        } catch (error) {
            console.error('Error fetching pending companies:', error);
            showErrorState('Failed to load pending companies. Please try again.');
        }
    }

    // Render pending companies in table
    function renderPendingCompanies(companiesData) {
        if (!Array.isArray(companiesData) || companiesData.length === 0) {
            showEmptyState();
            return;
        }

        const tbody = document.getElementById('companiesBody');
        tbody.innerHTML = '';

        companiesData.forEach((company, index) => {
            const documentStatus = company.verification_document
                ? '<span class="badge badge-sm bg-gradient-info">Document Uploaded</span>'
                : '<span class="badge badge-sm bg-gradient-warning">No Document</span>';

            const submittedDate = new Date(company.created_at).toLocaleDateString();
            const daysSince = Math.floor((new Date() - new Date(company.created_at)) / (1000 * 60 * 60 * 24));

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
                                <h6 class="mb-0 text-sm">${company.name}</h6>
                                <p class="text-xs text-secondary mb-0">${company.description ? company.description.substring(0, 50) + '...' : 'No description'}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">${company.user ? company.user.name : 'N/A'}</h6>
                                <p class="text-xs text-secondary mb-0">${company.user ? company.user.email : ''}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center">
                                ${documentStatus}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="text-secondary text-xs font-weight-bold">${submittedDate}</span>
                            <span class="text-xs ${daysSince > 7 ? 'text-danger' : daysSince > 3 ? 'text-warning' : 'text-success'}">${daysSince} days ago</span>
                        </div>
                    </td>
                    <td class="align-middle">
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="reviewCompany(${company.id})" title="Review & Verify">
                            <i class="fas fa-search"></i> Review
                        </button>
                        <button class="btn btn-sm btn-outline-success me-1" onclick="quickApprove(${company.id})" title="Quick Approve">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });

        showCompaniesTable();
    }

    // Review company for verification
    async function reviewCompany(companyId) {
        const company = pendingCompanies.find(c => c.id === companyId);
        if (!company) return;

        currentCompany = company;

        const modalBody = document.getElementById('verificationModalBody');
        modalBody.innerHTML = `
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Company Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-sm mb-2"><strong>Company Name:</strong> ${company.name}</p>
                                    <p class="text-sm mb-2"><strong>Slug:</strong> ${company.slug}</p>
                                    <p class="text-sm mb-2"><strong>Website:</strong> ${company.website || 'Not provided'}</p>
                                    <p class="text-sm mb-2"><strong>Address:</strong> ${company.address || 'Not provided'}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-sm mb-2"><strong>Owner Name:</strong> ${company.user ? company.user.name : 'N/A'}</p>
                                    <p class="text-sm mb-2"><strong>Owner Email:</strong> ${company.user ? company.user.email : 'N/A'}</p>
                                    <p class="text-sm mb-2"><strong>Submitted:</strong> ${new Date(company.created_at).toLocaleDateString()}</p>
                                    <p class="text-sm mb-2"><strong>Document:</strong> ${company.verification_document || 'No document uploaded'}</p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <p class="text-sm mb-2"><strong>Description:</strong></p>
                                    <p class="text-sm">${company.description || 'No description provided'}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
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
                                    Website is accessible (if provided)
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check4">
                                <label class="form-check-label text-sm" for="check4">
                                    Documentation is adequate
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="check5">
                                <label class="form-check-label text-sm" for="check5">
                                    No duplicate entries found
                                </label>
                            </div>

                            <hr class="my-3">

                            <div class="form-group">
                                <label class="form-control-label text-sm">Admin Notes (Optional)</label>
                                <textarea class="form-control" id="adminNotes" rows="3" placeholder="Add any notes about this verification..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('verificationModal'));
        modal.show();
    }

    // Quick approve company
    async function quickApprove(companyId) {
        if (!confirm('Are you sure you want to approve and verify this company?')) return;
        await updateCompanyVerification(companyId, true);
    }

    // Approve company from modal
    async function approveCompany() {
        if (!currentCompany) return;

        const notes = document.getElementById('adminNotes').value;
        await updateCompanyVerification(currentCompany.id, true, notes);

        bootstrap.Modal.getInstance(document.getElementById('verificationModal')).hide();
    }

    // Reject company
    async function rejectCompany() {
        if (!currentCompany) return;

        const notes = document.getElementById('adminNotes').value;
        if (!notes.trim()) {
            alert('Please provide a reason for rejection in the admin notes.');
            return;
        }

        if (!confirm('Are you sure you want to reject this company verification?')) return;

        // For now, we'll just keep it unverified but could add a rejected status later
        alert('Company verification has been noted as rejected. Notes: ' + notes);
        bootstrap.Modal.getInstance(document.getElementById('verificationModal')).hide();
    }

    // Update company verification status
    async function updateCompanyVerification(companyId, isVerified, notes = '') {
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

            // Remove the company from pending list if verified
            if (isVerified) {
                pendingCompanies = pendingCompanies.filter(c => c.id !== companyId);
                renderPendingCompanies(pendingCompanies);
            }

            alert(`Company has been ${isVerified ? 'approved and verified' : 'updated'} successfully!`);

        } catch (error) {
            console.error('Error updating company verification:', error);
            alert('Failed to update company verification status. Please try again.');
        }
    }

    // Load pending companies when page loads
    document.addEventListener('DOMContentLoaded', fetchPendingCompanies);
    </script>
@endsection
