<?php

namespace App\Services;

use App\Repositories\RoleRepository;
use App\Repositories\PermissionRepository;
use Illuminate\Support\Str;
use Flux\Flux;

class RoleService
{
    protected $roleRepo;
    protected $permissionRepo;

    public function __construct(RoleRepository $roleRepo, PermissionRepository $permissionRepo)
    {
        $this->roleRepo = $roleRepo;
        $this->permissionRepo = $permissionRepo;
    }

    public function rules($editId = null)
    {
        return [
            'label' => 'required|string|max:255',
            'desc' => 'required|string|max:500',
            // 'module' => 'required|string',
            'selectedPermissions' => 'required|array|min:1',
        ];
    }

    public function messages()
    {
        return [
            'label.required' => 'Please enter the label.',
            'desc.required' => 'Please enter the description.',
            // 'module.required' => 'Please select a module.',
            'selectedPermissions.required' => 'Please select at least one permission.',
        ];
    }

    public function saveRole($editId, $module, $label, $desc, $selectedPermissions)
    {
        $role = $editId
            ? $this->roleRepo->update($editId, [
                'module' => $module,
                'label' => $label,
                'desc' => $desc,
                'name' => Str::slug($label),
            ])
            : $this->roleRepo->create([
                'module' => $module,
                'label' => $label,
                'desc' => $desc,
                'name' => Str::slug($label),
            ]);

        $this->roleRepo->syncPermissions($role, $selectedPermissions);
    }

    public function getPermissions()
    {
        return $this->permissionRepo->allGroupedByModule();
    }

    public function prepareForEdit($component, $role, $modal)
    {
        $component->editId = $role->id;
        $component->isView = false;
        $component->label = $role->label;
        $component->desc = $role->desc;
        $component->module = $role->module;
        $component->selectedPermissions = $role->permissions->pluck('id')->toArray();
        $component->buttonText = "Update Role";
        $component->permissions = $this->getPermissions();
        Flux::modal($modal)->show();
    }

    public function prepareForView($component, $role, $modal)
    {
        $component->editId = $role->id;
        $component->isView = true;
        $component->label = $role->label;
        $component->desc = $role->desc;
        $component->module = $role->module;
        $component->selectedPermissions = $role->permissions->pluck('id')->toArray();
        $component->buttonText = "View Role";
        $component->permissions = $this->getPermissions();
        Flux::modal($modal)->show();
    }

    public function deleteRole($roleId)
    {
        $this->roleRepo->delete($roleId);
    }

    public function find($roleId)
    {
        return $this->roleRepo->find($roleId);
    }

    public function successMessage($editId)
    {
        return $editId ? 'Role updated successfully!' : 'Role created successfully!';
    }
}
