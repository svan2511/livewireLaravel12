<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    public function run()
    {
        $modules = [
            [
                'name' => 'Users',
                'slug' => 'users',
                'desc' => 'User management module',
            ],
            [
                'name' => 'Centers',
                'slug' => 'centers',
                'desc' => 'Center management module',
            ],
            [
                'name' => 'Members',
                'slug' => 'members',
                'desc' => 'Member management module',
            ],
            [
                'name' => 'Permissions',
                'slug' => 'permissions',
                'desc' => 'Permission management module',
            ],
            [
                'name' => 'Roles',
                'slug' => 'roles',
                'desc' => 'Role management module',
            ],
            [
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'desc' => 'Dashboard analytics module',
            ],
        ];

        foreach ($modules as $module) {
            DB::table('modules')->updateOrInsert(
                ['slug' => $module['slug']], // unique identifier
                [
                    'name' => $module['name'],
                    'desc' => $module['desc'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
