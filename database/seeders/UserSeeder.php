<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        $superadmin = User::create([
            'name' => 'Ahnaf Anan',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('123')
        ]);
        $superadmin->assignRole('super-admin');

        // Admin
        $admin = User::create([
            'name' => 'Admin user',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123')
        ]);
        $admin->assignRole('admin');

        // Staff
        $staff = User::create([
            'name' => 'Staff user',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('123')
        ]);
        $staff->assignRole('staff');

        // User
        $user = User::create([
            'name' => 'Normal user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('123')
        ]);
        $user->assignRole('user');
    }
}
