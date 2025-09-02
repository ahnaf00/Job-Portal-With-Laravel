@extends('backend.layouts.master')
@section('content')
    <div class="col-12 mt-5">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6>All Companies</h6>
                <div>
                    <a href="{{ route('getPendingCompanies') }}" class="btn btn-warning btn-sm me-2">
                        <i class="fas fa-clock me-2"></i>Pending Verification
                    </a>
                    <a href="{{ route('getVerifiedCompanies') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-check-circle me-2"></i>Verified Companies
                    </a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <!-- Loading State -->
                <div id="loadingState" class="text-center p-4">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading companies...</p>
                </div>

                <!-- Companies Table -->
                <div id="companiesTable" class="table-responsive p-0" style="display: none;">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Company</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Owner</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Website</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="companiesBody">
                            <!-- Companies will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="text-center p-4" style="display: none;">
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No companies found.</p>
                </div>

                <!-- Error State -->
                <div id="errorState" class="text-center p-4" style="display: none;">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="text-danger" id="errorMessage">Failed to load companies.</p>
                    <button class="btn btn-primary btn-sm" onclick="fetchCompanies()">
                        <i class="fas fa-retry me-2"></i>Retry
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Details Modal -->
    <div class="modal fade" id="companyModal" tabindex="-1" aria-labelledby="companyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="companyModalLabel">Company Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="companyModalBody">
                    <!-- Company details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="verifyBtn" onclick="verifyCompany()" style="display: none;">
                        <i class="fas fa-check me-2"></i>Verify Company
                    </button>
                    <button type="button" class="btn btn-warning" id="unverifyBtn" onclick="unverifyCompany()" style="display: none;">
                        <i class="fas fa-times me-2"></i>Unverify Company
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let companies = [];
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

    // Fetch companies from API
    async function fetchCompanies() {
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

            companies = await response.json();
            renderCompanies(companies);

        } catch (error) {
            console.error('Error fetching companies:', error);
            showErrorState('Failed to load companies. Please try again.');
        }
    }

    // Render companies in table
    function renderCompanies(companiesData) {
        if (!Array.isArray(companiesData) || companiesData.length === 0) {
            showEmptyState();
            return;
        }

        const tbody = document.getElementById('companiesBody');
        tbody.innerHTML = '';

        companiesData.forEach((company, index) => {
            const statusBadge = company.is_verified
                ? '<span class="badge badge-sm bg-gradient-success">Verified</span>'
                : '<span class="badge badge-sm bg-gradient-warning">Pending</span>';

            const website = company.website
                ? `<a href="${company.website}" target="_blank" class="text-decoration-none">${company.website}</a>`
                : '<span class="text-muted">N/A</span>';

            const createdDate = new Date(company.created_at).toLocaleDateString();

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
                        <p class="text-xs font-weight-bold mb-0">${website}</p>
                    </td>
                    <td>
                        <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center">
                                ${statusBadge}
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-secondary text-xs font-weight-bold">${createdDate}</span>
                    </td>
                    <td class="align-middle">
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="viewCompany(${company.id})" title="View Details">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                        ${!company.is_verified ?
                            `<button class="btn btn-sm btn-outline-success" onclick="quickVerify(${company.id})" title="Quick Verify">
                                <i class="fas fa-check"></i> Verify
                            </button>` :
                            `<button class="btn btn-sm btn-outline-warning" onclick="quickUnverify(${company.id})" title="Unverify">
                                <i class="fas fa-times"></i> Unverify
                            </button>`
                        }
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });

        showCompaniesTable();
    }

    // View company details
    async function viewCompany(companyId) {
        const company = companies.find(c => c.id === companyId);
        if (!company) return;

        currentCompany = company;

        const modalBody = document.getElementById('companyModalBody');
        modalBody.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder">Company Information</h6>
                    <p class="text-sm mb-2"><strong>Name:</strong> ${company.name}</p>
                    <p class="text-sm mb-2"><strong>Slug:</strong> ${company.slug}</p>
                    <p class="text-sm mb-2"><strong>Website:</strong> ${company.website || 'N/A'}</p>
                    <p class="text-sm mb-2"><strong>Address:</strong> ${company.address || 'N/A'}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder">Status & Owner</h6>
                    <p class="text-sm mb-2"><strong>Status:</strong>
                        <span class="badge ${company.is_verified ? 'bg-success' : 'bg-warning'}">${company.is_verified ? 'Verified' : 'Pending'}</span>
                    </p>
                    <p class="text-sm mb-2"><strong>Owner:</strong> ${company.user ? company.user.name : 'N/A'}</p>
                    <p class="text-sm mb-2"><strong>Owner Email:</strong> ${company.user ? company.user.email : 'N/A'}</p>
                    <p class="text-sm mb-2"><strong>Document:</strong> ${company.verification_document || 'No document uploaded'}</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder">Description</h6>
                    <p class="text-sm">${company.description || 'No description provided'}</p>
                </div>
            </div>
        `;

        // Show/hide action buttons
        const verifyBtn = document.getElementById('verifyBtn');
        const unverifyBtn = document.getElementById('unverifyBtn');

        if (company.is_verified) {
            verifyBtn.style.display = 'none';
            unverifyBtn.style.display = 'inline-block';
        } else {
            verifyBtn.style.display = 'inline-block';
            unverifyBtn.style.display = 'none';
        }

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('companyModal'));
        modal.show();
    }

    // Quick verify company
    async function quickVerify(companyId) {
        if (!confirm('Are you sure you want to verify this company?')) return;
        await updateCompanyVerification(companyId, true);
    }

    // Quick unverify company
    async function quickUnverify(companyId) {
        if (!confirm('Are you sure you want to unverify this company?')) return;
        await updateCompanyVerification(companyId, false);
    }

    // Verify company from modal
    async function verifyCompany() {
        if (!currentCompany) return;
        await updateCompanyVerification(currentCompany.id, true);
        bootstrap.Modal.getInstance(document.getElementById('companyModal')).hide();
    }

    // Unverify company from modal
    async function unverifyCompany() {
        if (!currentCompany) return;
        await updateCompanyVerification(currentCompany.id, false);
        bootstrap.Modal.getInstance(document.getElementById('companyModal')).hide();
    }

    // Update company verification status
    async function updateCompanyVerification(companyId, isVerified) {
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

            // Update the company in our local array
            const companyIndex = companies.findIndex(c => c.id === companyId);
            if (companyIndex !== -1) {
                companies[companyIndex] = updatedCompany;
            }

            // Re-render the table
            renderCompanies(companies);

            alert(`Company has been ${isVerified ? 'verified' : 'unverified'} successfully!`);

        } catch (error) {
            console.error('Error updating company verification:', error);
            alert('Failed to update company verification status. Please try again.');
        }
    }

    // Load companies when page loads
    document.addEventListener('DOMContentLoaded', fetchCompanies);
    </script>
@endsection
