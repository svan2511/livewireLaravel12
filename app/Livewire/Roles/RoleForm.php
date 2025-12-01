<?php

namespace App\Livewire\Roles;

use App\Services\RoleService;
use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\On;

class RoleForm extends Component
{
    public $buttonText = 'Create Role';
    public $editId = null;
    public $isView = false;

    public $label = '';
    public $desc = '';
    public $module = '';

    public array $permissions = [];
    public array $selectedPermissions = [];

    protected RoleService $service;

    public function boot(RoleService $service)
    {
        $this->service = $service;
    }

    public function render()
    {
        return view('livewire.roles.role-form');
    }

    /**
     * Real-time validation (FIXED)
     * - Only updates error for the current field
     * - Does NOT remove all other field errors
     */
    public function updated($field)
    {
        $this->validateOnly($field, $this->rules());
        $this->resetErrorBag($field);   // <--- critical fix
    }

    /**
     * Validation rules from service
     */
    protected function rules()
    {
        return $this->service->rules($this->editId);
    }

    /**
     * Validation messages from service
     */
    protected function messages()
    {
        return $this->service->messages();
    }

    /**
     * Handle create/edit submit
     */
    public function handleSubmit()
    {
        $this->validate();

        $this->service->saveRole(
            $this->editId,
            $this->module,
            $this->label,
            $this->desc,
            $this->selectedPermissions
        );

        $this->dispatch('toast', message: $this->service->successMessage($this->editId), type: 'success');
        $this->dispatch('member-created');

        Flux::modal('role-form')->close();
    }

    /**
     * Open modal for creating a new role
     */
    #[On('open-role-modal')]
    public function openModal()
    {
        $this->resetForm();
        $this->permissions = $this->service->getPermissions();

        Flux::modal('role-form')->show();
    }

    /**
     * Edit an existing role
     */
    #[On('edit-table')]
    public function editTableData($modal, $row)
    {
         $this->resetForm();
        $role = $this->service->find($row['id']);
        $this->service->prepareForEdit($this, $role, $modal);
    }

    /**
     * View role details
     */
    #[On('view-table')]
    public function viewTableData($modal, $row)
    {
         $this->resetForm();
        $role = $this->service->find($row['id']);
        $this->service->prepareForView($this, $role, $modal);
    }

    /**
     * Delete a role
     */
    #[On('delete-table')]
    public function deleteTableData($row)
    {
        $this->service->deleteRole($row['id']);

        $this->dispatch('toast', message: 'Role deleted successfully!', type: 'success');
        $this->dispatch('member-created');
    }

    /**
     * Close modal
     */
    #[On('close-modal')]
    public function closeModal()
    {
        Flux::modal('role-form')->close();
    }

    /**
     * Reset form state
     */
    private function resetForm()
    {
        $this->reset([
            'editId',
            'label',
            'desc',
            'module',
            'selectedPermissions',
            'isView',
            'buttonText',
        ]);

        $this->resetValidation();

        $this->permissions = [];
    }
}
