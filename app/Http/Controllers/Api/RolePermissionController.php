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
    // Create a new role
    public function createRole(Request $request)
    {
        try
        {
            $request->validate([
                'name' => 'required|unique:roles,name'
            ]);
            $role = Role::create(['name'=>$request->name]);
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
            'name' => $request->name
        ]);
        return response()->json([
            'message'       => 'Permissions created successfully',
            'permissions'   => $permission
        ],201);
    }

    // Assign Roles to a user
    public function assignRolesToUser(Request $request, User $user)
    {
        try{
            $request->validate([
                'roles' => 'required|array'
            ]);
            $user->assignRole($request->roles);

            return response()->json([
                'message' => 'Roles assigned to user'
            ],200);
        }
        catch(Exception $exception)
        {
            return response()->json([
                'message' => $exception->getMessage()
            ],401);
        }
    }

    // List roles for a user
    public function getRolesByUser(User $user)
    {
        return response()->json($user->getRoleNames(),200);
    }


}
