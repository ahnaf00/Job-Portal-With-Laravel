<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Exception;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function getAllPermissions()
    {
        try
        {
            $permissions = Permission::all();
            return response()->json($permissions);
        }
        catch(Exception $exception)
        {
            return response()->json(
                [
                    'error' => $exception->getMessage()
                ]
            );
        }
    }

    public function getAllRoles()
    {
        try
        {
            $roles = Role::all();
            return response()->json($roles);
        }
        catch(Exception $exception)
        {
            return response()->json(
                [
                    'error' => $exception->getMessage()
                ]
            );
        }
    }

    public function getAllUsers()
    {
        try
        {
            $users = User::with('roles')->get();
            return response()->json($users);
        }
        catch(Exception $exception)
        {
            return response()->json(
                [
                    'error' => $exception->getMessage()
                ]
            );
        }
    }

    // Create a new role
    public function createRole(Request $request)
    {
        try
        {
            $request->validate([
                'name' => 'required|unique:roles,name'
            ]);
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'api'
            ]);
            return response()->json(
                [
                    'message'   => 'Role created successfuly',
                    'role'      => $role
                ],201);
        }
        catch(Exception $exception)
        {
            return response()->json([
                'message' => $exception->getMessage()
            ]);
        }
    }

    // List permissions for a role
    public function getPermissionsByRole(Role $role)
    {
        $allPermissions = $role->permissions()->pluck('name');

        return response()->json([
            'message'           => 'success',
            'allpermissions'    => $allPermissions
        ],200);
    }

    // Assign permissions to a role
    public function assignPermissionsToRole(Request $request, Role $role)
    {
        try
        {
            $request->validate([
                'permissions' => 'required|array'
            ]);
            $role->syncPermissions($request->permissions);
            return response()->json([
                'message' => 'Permissions assigned to role'
            ],200);
        }catch(Exception $exception)
        {
            return response()->json([
                'message' => $exception->getMessage()
            ],401);
        }
    }

    // Create a permissions
    public function createPermission(Request $request)
    {
        $request->validate([
            'name'=> 'required|unique:permissions,name'
        ]);
        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'api'
        ]);
        return response()->json([
            'message'       => 'Permissions created successfully',
            'permissions'   => $permission
        ],201);
    }

    // Assign Roles to a user (adds roles without removing existing ones)
    public function assignRolesToUser(Request $request, User $user)
    {
        try{
            $request->validate([
                'roles' => 'required|array'
            ]);
            $user->assignRole($request->roles);

            return response()->json([
                'message' => 'Roles assigned to user successfully',
                'user' => $user->load('roles')
            ],200);
        }
        catch(Exception $exception)
        {
            return response()->json([
                'message' => $exception->getMessage()
            ],401);
        }
    }

    // Update user roles (replaces all existing roles with new ones)
    public function updateUserRoles(Request $request, User $user)
    {
        try{
            $request->validate([
                'roles' => 'required|array'
            ]);
            
            // syncRoles will remove all existing roles and assign the new ones
            $user->syncRoles($request->roles);

            return response()->json([
                'message' => 'User roles updated successfully',
                'user' => $user->load('roles')
            ],200);
        }
        catch(Exception $exception)
        {
            return response()->json([
                'message' => $exception->getMessage()
            ],401);
        }
    }

    // List roles for a user with detailed information
    public function getRolesByUser(User $user)
    {
        try {
            $userWithRoles = $user->load('roles');
            return response()->json([
                'message' => 'User roles retrieved successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ],
                'roles' => $userWithRoles->roles,
                'role_names' => $userWithRoles->getRoleNames()
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }


}
