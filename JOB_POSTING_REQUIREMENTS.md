# Job Posting Requirements

## Company Verification Requirement

**Only verified companies can publish jobs publicly.**

### How it Works:

1. **For Unverified Companies:**
   - Can create and save jobs as drafts
   - Cannot publish jobs publicly
   - Jobs remain private until company gets verified
   - Clear warnings displayed in the interface

2. **For Verified Companies:**
   - Can create and publish jobs immediately
   - Can toggle jobs between published and draft status
   - Full access to all job management features

### Backend Enforcement:

- **Job Creation (`POST /api/jobs`)**: Checks company verification before allowing `is_published: true`
- **Job Updates (`PUT /api/jobs/{id}`)**: Prevents setting `is_published: true` for unverified companies  
- **Job Publishing (`PATCH /api/jobs/{id}/publish`)**: Requires company verification
- **Seeder Data**: Creates realistic mix of verified/unverified companies

### Frontend Implementation:

- **Create Job Page**: Disables publish buttons for unverified companies
- **Edit Job Page**: Shows verification alerts and prevents publishing
- **My Jobs Page**: Displays company verification status
- **Clear messaging**: Users understand why they can't publish

### Job Management URLs:

- **All Jobs**: `/jobs-all`
- **Create Job**: `/jobs/create`
- **My Jobs**: `/jobs/my-jobs`
- **Draft Jobs**: `/jobs/drafts`
- **Edit Job**: `/jobs/{id}/edit` (uses path parameter for job ID)

### Error Messages:

- `"Company must be verified to post jobs"` - When trying to create published job
- `"Company must be verified to publish jobs"` - When trying to publish existing job
- Frontend alerts explain verification requirement clearly

### Company Verification Process:

Companies get verified by super admins through:
- `/companies/pending` - View unverified companies
- `/companies/{id}/verify` - Verify individual companies
- API endpoints: `PATCH /api/companies/{id}/verify`

This ensures quality control and prevents spam job postings while allowing legitimate companies to prepare their job listings before verification.