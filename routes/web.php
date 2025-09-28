<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('frontend.pages.home');
});

// Jobs listing page
// Route::get('/jobs', function () {
//     return view('frontend.pages.jobs');
// })->name('jobs');
Route::get('/jobs',[JobsController::class,'jobsPage'])->name('jobs');

// Route::get('/dashboard', function () {
//     return view('backend.pages.dashboard');
// })->name('dashboard');

Route::get('/dashboard',[DashboardController::class,'dashboardPage'])->name('dashboard');

// view files
Route::get('/registerView',[AuthenticationController::class,'registerView'])->name('registerView');
Route::get('/loginView',[AuthenticationController::class,'loginView'])->name('loginView');

// Roles management
/*
Route::get('/roles', function () {
    return view('backend.pages.roles.all-roles');
})->name('getAllRoles');

Route::get('/roles/create', function () {
    return view('backend.pages.roles.create');
})->name('createRoleView');

Route::get('/roles/assign-permissions', function () {
    return view('backend.pages.roles.assign-permissions');
})->name('assignPermissionsView');

*/

Route::get('/roles',[RolesController::class,'getAllRoles'])->name('getAllRoles');
Route::get('/roles/create',[RolesController::class,'createRoleView'])->name('createRoleView');
Route::get('/roles/assign-permissions',[RolesController::class,'assignPermissionsView'])->name('assignPermissionsView');



/*
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

*/

Route::get('/permissions',[PermissionsController::class,'getAllPermissions'])->name('getAllPermissions');
Route::get('/permissions/create',[PermissionsController::class,'createPermissionView'])->name('createPermissionView');
Route::get('/users/assign-roles',[RolesController::class,'assignRolesView'])->name('assignRolesView');



// Company management
/*
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

*/

Route::get('/companies',[CompaniesController::class,'getAllCompanies'])->name('getAllCompanies');
Route::get('/companies/pending',[CompaniesController::class,'getPendingCompanies'])->name('getPendingCompanies');
Route::get('/companies/verified',[CompaniesController::class,'getVerifiedCompanies'])->name('getVerifiedCompanies');
Route::get('/companies/{company}/verify',[CompaniesController::class,'verifyCompanyView'])->name('verifyCompanyView');


// Job management
/*

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

*/


Route::get('/jobs-all',[JobsController::class])->name('getAllJobs');
Route::get('/jobs/create',[JobsController::class])->name('createJobView');
Route::get('/jobs/my-jobs',[JobsController::class])->name('getMyJobs');
Route::get('/jobs/drafts',[JobsController::class])->name('getDraftJobs');
Route::get('/jobs/{job}/edit',[JobsController::class])->name('editJobView');






// Role-based access control demo page
Route::get('/role-demo', function () {
    return view('backend.pages.role-demo');
})->name('roleDemoView');
