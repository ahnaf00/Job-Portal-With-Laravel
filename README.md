# Job Portal REST API

A Laravel-based REST API for a job portal, allowing companies to post jobs, candidates to apply, and administrators to manage resources. Built with Laravel Sanctum for authentication and Spatie’s Laravel Permission for role-based access control.

## Table of Contents
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Database Schema](#database-schema)
- [Authentication](#authentication)
- [Roles and Permissions](#roles-and-permissions)
- [API Endpoints](#api-endpoints)
  - [Authentication](#authentication-1)
  - [Candidates](#candidates)
  - [Companies](#companies)
  - [Job Categories](#job-categories)
  - [Jobs](#jobs)
  - [Job Applications](#job-applications)
  - [Roles and Permissions](#roles-and-permissions-1)
- [Testing with Postman](#testing-with-postman)
- [Contributing](#contributing)
- [License](#license)

## Features
- User registration and login with Sanctum token-based authentication.
- Role-based access control (`super_admin`, `company`, `candidate`) using Spatie Laravel Permission.
- Companies can post and manage jobs (requires verification).
- Candidates can update their profiles and apply for jobs.
- Super admins can manage companies, job categories, and roles/permissions.
- Public access to published jobs and job categories.
- Secure API endpoints with middleware for authentication and authorization.

## Tech Stack
- **Framework**: Laravel 12
- **Authentication**: Laravel Sanctum
- **Authorization**: Spatie Laravel Permission
- **Database**: MySQL (or any Laravel-supported database)
- **PHP**: 8.1+
- **Composer**: For dependency management

## Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/ahnaf00/Job-Portal-With-Laravel.git
   cd job-portal-api
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Set Up Environment**
   - Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Configure `.env` with your database credentials and app settings:
     ```env
     APP_NAME=JobPortal
     APP_URL=http://localhost
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=job_portal
     DB_USERNAME=root
     DB_PASSWORD=
     ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate
   ```

6. **Seed Roles**
   Seed the necessary roles (`super_admin`, `company`, `candidate`) with the `api` guard:
   ```php
   use Spatie\Permission\Models\Role;

   Role::create(['name' => 'super_admin', 'guard_name' => 'api']);
   Role::create(['name' => 'company', 'guard_name' => 'api']);
   Role::create(['name' => 'candidate', 'guard_name' => 'api']);
   ```
   Run the seeder:
   ```bash
   php artisan db:seed
   ```

7. **Install Sanctum**
   Ensure Sanctum is set up:
   ```bash
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   php artisan migrate
   ```

8. **Start the Server**
   ```bash
   php artisan serve
   ```
   The API will be available at `http://localhost:8000/api`.

## Database Schema
The database consists of the following tables:

- **users**: Stores user data (`id`, `name`, `email`, `password`, `created_at`, `updated_at`).
- **companies**: Stores company profiles (`id`, `user_id`, `name`, `slug`, `address`, `website`, `description`, `is_verified`, `verification_document`, `verified_at`, `created_at`, `updated_at`).
- **candidates**: Stores candidate profiles (`id`, `user_id`, `first_name`, `last_name`, `phone`, `resume`, `skills`, `education`, `experience`, `created_at`, `updated_at`).
- **job_categories**: Stores job categories (`id`, `name`, `slug`, `created_at`, `updated_at`).
- **jobs**: Stores job postings (`id`, `company_id`, `category_id`, `title`, `slug`, `description`, `location`, `job_type`, `salary_min`, `salary_max`, `is_featured`, `is_published`, `created_at`, `updated_at`).
- **job_applications**: Stores job applications (`id`, `candidate_id`, `job_id`, `status`, `cover_letter`, `created_at`, `updated_at`).
- **roles** and **permissions**: Managed by Spatie for role-based access control.

## Authentication
The API uses Laravel Sanctum for token-based authentication. Users must register or log in to obtain a Bearer token, which is required for protected endpoints.

- **Register**: `POST /api/register`
- **Login**: `POST /api/login`
- **Logout**: `POST /api/logout` (requires `auth:sanctum`)

## Roles and Permissions
- **super_admin**: Can manage all resources (companies, job categories, roles, permissions).
- **company**: Can post/update jobs (if verified), manage own company profile, and view/update job applications for their jobs.
- **candidate**: Can update own profile and apply for jobs.
- Permissions are managed via Spatie’s Laravel Permission package with the `api` guard.

## API Endpoints

### Authentication

#### POST /api/register
Register a new user (company or candidate).
- **Request Body**:

  For a user/candidate
  ```json
  {
      "name": "John Doe",
      "email": "john@example.com",
      "password": "password123",
      "password_confirmation": "password123",
      "role": "candidate",
      "first_name": "John",
      "last_name": "Doe"
  }
  ```

- **Response (201)**:
  ```json
  {
      "message": "User registered successfully",
      "access_token": "1|plaintexttokenhere",
      "token_type": "Bearer",
      "user": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com",
          "created_at": "2025-08-24T22:12:00.000000Z",
          "updated_at": "2025-08-24T22:12:00.000000Z",
          "roles": [
              {
                  "name": "candidate",
                  "guard_name": "api",
                  "created_at": "2025-08-24T22:12:00.000000Z",
                  "updated_at": "2025-08-24T22:12:00.000000Z",
                  "pivot": {
                      "model_id": 1,
                      "role_id": 2,
                      "model_type": "App\\Models\\User"
                  }
              }
          ]
      }
  }
  ```

  For company:
  ```json
  {
      "name": "Company User",
      "email": "company@example.com",
      "password": "password123",
      "password_confirmation": "password123",
      "role": "company",
      "company_name": "Example Corp",
      "address": "123 Street",
      "website": "https://example.com"
  }
  ```

  - **Response (201)**:
  ```json
    {
        "message": "User registered successfully",
        "access_token": "1|plaintexttokenhere",
        "token_type": "Bearer",
        "user": {
            "name": "Company User",
            "email": "newcompany@gmail.com",
            "updated_at": "2025-08-24T16:25:53.000000Z",
            "created_at": "2025-08-24T16:25:53.000000Z",
            "id": 13,
            "roles": [
                {
                    "id": 6,
                    "name": "company",
                    "guard_name": "api",
                    "created_at": "2025-08-05T15:22:21.000000Z",
                    "updated_at": "2025-08-05T18:26:25.000000Z",
                    "pivot": {
                        "model_type": "App\\Models\\User",
                        "model_id": 13,
                        "role_id": 6
                    }
                }
            ]
        }
    }
  ```

#### POST /api/login
Log in and obtain a Bearer token.
- **Request Body**:
  ```json
  {
      "email": "john@example.com",
      "password": "password123"
  }
  ```
- **Response (200)**:
  ```json
  {
      "message": "Login successful",
      "access_token": "2|newplaintexttoken",
      "token_type": "Bearer",
      "user": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com",
          "created_at": "2025-08-24T22:12:00.000000Z",
          "updated_at": "2025-08-24T22:12:00.000000Z",
          "roles": [
              {
                  "name": "candidate",
                  "guard_name": "api",
                  "created_at": "2025-08-24T22:12:00.000000Z",
                  "updated_at": "2025-08-24T22:12:00.000000Z",
                  "pivot": {
                      "model_id": 1,
                      "role_id": 2,
                      "model_type": "App\\Models\\User"
                  }
              }
          ]
      }
  }
  ```

#### POST /api/logout
Log out and revoke the token.
- **Headers**: `Authorization: Bearer {token}`
- **Response (200)**:
  ```json
  {
      "message": "Logged out successfully"
  }
  ```

### Roles and Permissions

#### POST /api/roles
Create a role (requires `super_admin` or `admin`).
- **Headers**: `Authorization: Bearer {super_admin_token_or_admin_token}`
- **Request Body**:
  ```json
  {
      "name": "super_admin"
  }
  ```
- **Response (201)**:
  ```json
  {
      "message": "Role created successfully",
      "role": {
          "id": 1,
          "name": "super_admin",
          "guard_name": "api",
          "created_at": "2025-08-24T22:12:00.000000Z",
          "updated_at": "2025-08-24T22:12:00.000000Z"
      }
  }
  ```

#### GET /api/roles
Get All Roles (requires `super_admin` or `admin`).
- **Headers**: `Authorization: Bearer {super_admin_token_or_admin_token}`
- **Response (201)**:
  ```json
    [
        {
            "id": 1,
            "name": "super_admin",
            "guard_name": "api",
            "created_at": "2025-08-05T12:50:25.000000Z",
            "updated_at": "2025-08-05T18:26:25.000000Z"
        },
        {
            "id": 2,
            "name": "admin",
            "guard_name": "api",
            "created_at": "2025-08-05T12:50:25.000000Z",
            "updated_at": "2025-08-05T18:26:25.000000Z"
        },
        {
            "id": 3,
            "name": "staff",
            "guard_name": "api",
            "created_at": "2025-08-05T12:50:25.000000Z",
            "updated_at": "2025-08-05T18:26:25.000000Z"
        }
    ]
  ```

#### POST /api/permissions
Create a permission (requires `super_admin` or `admin`).
- **Headers**: `Authorization: Bearer {super_admin_token_or_admin_token}`
- **Request Body**:
  ```json
  {
      "name": "manage_jobs"
  }
  ```
- **Response (201)**:
  ```json
  {
      "message": "Permissions created successfully",
      "permissions": {
          "id": 1,
          "name": "manage_jobs",
          "guard_name": "api",
          "created_at": "2025-08-24T22:12:00.000000Z",
          "updated_at": "2025-08-24T22:12:00.000000Z"
      }
  }
  ```

#### GET /api/permissions
Get All Permissions (requires `super_admin` or `admin`).
- **Headers**: `Authorization: Bearer {super_admin_token_or_admin_token}`
- **Response (201)**:
  ```json
    [
        {
            "id": 1,
            "name": "user-list",
            "guard_name": "api",
            "created_at": "2025-08-05T12:52:07.000000Z",
            "updated_at": "2025-08-05T18:26:25.000000Z"
        },
        {
            "id": 2,
            "name": "user-create",
            "guard_name": "api",
            "created_at": "2025-08-05T12:52:07.000000Z",
            "updated_at": "2025-08-05T18:26:25.000000Z"
        },
        {
            "id": 5,
            "name": "role-list",
            "guard_name": "api",
            "created_at": "2025-08-05T12:52:07.000000Z",
            "updated_at": "2025-08-05T18:26:25.000000Z"
        }
    ]
  ```

#### GET /api/roles/{role}/permissions
List permissions for a role (requires `super_admin` or `admin`).
- **Headers**: `Authorization: Bearer {super_admin_token_or_admin_token}`
- **Response (200)**:
  ```json
  {
      "message": "success",
      "allpermissions": [
          "manage_jobs"
      ]
  }
  ```

#### POST /api/roles/{role}/permissions
Assign permissions to a role (requires `super_admin` or `admin`).
- **Headers**: `Authorization: Bearer {super_admin_token_or_admin_token}`
- **Request Body**:
  ```json
  {
      "permissions": ["manage_jobs"]
  }
  ```
- **Response (200)**:
  ```json
  {
      "message": "Permissions assigned to role"
  }
  ```

#### POST /api/users/{user}/roles
Assign roles to a user (requires `super_admin` or `admin`).
- **Headers**: `Authorization: Bearer {super_admin_token_or_admin_token}`
- **Request Body**:
  ```json
  {
      "roles": ["company"]
  }
  ```
- **Response (200)**:
  ```json
  {
      "message": "Roles assigned to user"
  }
  ```

#### GET /api/users/{user}/roles
List roles for a user (requires `super_admin` or `admin`).
- **Headers**: `Authorization: Bearer {super_admin_token_or_admin_token}`
- **Response (200)**:
  ```json
  [
      "company"
  ]
  ```


### Candidates

#### GET /api/candidates
List all candidates (requires `super_admin` or `company` role).
- **Headers**: `Authorization: Bearer {super_admin_token_or_company_token}`
- **Response (200)**:
  ```json
  [
      {
          "id": 1,
          "user_id": 1,
          "first_name": "John",
          "last_name": "Doe",
          "phone": "1234567890",
          "resume": null,
          "skills": "PHP, Laravel",
          "education": "BSc Computer Science",
          "experience": "2 years as a developer",
          "created_at": "2025-08-24T22:12:00.000000Z",
          "updated_at": "2025-08-24T22:12:00.000000Z"
      }
  ]
  ```

#### GET /api/candidates/{id}
View a candidate’s profile (requires `super_admin`, `company`, or candidate themselves).
- **Headers**: `Authorization: Bearer {super_admin_token_or_company_token_or_candidate_token}`
- **Response (200)**:
  ```json
  {
      "id": 1,
      "user_id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "phone": "1234567890",
      "resume": null,
      "skills": "PHP, Laravel",
      "education": "BSc Computer Science",
      "experience": "2 years as a developer",
      "created_at": "2025-08-24T22:12:00.000000Z",
      "updated_at": "2025-08-24T22:12:00.000000Z"
  }
  ```

#### PUT /api/candidates/{id}
Update a candidate’s profile (requires `super_admin` or candidate themselves).
- **Headers**: `Authorization: Bearer {super_admin_token_or_candidate_token}`
- **Request Body**:
  ```json
  {
      "first_name": "John",
      "last_name": "Smith",
      "phone": "9876543210",
      "skills": "PHP, Laravel, JavaScript",
      "education": "MSc Computer Science",
      "experience": "3 years as a senior developer"
  }
  ```
- **Response (200)**:
  ```json
  {
      "id": 1,
      "user_id": 1,
      "first_name": "John",
      "last_name": "Smith",
      "phone": "9876543210",
      "resume": null,
      "skills": "PHP, Laravel, JavaScript",
      "education": "MSc Computer Science",
      "experience": "3 years as a senior developer",
      "created_at": "2025-08-24T22:12:00.000000Z",
      "updated_at": "2025-08-24T22:12:00.000000Z"
  }
  ```

#### DELETE /api/candidates/{id}
Delete a candidate’s profile (requires `super_admin` or candidate themselves).
- **Headers**: `Authorization: Bearer {super_admin_token_or_candidate_token}`
- **Response (200)**:
  ```json
  {
      "message": "Candidate deleted"
  }
  ```

### Companies

#### GET /api/companies
List all companies (requires `super_admin`).
- **Headers**: `Authorization: Bearer {super_admin_token}`
- **Response (200)**:
  ```json
  [
      {
          "id": 1,
          "user_id": 2,
          "name": "Example Corp",
          "slug": "example-corp",
          "address": "123 Street",
          "website": "https://example.com",
          "description": null,
          "is_verified": true,
          "verification_document": "doc.pdf",
          "verified_at": "2025-08-24T22:12:00.000000Z",
          "created_at": "2025-08-24T22:12:00.000000Z",
          "updated_at": "2025-08-24T22:12:00.000000Z"
      }
  ]
  ```

#### GET /api/companies/{id}
View a company’s profile (requires `super_admin` or company owner).
- **Headers**: `Authorization: Bearer {super_admin_token_or_company_token}`
- **Response (200)**:
  ```json
  {
      "id": 1,
      "user_id": 2,
      "name": "Example Corp",
      "slug": "example-corp",
      "address": "123 Street",
      "website": "https://example.com",
      "description": null,
      "is_verified": true,
      "verification_document": "doc.pdf",
      "verified_at": "2025-08-24T22:12:00.000000Z",
      "created_at": "2025-08-24T22:12:00.000000Z",
      "updated_at": "2025-08-24T22:12:00.000000Z"
  }
  ```

#### PUT /api/companies/{id}
Update a company’s profile (requires `super_admin` or company owner; only `super_admin` can verify).
- **Headers**: `Authorization: Bearer {super_admin_token_or_company_token}`
- **Request Body**:
  ```json
  {
      "name": "Updated Corp",
      "address": "456 Avenue",
      "website": "https://updated.com",
      "description": "Updated description",
      "is_verified": true,
      "verification_document": "new_doc.pdf"
  }
  ```
- **Response (200)**:
  ```json
  {
      "id": 1,
      "user_id": 2,
      "name": "Updated Corp",
      "slug": "updated-corp",
      "address": "456 Avenue",
      "website": "https://updated.com",
      "description": "Updated description",
      "is_verified": true,
      "verification_document": "new_doc.pdf",
      "verified_at": "2025-08-24T22:12:00.000000Z",
      "created_at": "2025-08-24T22:12:00.000000Z",
      "updated_at": "2025-08-24T22:12:00.000000Z"
  }
  ```

#### DELETE /api/companies/{id}
Delete a company (requires `super_admin`).
- **Headers**: `Authorization: Bearer {super_admin_token}`
- **Response (200)**:
  ```json
  {
      "message": "Company deleted"
  }
  ```

### Job Categories

#### GET /api/job-categories
List all job categories (public).
- **Response (200)**:
  ```json
  [
      {
          "id": 1,
          "name": "Software Development",
          "slug": "software-development",
          "created_at": "2025-08-24T22:12:00.000000Z",
          "updated_at": "2025-08-24T22:12:00.000000Z"
      }
  ]
  ```

#### GET /api/job-categories/{id}
View a job category (public).
- **Response (200)**:
  ```json
  {
      "id": 1,
      "name": "Software Development",
      "slug": "software-development",
      "created_at": "2025-08-24T22:12:00.000000Z",
      "updated_at": "2025-08-24T22:12:00.000000Z"
  }
  ```

#### POST /api/job-categories
Create a job category (requires `super_admin`).
- **Headers**: `Authorization: Bearer {super_admin_token}`
- **Request Body**:
  ```json
  {
      "name": "Software Development"
  }
  ```
- **Response (201)**:
  ```json
  {
      "id": 1,
      "name": "Software Development",
      "slug": "software-development",
      "created_at": "2025-08-24T22:12:00.000000Z",
      "updated_at": "2025-08-24T22:12:00.000000Z"
  }
  ```

#### PUT /api/job-categories/{id}
Update a job category (requires `super_admin`).
- **Headers**: `Authorization: Bearer {super_admin_token}`
- **Request Body**:
  ```json
  {
      "name": "Web Development"
  }
  ```
- **Response (200)**:
  ```json
  {
      "id": 1,
      "name": "Web Development",
      "slug": "web-development",
      "created_at": "2025-08-24T22:12:00.000000Z",
      "updated_at": "2025-08-24T22:12:00.000000Z"
  }
  ```

#### DELETE /api/job-categories/{id}
Delete a job category (requires `super_admin`).
- **Headers**: `Authorization: Bearer {super_admin_token}`
- **Response (200)**:
  ```json
  {
      "message": "Category deleted"
  }
  ```

### Jobs

#### GET /api/jobs
List all published jobs (public).
- **Response (200)**:
  ```json
  [
      {
          "id": 1,
          "company_id": 1,
          "category_id": 1,
          "title": "Software Engineer",
          "slug": "software-engineer",
          "description": "Develop software solutions",
          "location": "Remote",
          "job_type": "full_time",
          "salary_min": 50000,
          "salary_max": 80000,
          "is_featured": false,
          "is_published": true,
          "created_at": "2025-08-24T22:12:00.000000Z",
          "updated_at": "2025-08-24T22:12:00.000000Z"
      }
  ]
  ```

#### GET /api/jobs/{id}
View a job (public for published jobs; unpublished jobs require `super_admin` or company owner).
- **Headers**: `Authorization: Bearer {super_admin_token_or_company_token}` (optional for published jobs)
- **Response (200)**:
  ```json
  {
      "id": 1,
      "company_id": 1,
      "category_id": 1,
      "title": "Software Engineer",
      "slug": "software-engineer",
      "description": "Develop software solutions",
      "location": "Remote",
      "job_type": "full_time",
      "salary_min": 50000,
      "salary_max": 80000,
      "is_featured": false,
      "is_published": true,
      "created_at": "2025-08-24T22:12:00.000000Z",
      "updated_at": "2025-08-24T22:12:00.000000Z"
  }
  ```

#### POST /api/jobs
Create a job (requires `super_admin` or verified `company`).
- **Headers**: `Authorization: Bearer {super_admin_token_or_company_token}`
- **Request Body**:
  ```json
  {
      "category_id": 1,
      "title": "Software Engineer",
      "description": "Develop software solutions",
      "location": "Remote",
      "job_type": "full_time",
      "salary_min": 50000,
      "salary_max": 80000
  }
  ```
- **Response (201)**:
  ```json
  {
      "id": 1,
      "company_id": 1,
      "category_id": 1,
      "title": "Software Engineer",
      "slug": "software-engineer",
      "description": "Develop software solutions",
      "location": "Remote",
      "job_type": "full_time",
      "salary_min": 50000,
      "salary_max": 80000,
      "is_featured": false,
      "is_published": true,
      "created_at": "2025-08-24T22:12:00.000000Z",
      "updated_at": "2025-08-24T22:12:00.000000Z"
  }
  ```

#### PUT /api/jobs/{id}
Update a job (requires `super_admin` or company owner).
- **Headers**: `Authorization: Bearer {super_admin_token_or_company_token}`
- **Request Body**:
  ```json
  {
      "title": "Senior Software Engineer",
      "description": "Lead software development",
      "salary_min": 60000,
      "salary_max": 90000
  }
  ```
- **Response (200)**:
  ```json
  {
      "id": 1,
      "company_id": 1,
      "category_id": 1,
      "title": "Senior Software Engineer",
      "slug": "senior-software-engineer",
      "description": "Lead software development",
      "location": "Remote",
      "job_type": "full_time",
      "salary_min": 60000,
      "salary_max": 90000,
      "is_featured": false,
      "is_published": true,
      "created_at": "2025-08-24T22:12:00.000000Z",
      "updated_at": "2025-08-24T22:12:00.000000Z"
  }
  ```

#### DELETE /api/jobs/{id}
Delete a job (requires `super_admin` or company owner).
- **Headers**: `Authorization: Bearer {super_admin_token_or_company_token}`
- **Response (200)**:
  ```json
  {
      "message": "Job deleted"
  }
  ```

### Job Applications

#### GET /api/job-applications
List job applications (filtered by role: `super_admin` sees all, `company` sees own job applications, `candidate` sees own applications).
- **Headers**: `Authorization: Bearer {super_admin_token_or_company_token_or_candidate_token}`
- **Response (200)**:
  ```json
  [
      {
          "id": 1,
          "candidate_id": 1,
          "job_id": 1,
          "status": "pending",
          "cover_letter": "I am excited to apply...",
          "created_at": "2025-08-24T22:12:00.000000Z",
          "updated_at": "2025-08-24T22:12:00.000000Z"
      }
  ]
  ```

#### GET /api/job-applications/{id}
View a job application (requires `super_admin`, company owning the job, or candidate who applied).
- **Headers**: `Authorization: Bearer {super_admin_token_or_company_token_or_candidate_token}`
- **Response (200)**:
  ```json
  {
      "id": 1,
      "candidate_id": 1,
      "job_id": 1,
      "status": "pending",
      "cover_letter": "I am excited to apply...",
      "created_at": "2025-08-24T22:12:00.000000Z",
      "updated_at": "2025-08-24T22:12:00.000000Z"
  }
  ```

#### POST /api/job-applications
Create a job application (requires `candidate`).
- **Headers**: `Authorization: Bearer {candidate_token}`
- **Request Body**:
  ```json
  {
      "job_id": 1,
      "cover_letter": "I am excited to apply..."
  }
  ```
- **Response (201)**:
  ```json
  {
      "id": 1,
      "candidate_id": 1,
      "job_id": 1,
      "status": "pending",
      "cover_letter": "I am excited to apply...",
      "created_at": "2025-08-24T22:12:00.000000Z",
      "updated_at": "2025-08-24T22:12:00.000000Z"
  }
  ```

#### PUT /api/job-applications/{id}
Update a job application’s status (requires `company` owning the job).
- **Headers**: `Authorization: Bearer {company_token}`
- **Request Body**:
  ```json
  {
      "status": "accepted"
  }
  ```
- **Response (200)**:
  ```json
  {
      "id": 1,
      "candidate_id": 1,
      "job_id": 1,
      "status": "accepted",
      "cover_letter": "I am excited to apply...",
      "created_at": "2025-08-24T22:12:00.000000Z",
      "updated_at": "2025-08-24T22:12:00.000000Z"
  }
  ```

#### DELETE /api/job-applications/{id}
Delete a job application (requires `super_admin` or candidate who applied).
- **Headers**: `Authorization: Bearer {super_admin_token_or_candidate_token}`
- **Response (200)**:
  ```json
  {
      "message": "Application deleted"
  }
  ```

## Testing with Postman
1. **Set Up Postman**:
   - Create a collection named `Job Portal API`.
   - Add a global variable `base_url` set to `http://localhost:8000/api`.

2. **Authentication**:
   - Register a user (`POST {{base_url}}/register`) with `role: super_admin`, `company`, or `candidate`.
   - Log in (`POST {{base_url}}/login`) to obtain a Bearer token.
   - Set the token in Postman’s `Authorization` header as `Bearer Token`.

3. **Test Endpoints**:
   - Use the JSON examples above to test each endpoint.
   - For public endpoints (`GET /api/jobs`, `GET /api/job-categories`), no `Authorization` header is needed.
   - For protected endpoints, ensure the correct role’s token is used (e.g., `super_admin` for company verification).
   - Example for `GET /api/candidates`:
     - URL: `{{base_url}}/candidates`
     - Headers: `Authorization: Bearer {{token}}`
     - Expected Response: List of candidates (200) or 403 if role is `candidate`.

4. **Error Handling**:
   - 400: Validation or database errors.
   - 403: Unauthorized (wrong role or no token).
   - 404: Resource not found.

## Contributing
1. Fork the repository.
2. Create a feature branch (`git checkout -b feature/new-feature`).
3. Commit changes (`git commit -m "Add new feature"`).
4. Push to the branch (`git push origin feature/new-feature`).
5. Create a pull request.

## License
This project is licensed under the MIT License.
