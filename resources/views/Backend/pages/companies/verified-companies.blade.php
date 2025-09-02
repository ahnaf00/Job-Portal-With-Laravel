@extends('backend.layouts.master')
@section('content')
    <div class="col-12 mt-5">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <div>
                    <h6>Verified Companies</h6>
                    <p class="text-sm mb-0">Companies that have been successfully verified</p>
                </div>
                <div>
                    <a href="{{ route('getAllCompanies') }}" class="btn btn-info btn-sm me-2">
                        <i class="fas fa-list me-2"></i>All Companies
                    </a>
                    <a href="{{ route('getPendingCompanies') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-clock me-2"></i>Pending Verification
                    </a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <!-- Search and Filter -->
                <div class="row px-4 mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" id="searchCompanies" placeholder="Search companies..." onkeyup="filterCompanies()">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <select class="form-control" id="sortCompanies" onchange="sortCompanies()">
                                <option value="name_asc">Sort by Name (A-Z)</option>
                                <option value="name_desc">Sort by Name (Z-A)</option>
                                <option value="date_desc">Newest First</option>
                                <option value="date_asc">Oldest First</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="text-center p-4">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading verified companies...</p>
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
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Verified Date</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="companiesBody">
                            <!-- Verified companies will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="text-center p-4" style="display: none;">
                    <i class="fas fa-shield-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No verified companies yet</h5>
                    <p class="text-muted">Once companies are verified, they will appear here.</p>
                    <a href="{{ route('getPendingCompanies') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-clock me-2"></i>Review Pending Companies
                    </a>
                </div>

                <!-- No Results State -->
                <div id="noResultsState" class="text-center p-4" style="display: none;">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No companies found</h5>
                    <p class="text-muted">Try adjusting your search terms or filters.</p>
                    <button class="btn btn-primary btn-sm" onclick="clearFilters()">
                        <i class="fas fa-times me-2"></i>Clear Filters
                    </button>
                </div>

                <!-- Error State -->
                <div id="errorState" class="text-center p-4" style="display: none;">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="text-danger" id="errorMessage">Failed to load verified companies.</p>
                    <button class="btn btn-primary btn-sm" onclick="fetchVerifiedCompanies()">
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
                    <h5 class="modal-title" id="companyModalLabel">Verified Company Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="companyModalBody">
                    <!-- Company details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning" id="revokeBtn" onclick="revokeVerification()">
                        <i class="fas fa-times me-2"></i>Revoke Verification
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    let verifiedCompanies = [];
    let filteredCompanies = [];
    let currentCompany = null;

    // Show different states
    function showLoadingState() {
        document.getElementById('loadingState').style.display = 'block';
        document.getElementById('companiesTable').style.display = 'none';
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('noResultsState').style.display = 'none';
        document.getElementById('errorState').style.display = 'none';
    }

    function showCompaniesTable() {
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('companiesTable').style.display = 'block';
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('noResultsState').style.display = 'none';
        document.getElementById('errorState').style.display = 'none';
    }

    function showEmptyState() {
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('companiesTable').style.display = 'none';
        document.getElementById('emptyState').style.display = 'block';
        document.getElementById('noResultsState').style.display = 'none';
        document.getElementById('errorState').style.display = 'none';
    }

    function showNoResultsState() {
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('companiesTable').style.display = 'none';
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('noResultsState').style.display = 'block';
        document.getElementById('errorState').style.display = 'none';
    }

    function showErrorState(message) {
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('companiesTable').style.display = 'none';
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('noResultsState').style.display = 'none';
        document.getElementById('errorState').style.display = 'block';
        document.getElementById('errorMessage').textContent = message;
    }

    // Fetch verified companies from API
    async function fetchVerifiedCompanies() {
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
            // Filter only verified companies
            verifiedCompanies = allCompanies.filter(company => company.is_verified);
            filteredCompanies = [...verifiedCompanies];
            renderVerifiedCompanies(filteredCompanies);

        } catch (error) {
            console.error('Error fetching verified companies:', error);
            showErrorState('Failed to load verified companies. Please try again.');
        }
    }

    // Render verified companies in table
    function renderVerifiedCompanies(companiesData) {
        if (!Array.isArray(companiesData) || companiesData.length === 0) {
            if (verifiedCompanies.length === 0) {
                showEmptyState();
            } else {
                showNoResultsState();
            }
            return;
        }

        const tbody = document.getElementById('companiesBody');
        tbody.innerHTML = '';

        companiesData.forEach((company, index) => {
            const website = company.website
                ? `<a href="${company.website}" target="_blank" class="text-decoration-none">${company.website}</a>`
                : '<span class="text-muted">N/A</span>';

            const verifiedDate = new Date(company.updated_at).toLocaleDateString();

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
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm bg-gradient-success text-center me-2">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm">${company.name}</h6>
                                    <p class="text-xs text-secondary mb-0">${company.description ? company.description.substring(0, 50) + '...' : 'No description'}</p>
                                </div>
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
                        <span class="text-secondary text-xs font-weight-bold">${verifiedDate}</span>
                    </td>
                    <td class="align-middle">
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="viewCompany(${company.id})" title="View Details">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                        <button class="btn btn-sm btn-outline-warning" onclick="revokeQuick(${company.id})" title="Revoke Verification">
                            <i class="fas fa-times"></i> Revoke Verification
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });

        showCompaniesTable();
    }

    // Filter companies based on search
    function filterCompanies() {
        const searchTerm = document.getElementById('searchCompanies').value.toLowerCase();

        if (!searchTerm) {
            filteredCompanies = [...verifiedCompanies];
        } else {
            filteredCompanies = verifiedCompanies.filter(company =>
                company.name.toLowerCase().includes(searchTerm) ||
                (company.user && company.user.name.toLowerCase().includes(searchTerm)) ||
                (company.user && company.user.email.toLowerCase().includes(searchTerm)) ||
                (company.website && company.website.toLowerCase().includes(searchTerm))
            );
        }

        renderVerifiedCompanies(filteredCompanies);
    }

    // Sort companies
    function sortCompanies() {
        const sortValue = document.getElementById('sortCompanies').value;

        switch(sortValue) {
            case 'name_asc':
                filteredCompanies.sort((a, b) => a.name.localeCompare(b.name));
                break;
            case 'name_desc':
                filteredCompanies.sort((a, b) => b.name.localeCompare(a.name));
                break;
            case 'date_desc':
                filteredCompanies.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
                break;
            case 'date_asc':
                filteredCompanies.sort((a, b) => new Date(a.updated_at) - new Date(b.updated_at));
                break;
        }

        renderVerifiedCompanies(filteredCompanies);
    }

    // Clear filters
    function clearFilters() {
        document.getElementById('searchCompanies').value = '';
        document.getElementById('sortCompanies').value = 'name_asc';
        filteredCompanies = [...verifiedCompanies];
        renderVerifiedCompanies(filteredCompanies);
    }

    // View company details
    async function viewCompany(companyId) {
        const company = verifiedCompanies.find(c => c.id === companyId);
        if (!company) return;

        currentCompany = company;

        const modalBody = document.getElementById('companyModalBody');
        modalBody.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Company Information</h6>
                    <div class="mb-3">
                        <span class="badge bg-gradient-success mb-2">VERIFIED</span>
                    </div>
                    <p class="text-sm mb-2"><strong>Name:</strong> ${company.name}</p>
                    <p class="text-sm mb-2"><strong>Slug:</strong> ${company.slug}</p>
                    <p class="text-sm mb-2"><strong>Website:</strong> ${company.website ? `<a href="${company.website}" target="_blank">${company.website}</a>` : 'N/A'}</p>
                    <p class="text-sm mb-2"><strong>Address:</strong> ${company.address || 'N/A'}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Verification Details</h6>
                    <p class="text-sm mb-2"><strong>Owner:</strong> ${company.user ? company.user.name : 'N/A'}</p>
                    <p class="text-sm mb-2"><strong>Owner Email:</strong> ${company.user ? company.user.email : 'N/A'}</p>
                    <p class="text-sm mb-2"><strong>Verified Date:</strong> ${new Date(company.updated_at).toLocaleDateString()}</p>
                    <p class="text-sm mb-2"><strong>Document:</strong> ${company.verification_document || 'No document on file'}</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder">Description</h6>
                    <p class="text-sm">${company.description || 'No description provided'}</p>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        <strong>Note:</strong> This company has been verified and can post jobs. Revoking verification will prevent them from posting new jobs.
                    </div>
                </div>
            </div>
        `;

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('companyModal'));
        modal.show();
    }

    // Quick revoke verification
    async function revokeQuick(companyId) {
        if (!confirm('Are you sure you want to revoke verification for this company? This will prevent them from posting new jobs.')) return;
        await updateCompanyVerification(companyId, false);
    }

    // Revoke verification from modal
    async function revokeVerification() {
        if (!currentCompany) return;

        if (!confirm('Are you sure you want to revoke verification for this company? This action will prevent them from posting new jobs.')) return;

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

            // Remove the company from verified list if unverified
            if (!isVerified) {
                verifiedCompanies = verifiedCompanies.filter(c => c.id !== companyId);
                filteredCompanies = filteredCompanies.filter(c => c.id !== companyId);
                renderVerifiedCompanies(filteredCompanies);
            }

            alert(`Company verification has been ${isVerified ? 'restored' : 'revoked'} successfully!`);

        } catch (error) {
            console.error('Error updating company verification:', error);
            alert('Failed to update company verification status. Please try again.');
        }
    }

    // Load verified companies when page loads
    document.addEventListener('DOMContentLoaded', fetchVerifiedCompanies);
    </script>
@endsection
