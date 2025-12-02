<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $modules = [
            'dashboard',
            'user',
            'center',
            'member',
            'permission',
            'role',
        ];

        $actions = ['create','view','edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {

                $permissionName = "{$action}-{$module}";
                $label = ucfirst($action) . ' ' . ucfirst($module);
                $desc  = "Can {$action} {$module}";

                Permission::firstOrCreate(
                    ['name' => $permissionName],
                    [
                        'module' => $module,
                        'label'  => $label,
                        'desc'   => $desc,   // ← FIX ADDED
                    ]
                );
            }
        }
    }
}
