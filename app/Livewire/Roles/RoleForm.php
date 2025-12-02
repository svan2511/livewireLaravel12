<?php

namespace App\Livewire\Roles;

use App\Services\RoleService;
use Exception;
use Flux\Flux;
use Illuminate\Support\Facades\Log;
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

        try {
            $this->service->saveRole(
            $this->editId,
            $this->module,
            $this->label,
            $this->desc,
            $this->selectedPermissions
        );
        $msg= $this->service->successMessage($this->editId);
        $type="success";

        }catch(Exception $ex) {
        Log::info("ERROR:ROLE1 : " . $ex->getMessage());
        $msg="Some internal error !";
        $type="error";
        }
        $this->dispatch('toast', message: $msg, type: $type);
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
        try {
        $this->permissions = $this->service->getPermissions();
        }catch(Exception $ex) {
        Log::info("ERROR:ROLE2 : " . $ex->getMessage());
        $msg="Some internal error !";
        $type="error";
        $this->dispatch('toast', message: $msg, type: $type);
        }
        Flux::modal('role-form')->show();
    }

    /**
     * Edit an existing role
     */
    #[On('edit-table')]
    public function editTableData($modal, $row)
    {
         $this->resetForm();
         try {
        $role = $this->service->find($row['id']);
        $this->service->prepareForEdit($this, $role, $modal);
         }catch(Exception $ex) {
        Log::info("ERROR:ROLE3 : " . $ex->getMessage());
        $msg="Some internal error !";
        $type="error";
        $this->dispatch('toast', message: $msg, type: $type);
        }
    }

    /**
     * View role details
     */
    #[On('view-table')]
    public function viewTableData($modal, $row)
    {
         $this->resetForm();
         try {
        $role = $this->service->find($row['id']);
        $this->service->prepareForView($this, $role, $modal);
         }catch(Exception $ex) {
        Log::info("ERROR:ROLE4 : " . $ex->getMessage());
        $msg="Some internal error !";
        $type="error";
        $this->dispatch('toast', message: $msg, type: $type);
        }
      
    }

    /**
     * Delete a role
     */
    #[On('delete-table')]
    public function deleteTableData($row)
    {
        try {
        $this->service->deleteRole($row['id']);
         $msg="Role deleted successfully!";
        $type="success";
        }catch(Exception $ex) {
        Log::info("ERROR:ROLE5 : " . $ex->getMessage());
        $msg="Some internal error !";
        $type="error";
        }
        
        $this->dispatch('toast', message: $msg, type: $type);
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
