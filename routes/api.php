<?php

use App\Http\Controllers\Api\RolePermissionController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register',[AuthController::class, 'register'])->name('register');
Route::post('/login',[AuthController::class, 'login'])->name('login');
Route::post('/logout',[AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');


Route::middleware(['auth:sanctum','role:super-admin|admin'])->group(function(){

    // Roles
    Route::post('/roles',[RolePermissionController::class,'createRole'])->name('createRole');
    Route::get('/roles/{role}/permissions',[RolePermissionController::class,'getPermissionsByRole'])->name('getPermissionsByRole');
    Route::post('/roles/{role}/permissions',[RolePermissionController::class,'assignPermissionsToRole'])->name('assignPermissionsToRole');

    // Permissions
    Route::post('/permissions',[RolePermissionController::class, 'createPermission'])->name('createPermission');

    // User Role Assignments
    Route::post('/users/{user}/roles',[RolePermissionController::class,'assignRolesToUser'])->name('assignRolesToUser');
    Route::get('/users/{user}/roles',[RolePermissionController::class,'getRolesByUser'])->name('getRolesByUser');
});
