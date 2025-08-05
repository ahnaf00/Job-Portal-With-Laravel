<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Permissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User management
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            // Role management
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            // Job Posts (example)
            'job-list',
            'job-create',
            'job-edit',
            'job-delete',

            // Applications
            'application-list',
            'application-review',
            'application-delete',
        ];

        // Create or Update permissions
        foreach($permissions as $permission)
        {
            Permission::firstOrCreate(['name'=>$permission]);
        }

        // Assign permissions to roles
        $superadmin     = Role::where('name', 'super-admin')->first();
        $admin          = Role::where('name','admin')->first();
        $staff          = Role::where('name','staff')->first();
        $user           = Role::where('name','user')->first();

        if($superadmin)
        {
            $superadmin->syncPermissions(Permission::all());
        }

        if($admin)
        {
            $adminPermissions = [
                'user-list',
                // 'user-create',
                // 'user-edit',
                // 'user-delete',
                'role-list',
                'role-create',
                'role-edit',
                'role-delete',
                'job-list',
                'job-create',
                'job-edit',
                'job-delete',
                'application-list',
                'application-review',
            ];
            $admin->syncPermissions($adminPermissions);
        }

        if ($staff) {
            $staffPermissions = [
                // Staff can manage jobs and review applications
                'job-list',
                'job-create',
                'job-edit',
                'job-delete',
                'application-list',
                'application-review',
            ];
            $staff->syncPermissions($staffPermissions);
        }

        if ($user) {
            $userPermissions = [
                // Users can view and list jobs and applications
                'job-list',
                'application-list',
            ];
            $user->syncPermissions($userPermissions);
        }

    }
}
