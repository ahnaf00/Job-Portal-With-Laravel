<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::firstOrCreate(['name'=>'super-admin']);
        $allPermissions = Permission::all();

        $superAdminRole->syncPermissions($allPermissions);

        $user = User::create([
            'name' => 'Ahnaf Anan',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make(123),
            'remember_token' => Str::random(10)
        ]);

        $user->assignRole($superAdminRole);
    }
}
