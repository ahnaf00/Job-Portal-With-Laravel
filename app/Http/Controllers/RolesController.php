<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function getAllRoles()
    {
        return view('backend.pages.roles.all-roles');
    }
    /**
     * Display a listing of the resource.
     */
    public function createRoleView()
    {
        return view('backend.pages.roles.create');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function assignPermissionsView()
    {
        return view('backend.pages.roles.assign-permissions');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function assignRolesView()
    {
        return view('backend.pages.users.assign-roles');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
