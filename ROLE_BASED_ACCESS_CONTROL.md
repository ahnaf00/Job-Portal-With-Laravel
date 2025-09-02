# Role-Based Dashboard Access Control System

## Overview

This system provides comprehensive role-based access control for the job portal dashboard, ensuring that users only see and can access features appropriate to their role.

## User Roles

- **super_admin**: Full access to everything including roles, permissions, company management, and all job functions
- **company**: Can access job management features (create, edit, manage their own jobs)
- **candidate**: Limited access (future: can manage their profile and applications)

## Features Implemented

### 1. API Endpoint for Current User Profile

**New Endpoint**: `GET /api/profile`
- Returns authenticated user data with roles and permissions
- Used by frontend to determine navigation visibility

### 2. Role-Based Sidebar Navigation

The sidebar automatically shows/hides sections based on user roles:

#### Super Admin Can See:
- Roles management section
- Permissions management section  
- RBAC Management section
- Company Management section
- Job Management section (all features)

#### Company Users Can See:
- Job Management section (limited to company features)
  - Create Job
  - My Jobs  
  - Draft Jobs
  - **Not visible**: All Jobs (super admin only)

#### Candidates Can See:
- Currently no dashboard sections (can be extended later)

### 3. Dynamic Navigation Loading

- Shows loading spinner while fetching user profile
- Automatically redirects to login if not authenticated
- Graceful error handling for network issues

### 4. Enhanced User Interface

#### Sidebar Features:
- Loading state with spinner
- Role-based section visibility
- Clean, organized navigation structure

#### Navbar Features:
- User profile dropdown with name, email, and roles
- Logout functionality
- Loading states

### 5. Utility Functions

Created `role-based-auth.js` with helper functions:

```javascript
// Role checking
hasRole(role)              // Check specific role
isSuperAdmin()             // Check if super admin
isCompany()                // Check if company user
isCandidate()              // Check if candidate

// Authentication
initRoleBasedAuth()        // Initialize auth system
requireAuthentication()   // Ensure user is logged in
requireRole(role)          // Require specific role

// User info
getUserDisplayName()       // Get user's display name
getUserEmail()             // Get user's email
getUserRolesString()       // Get roles as string
```

## Implementation Details

### Backend Changes

1. **AuthController.php**: Added `profile()` method to return current user with roles and permissions

2. **API Routes**: Added `GET /api/profile` route for authenticated users

### Frontend Changes

1. **Sidebar**: Complete rewrite with role-based visibility logic
2. **Navbar**: Enhanced with user profile display and logout
3. **Utility Script**: Comprehensive role-based authentication utilities
4. **Master Layout**: Included utility script in all pages

## Usage Examples

### For Page-Specific Role Checks

```javascript
// In your page JavaScript
document.addEventListener('DOMContentLoaded', async function() {
    await initRoleBasedAuth();
    
    // Show elements only to super admins
    if (isSuperAdmin()) {
        document.getElementById('admin-only-section').style.display = 'block';
    }
    
    // Require company role for certain actions
    if (!isCompany() && !isSuperAdmin()) {
        document.getElementById('company-features').style.display = 'none';
    }
});
```

### For HTML Role-Based Visibility

```html
<!-- Will be hidden for non-super-admins -->
<div data-role="super_admin">
    <button>Delete All Users</button>
</div>

<!-- Will be shown for company and super_admin -->
<div data-roles="company,super_admin">
    <button>Manage Jobs</button>
</div>
```

## Security Notes

1. **Frontend Only**: This role-based UI control is for user experience only
2. **Backend Protection**: All API endpoints still need proper middleware protection
3. **Token Security**: Authentication tokens are stored in localStorage
4. **Auto-Logout**: Expired tokens automatically redirect to login

## Future Enhancements

1. **Profile Management**: Add profile view/edit functionality
2. **Candidate Dashboard**: Implement candidate-specific features
3. **Role Permissions**: More granular permission-based controls
4. **Real-time Updates**: WebSocket integration for role changes
5. **Audit Logging**: Track role-based access attempts

## Testing the System

1. **Login as Super Admin**: See all navigation sections
2. **Login as Company**: See only job management section
3. **Login as Candidate**: See minimal navigation (can be enhanced)
4. **Invalid Token**: Automatically redirected to login
5. **Role Changes**: Navigation updates on page refresh

## Error Handling

- Network failures: Shows fallback UI
- Authentication failures: Redirects to login
- Authorization failures: Shows appropriate messages
- Invalid tokens: Automatically cleared and redirected

This system provides a robust foundation for role-based access control while maintaining good user experience and security practices.