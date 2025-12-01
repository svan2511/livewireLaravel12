<?php

namespace App\Services;

use App\Repositories\PermissionRepository;
use Illuminate\Support\Str;
use Flux\Flux;

class PermissionService
{
    protected $repo;

    public function __construct(PermissionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function rules($editId = null)
    {
        return [
            'module' => 'required|string|max:255',
            'label'  => 'required|string|max:255',
            'desc'   => 'required|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'module.required' => 'Please select module.',
            'label.required'  => 'Please enter label.',
            'desc.required'   => 'Please enter description.',
        ];
    }

    public function save($editId, $module, $label, $desc)
    {
        $data = [
            'module' => $module,
            'label'  => $label,
            'desc'   => $desc,
            'name'   => Str::slug($label),
        ];

        return $editId
            ? $this->repo->update($editId, $data)
            : $this->repo->create($data);
    }

    public function prepareForEdit($component, $permission, $modal)
    {
        $component->editId  = $permission->id;
        $component->isView  = false;
        $component->label   = $permission->label;
        $component->desc    = $permission->desc;
        $component->module  = $permission->module;
        $component->buttonText = "Update Permission";

        Flux::modal($modal)->show();
    }

    public function prepareForView($component, $permission, $modal)
    {
        $component->editId  = $permission->id;
        $component->isView  = true;
        $component->label   = $permission->label;
        $component->desc    = $permission->desc;
        $component->module  = $permission->module;
        $component->buttonText = "View Permission";

        Flux::modal($modal)->show();
    }

    public function successMessage($editId)
    {
        return $editId ? "Permission updated successfully!" : "Permission created successfully!";
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}
