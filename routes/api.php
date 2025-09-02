<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AllJobController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\JobCategoryController;
use App\Http\Controllers\Api\JobApplicationController;
use App\Http\Controllers\Api\RolePermissionController;


// Public routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/jobs', [AllJobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{id}', [AllJobController::class, 'show'])->name('jobs.show');
Route::get('/job-categories', [JobCategoryController::class, 'index'])->name('job-categories.index');
Route::get('/job-categories/{id}', [JobCategoryController::class, 'show'])->name('job-categories.show');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');

    // Admin routes
    Route::middleware('role:super_admin')->group(function () {
        Route::apiResource('companies', CompanyController::class)->except(['store']);
        
        // Company verification routes
        Route::get('/companies-pending', [CompanyController::class, 'pending'])->name('companies.pending');
        Route::get('/companies-verified', [CompanyController::class, 'verified'])->name('companies.verified');
        Route::patch('/companies/{id}/verify', [CompanyController::class, 'verify'])->name('companies.verify');
        Route::patch('/companies/{id}/unverify', [CompanyController::class, 'unverify'])->name('companies.unverify');
        
        Route::post('/job-categories', [JobCategoryController::class, 'store'])->name('job-categories.store');
        Route::put('/job-categories/{id}', [JobCategoryController::class, 'update'])->name('job-categories.update');
        Route::patch('/job-categories/{id}', [JobCategoryController::class, 'update'])->name('job-categories.patch');
        Route::delete('/job-categories/{id}', [JobCategoryController::class, 'destroy'])->name('job-categories.destroy');
    });

    // Role and Permission Management
    Route::middleware('role:super_admin|admin')->group(function () {
        Route::post('/roles', [RolePermissionController::class, 'createRole'])->name('createRole');
        Route::get('/roles', [RolePermissionController::class,'getAllRoles'])->name('getAllRoles');
        Route::get('/users', [RolePermissionController::class,'getAllUsers'])->name('getAllUsers');
        Route::get('/roles/{role}/permissions', [RolePermissionController::class, 'getPermissionsByRole'])->name('getPermissionsByRole');
        Route::post('/roles/{role}/permissions', [RolePermissionController::class, 'assignPermissionsToRole'])->name('assignPermissionsToRole');
        Route::post('/permissions', [RolePermissionController::class, 'createPermission'])->name('createPermission');
        Route::get('/permissions', [RolePermissionController::class, 'getAllPermissions'])->name('getallPermissions');
        
        // User role management routes
        Route::post('/users/{user}/roles', [RolePermissionController::class, 'assignRolesToUser'])->name('assignRolesToUser');
        Route::put('/users/{user}/roles', [RolePermissionController::class, 'updateUserRoles'])->name('updateUserRoles');
        Route::get('/users/{user}/roles', [RolePermissionController::class, 'getRolesByUser'])->name('getRolesByUser');
    });

    // General routes
    Route::apiResource('candidates', CandidateController::class)->except(['store']);
    
    // Company routes for company users
    Route::get('/companies/my-company', [CompanyController::class, 'myCompany'])->name('companies.my-company');
    
    Route::post('/jobs', [AllJobController::class, 'store'])->name('jobs.store');
    Route::put('/jobs/{id}', [AllJobController::class, 'update'])->name('jobs.update');
    Route::delete('/jobs/{id}', [AllJobController::class, 'destroy'])->name('jobs.destroy');
    
    // Job management routes
    Route::get('/jobs-admin', [AllJobController::class, 'adminIndex'])->name('jobs.admin');
    Route::get('/jobs-my', [AllJobController::class, 'myJobs'])->name('jobs.my');
    Route::get('/jobs-drafts', [AllJobController::class, 'draftJobs'])->name('jobs.drafts');
    Route::patch('/jobs/{id}/publish', [AllJobController::class, 'publish'])->name('jobs.publish');
    Route::patch('/jobs/{id}/unpublish', [AllJobController::class, 'unpublish'])->name('jobs.unpublish');
    Route::patch('/jobs/{id}/toggle-featured', [AllJobController::class, 'toggleFeatured'])->name('jobs.toggle-featured');
    
    Route::apiResource('job-applications', JobApplicationController::class);
});
