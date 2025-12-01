<?php

namespace App\Livewire\Permissions;

use App\Services\PermissionService;
use App\Models\Module;
use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\On;

class PermissionForm extends Component
{
    public $buttonText = "Create Permission";
    public $editId = null;
    public $isView = false;

    public $module = '';
    public $label = '';
    public $desc = '';
    public array $modules = [];

    protected PermissionService $service;

    public function boot(PermissionService $service)
    {
        $this->service = $service;
    }

    public function render()
    {
        return view('livewire.permissions.permission-form');
    }

    // Real-time validation
    public function updated($field)
    {
        $this->validateOnly($field, $this->service->rules(), $this->service->messages());
    }

    protected function rules()
    {
        return $this->service->rules($this->editId);
    }

    protected function messages()
    {
        return $this->service->messages();
    }

    // Submit form
    public function handleSubmit()
    {
        $this->validate();

        $this->service->save(
            $this->editId,
            $this->module,
            $this->label,
            $this->desc
        );

        $this->dispatch('toast', message: $this->service->successMessage($this->editId), type: 'success');
        $this->dispatch('member-created');

        Flux::modal('permission-form')->close();
    }

    #[On('open-permission-modal')]
    public function openModal()
    {
        $this->resetForm();
        $this->modules = Module::pluck('name', 'slug')->toArray();
        Flux::modal('permission-form')->show();
    }

    #[On('edit-table')]
    public function editTableData($modal, $row)
    {
        $permission = $this->service->find($row['id']);
        $this->modules = Module::pluck('name', 'slug')->toArray();
        $this->service->prepareForEdit($this, $permission, $modal);
    }

    #[On('view-table')]
    public function viewTableData($modal, $row)
    {
        $permission = $this->service->find($row['id']);
        $this->modules = Module::pluck('name', 'slug')->toArray();
        $this->service->prepareForView($this, $permission, $modal);
    }

    #[On('delete-table')]
    public function deleteTableData($row)
    {
        $this->service->delete($row['id']);
        $this->dispatch('toast', message: "Permission deleted successfully!", type: 'success');
        $this->dispatch('member-created');
    }

    #[On('close-modal')]
    public function closeModal()
    {
        Flux::modal('permission-form')->close();
    }

    private function resetForm()
    {
        $this->reset(['editId', 'label', 'desc', 'module', 'isView', 'buttonText']);
        $this->resetValidation();
    }
}
