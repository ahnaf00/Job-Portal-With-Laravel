<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Middleware\AuthenticateUserMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'homePage'])->name('home');

// view files
Route::get('/registerView',[AuthenticationController::class,'registerView'])->name('registerView');
Route::get('/loginView',[AuthenticationController::class,'loginView'])->name('loginView');

Route::post('/register', [AuthenticationController::class, 'register'])->name('register');
Route::post('/login', [AuthenticationController::class, 'login'])->name('login');

Route::middleware([AuthenticateUserMiddleware::class])->group(function () {

    Route::get('/dashboard',[DashboardController::class,'dashboardPage'])->name('dashboard');

    Route::get('/roles',[RolesController::class,'getAllRoles'])->name('getAllRoles');
    Route::get('/roles/create',[RolesController::class,'createRoleView'])->name('createRoleView');
    Route::post('/roles',[RolesController::class,'store'])->name('createRole');
    Route::get('/roles/{id}/edit', [RolesController::class, 'edit'])->name('editRoleView');
    Route::put('/roles/{id}', [RolesController::class, 'update'])->name('updateRole');

    Route::get('/roles/assign-permissions',[RolesController::class,'assignPermissionsView'])->name('assignPermissionsView');


    Route::get('/permissions',[PermissionsController::class,'getAllPermissions'])->name('getAllPermissions');
    Route::get('/permissions/create',[PermissionsController::class,'createPermissionView'])->name('createPermissionView');
    Route::get('/roles/assign-roles',[RolesController::class,'assignRolesView'])->name('assignRolesView');
    Route::post('/roles/assign-roles',[RolesController::class,'assignRoles'])->name('assignRoles');


    Route::get('/companies',[CompaniesController::class,'getAllCompanies'])->name('getAllCompanies');
    Route::get('/companies/pending',[CompaniesController::class,'getPendingCompanies'])->name('getPendingCompanies');
    Route::get('/companies/verified',[CompaniesController::class,'getVerifiedCompanies'])->name('getVerifiedCompanies');
    Route::get('/companies/{company}/verify',[CompaniesController::class,'verifyCompanyView'])->name('verifyCompanyView');


    Route::get('/jobs-all',[JobsController::class,'getAllJobs'])->name('getAllJobs');
    Route::get('/jobs/create',[JobsController::class,'createJobView'])->name('createJobView');
    Route::get('/jobs/my-jobs',[JobsController::class,'getMyJobs'])->name('getMyJobs');
    Route::get('/jobs/drafts',[JobsController::class,'getDraftJobs'])->name('getDraftJobs');
    Route::get('/jobs/{job}/edit',[JobsController::class,'editJobView'])->name('editJobView');

    // Job categories page
    Route::get('/job-categories',[JobsController::class,'allCategories'])->name('allCategories');
    Route::get('/jobs/category/{id}', [JobsController::class, 'jobsByCategory'])->name('jobs.byCategory');



    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

});






// Role-based access control demo page
Route::get('/role-demo', function () {
    return view('backend.pages.role-demo');
})->name('roleDemoView');

// Route::get('/test-role', function () {
//     return auth()->user()->getRoleNames();
// });
