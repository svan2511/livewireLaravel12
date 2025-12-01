<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleRepository
{
    public function find($id)
    {
        return Role::with('permissions')->findOrFail($id);
    }

    public function create($data)
    {
        return Role::create($data);
    }

    public function update($id, $data)
    {
        $role = $this->find($id);
        $role->update($data);
        return $role;
    }

    public function delete($id)
    {
        $role = $this->find($id);
        $role->delete();
    }

    public function syncPermissions($role, $permissionIds)
    {
        $role->syncPermissions(Permission::whereIn('id', $permissionIds)->get());
    }
}
