<?php

namespace App\Repositories;

use App\Models\Module;
use App\Models\Permission;

class PermissionRepository
{
    public function allGroupedByModule()
    {
        return Module::with('permissions')->get()
            ->mapWithKeys(fn($module) => [$module->slug => $module->permissions->pluck('label', 'id')])
            ->toArray();
    }

    public function find($id)
    {
        return Permission::findOrFail($id);
    }

    public function create(array $data)
    {
        return Permission::create($data);
    }

    public function update($id, array $data)
    {
        $permission = $this->find($id);
        $permission->update($data);
        return $permission;
    }

    public function delete($id)
    {
        return Permission::findOrFail($id)->delete();
    }
}
