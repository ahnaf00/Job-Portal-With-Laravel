<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function getAllRoles()
    {
        $roles = Role::all();
        return view('backend.pages.roles.all-roles',compact('roles'));
    }

    public function assignPermissionsView()
    {
        return view('backend.pages.roles.assign-permissions');
    }

    public function assignRolesView()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        $userRole = Auth::user()->roles()->pluck('name','name')->all();
        return view('backend.pages.roles.assign-roles', compact('users', 'roles','userRole'));
    }

    public function assignRoles(Request $request)
    {
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'roles'     => 'required|array',
            'roles.*'   => 'exists:roles,id',
            'operation' => 'required|in:assign,update'
        ]);

        $user = User::findOrFail($request->user_id);

        if ($request->operation === 'assign') {
            // Assign roles (add to existing roles)
            $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
            $user->assignRole($roleNames);
            $message = 'Roles assigned successfully!';
        } else {
            // Update roles (replace all existing roles)
            $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
            $user->syncRoles($roleNames);
            $message = 'User roles updated successfully!';
        }

        return redirect()->back()->with('success', $message);
    }

    public function createRoleView()
    {
        return view('backend.pages.roles.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:255'
        ]);

        Role::create(['name' => $request->name]);

        return redirect()->route('getAllRoles')->with('success','Role Created Successfully');
    }


    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        return view('backend.pages.roles.edit',compact('role'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$id,
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();

        return redirect()->route('getAllRoles')->with('success','Role updated successfully');
    }


    public function destroy(string $id)
    {
        //
    }
}
