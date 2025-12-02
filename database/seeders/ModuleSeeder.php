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
                'name' => 'User',
                'slug' => 'user',
                'desc' => 'User management module',
            ],
            [
                'name' => 'Center',
                'slug' => 'center',
                'desc' => 'Center management module',
            ],
            [
                'name' => 'Member',
                'slug' => 'member',
                'desc' => 'Member management module',
            ],
            [
                'name' => 'Permission',
                'slug' => 'permission',
                'desc' => 'Permission management module',
            ],
            [
                'name' => 'Role',
                'slug' => 'role',
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
