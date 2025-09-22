<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('frontend.pages.home');
});

// Jobs listing page
Route::get('/jobs', function () {
    return view('frontend.pages.jobs');
})->name('jobs');

// Protected Dashboard Routes - Require Authentication via JavaScript
Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', function () {
        return view('backend.pages.dashboard');
    })->name('dashboard');

    // Roles management
    Route::get('/roles', function () {
        return view('backend.pages.roles.all-roles');
    })->name('getAllRoles');
    Route::get('/roles/create', function () {
        return view('backend.pages.roles.create');
    })->name('createRoleView');
    Route::get('/roles/assign-permissions', function () {
        return view('backend.pages.roles.assign-permissions');
    })->name('assignPermissionsView');

    // Permissions management
    Route::get('/permissions', function () {
        return view('backend.pages.permissions.all-permissions');
    })->name('getAllPermissions');
    Route::get('/permissions/create', function () {
        return view('backend.pages.permissions.create');
    })->name('createPermissionView');

    // User-Role management
    Route::get('/users/assign-roles', function () {
        return view('backend.pages.users.assign-roles');
    })->name('assignRolesView');

    // Company management
    Route::get('/companies', function () {
        return view('backend.pages.companies.all-companies');
    })->name('getAllCompanies');
    Route::get('/companies/pending', function () {
        return view('backend.pages.companies.pending-companies');
    })->name('getPendingCompanies');
    Route::get('/companies/verified', function () {
        return view('backend.pages.companies.verified-companies');
    })->name('getVerifiedCompanies');
    Route::get('/companies/{company}/verify', function () {
        return view('backend.pages.companies.verify-company');
    })->name('verifyCompanyView');

    // Job management
    Route::get('/jobs-all', function () {
        return view('backend.pages.jobs.all-jobs');
    })->name('getAllJobs');
    Route::get('/jobs/create', function () {
        return view('backend.pages.jobs.create-job');
    })->name('createJobView');
    Route::get('/jobs/my-jobs', function () {
        return view('backend.pages.jobs.my-jobs');
    })->name('getMyJobs');
    Route::get('/jobs/drafts', function () {
        return view('backend.pages.jobs.draft-jobs');
    })->name('getDraftJobs');
    Route::get('/jobs/{job}/edit', function () {
        return view('backend.pages.jobs.edit-job');
    })->name('editJobView');

    // Role-based access control demo page
    Route::get('/role-demo', function () {
        return view('backend.pages.role-demo');
    })->name('roleDemoView');
});

// Public Authentication Routes
Route::get('/registerView',[AuthController::class,'registerView'])->name('registerView');
Route::get('/loginView',[AuthController::class,'loginView'])->name('loginView');
