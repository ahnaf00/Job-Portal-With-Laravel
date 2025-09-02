/**
 * Role-Based Authentication Utility
 * Provides consistent role checking and user authentication across the dashboard
 */

// Global user state
window.currentUser = null;
window.userProfile = null;

/**
 * Initialize role-based authentication
 * Call this on every dashboard page
 */
async function initRoleBasedAuth() {
    try {
        const profile = await getCurrentUserProfile();
        if (profile) {
            setCurrentUser(profile);
            return profile;
        } else {
            redirectToLogin();
            return null;
        }
    } catch (error) {
        console.error('Authentication initialization failed:', error);
        redirectToLogin();
        return null;
    }
}

/**
 * Get current user profile from API
 */
async function getCurrentUserProfile() {
    const token = localStorage.getItem('access_token');
    if (!token) return null;

    try {
        const response = await fetch('http://127.0.0.1:8000/api/profile', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            if (response.status === 401) {
                // Token expired
                localStorage.removeItem('access_token');
                return null;
            }
            throw new Error(`HTTP ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error('Error fetching user profile:', error);
        return null;
    }
}

/**
 * Set current user data globally
 */
function setCurrentUser(profile) {
    window.userProfile = profile;
    window.currentUser = {
        user: profile.user,
        roles: profile.roles || [],
        permissions: profile.permissions || [],
        isSuperAdmin: (profile.roles || []).includes('super_admin'),
        isCompany: (profile.roles || []).includes('company'),
        isCandidate: (profile.roles || []).includes('candidate')
    };

    // Dispatch event for other components (navbar, etc.)
    window.dispatchEvent(new CustomEvent('userProfileLoaded', {
        detail: window.currentUser
    }));
    
    // Also trigger navbar update directly if function exists
    if (typeof updateNavbarUserProfile === 'function') {
        updateNavbarUserProfile(window.currentUser);
    }
}

/**
 * Check if user has specific role
 */
function hasRole(role) {
    return window.currentUser && window.currentUser.roles.includes(role);
}

/**
 * Check if user has specific permission
 */
function hasPermission(permission) {
    return window.currentUser && window.currentUser.permissions.includes(permission);
}

/**
 * Check if user has any of the specified roles
 */
function hasAnyRole(roles) {
    if (!window.currentUser) return false;
    return roles.some(role => window.currentUser.roles.includes(role));
}

/**
 * Check if user is super admin
 */
function isSuperAdmin() {
    return window.currentUser && window.currentUser.isSuperAdmin;
}

/**
 * Check if user is company
 */
function isCompany() {
    return window.currentUser && window.currentUser.isCompany;
}

/**
 * Check if user is candidate
 */
function isCandidate() {
    return window.currentUser && window.currentUser.isCandidate;
}

/**
 * Redirect to login page
 */
function redirectToLogin() {
    window.location.href = "/login";
}

/**
 * Redirect to homepage
 */
function redirectToHomepage() {
    window.location.href = "/";
}

/**
 * Check authentication and redirect if needed
 */
function requireAuthentication() {
    const token = localStorage.getItem('access_token');
    if (!token) {
        redirectToLogin();
        return false;
    }
    return true;
}

/**
 * Require specific role or redirect
 */
function requireRole(role, redirectUrl = null) {
    if (!hasRole(role)) {
        if (redirectUrl) {
            window.location.href = redirectUrl;
        } else {
            showUnauthorizedMessage();
        }
        return false;
    }
    return true;
}

/**
 * Require super admin role
 */
function requireSuperAdmin(redirectUrl = null) {
    return requireRole('super_admin', redirectUrl);
}

/**
 * Require company role
 */
function requireCompany(redirectUrl = null) {
    return requireRole('company', redirectUrl);
}

/**
 * Show unauthorized message
 */
function showUnauthorizedMessage() {
    const message = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Access Denied!</strong> You don't have permission to access this section.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Try to find a container to show the message
    const container = document.querySelector('.container-fluid') || document.querySelector('.container') || document.body;
    container.insertAdjacentHTML('afterbegin', message);
}

/**
 * Hide elements based on role
 */
function hideElementsForRole(selector, allowedRoles) {
    const elements = document.querySelectorAll(selector);
    elements.forEach(element => {
        if (!hasAnyRole(allowedRoles)) {
            element.style.display = 'none';
        }
    });
}

/**
 * Show elements based on role
 */
function showElementsForRole(selector, allowedRoles) {
    const elements = document.querySelectorAll(selector);
    elements.forEach(element => {
        if (hasAnyRole(allowedRoles)) {
            element.style.display = '';
        } else {
            element.style.display = 'none';
        }
    });
}

/**
 * Disable elements based on role
 */
function disableElementsForRole(selector, allowedRoles) {
    const elements = document.querySelectorAll(selector);
    elements.forEach(element => {
        if (!hasAnyRole(allowedRoles)) {
            element.disabled = true;
            element.classList.add('disabled');
            if (element.tagName === 'A') {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    showUnauthorizedMessage();
                });
            }
        }
    });
}

/**
 * Setup role-based visibility for common elements
 */
function setupRoleBasedVisibility() {
    // Hide super admin only elements
    hideElementsForRole('[data-role="super_admin"]', ['super_admin']);
    
    // Hide company only elements
    hideElementsForRole('[data-role="company"]', ['company', 'super_admin']);
    
    // Hide candidate only elements
    hideElementsForRole('[data-role="candidate"]', ['candidate', 'super_admin']);
    
    // Setup elements with multiple allowed roles
    const roleElements = document.querySelectorAll('[data-roles]');
    roleElements.forEach(element => {
        const allowedRoles = element.getAttribute('data-roles').split(',').map(r => r.trim());
        if (!hasAnyRole(allowedRoles)) {
            element.style.display = 'none';
        }
    });
}

/**
 * Get user display name
 */
function getUserDisplayName() {
    return window.currentUser ? window.currentUser.user.name : 'User';
}

/**
 * Get user email
 */
function getUserEmail() {
    return window.currentUser ? window.currentUser.user.email : '';
}

/**
 * Get user roles as string
 */
function getUserRolesString() {
    return window.currentUser ? window.currentUser.roles.join(', ') : '';
}

/**
 * Logout user
 */
async function logoutUser() {
    const token = localStorage.getItem('access_token');
    if (token) {
        try {
            await fetch('http://127.0.0.1:8000/api/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
        } catch (error) {
            console.error('Logout error:', error);
        }
    }
    
    localStorage.removeItem('access_token');
    window.currentUser = null;
    window.userProfile = null;
    redirectToHomepage();
}

// Backward compatibility - expose functions globally
window.hasRole = hasRole;
window.hasPermission = hasPermission;
window.hasAnyRole = hasAnyRole;
window.isSuperAdmin = isSuperAdmin;
window.isCompany = isCompany;
window.isCandidate = isCandidate;
window.requireAuthentication = requireAuthentication;
window.requireRole = requireRole;
window.requireSuperAdmin = requireSuperAdmin;
window.requireCompany = requireCompany;
window.initRoleBasedAuth = initRoleBasedAuth;
window.getCurrentUserProfile = getCurrentUserProfile;
window.logoutUser = logoutUser;
window.getUserDisplayName = getUserDisplayName;
window.getUserEmail = getUserEmail;
window.getUserRolesString = getUserRolesString;
window.setupRoleBasedVisibility = setupRoleBasedVisibility;
window.redirectToHomepage = redirectToHomepage;

// Auto-initialize on DOM load if not already initialized
document.addEventListener('DOMContentLoaded', function() {
    if (!window.currentUser) {
        initRoleBasedAuth();
    }
});