<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create admin role if not exists
       $adminRole = Role::firstOrCreate(
                    ['name' => 'admin'],
                    [
                        'label' => 'Admin',
                        'desc'  => 'This is for Admin User Role',
                    ]
                );

        // Assign ALL permissions to admin role
        $permissions = Permission::all();
        $adminRole->syncPermissions($permissions);

        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'], // unique field
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // change in production
            ]
        );

        // Assign admin role to the user
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}
