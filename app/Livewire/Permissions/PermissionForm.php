<?php

namespace App\Livewire\Permissions;

use App\Services\PermissionService;
use App\Models\Module;
use Exception;
use Flux\Flux;
use Illuminate\Support\Facades\Log;
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

        try {
            $this->service->save(
            $this->editId,
            $this->module,
            $this->label,
            $this->desc
        );

        $msg =  $this->service->successMessage($this->editId);
        $type = "success";
        } catch(Exception $ex) {
        Log::info("ERROR:PERM1 : " . $ex->getMessage());
        $msg =  "Some Internal Error !";
        $type = "error";
        }

        $this->dispatch('toast', message: $msg, type: $type);
        $this->dispatch('member-created');

        Flux::modal('permission-form')->close();
    }

    #[On('open-permission-modal')]
    public function openModal()
    {
        $this->resetForm();
        try{
        $this->modules = Module::pluck('name', 'slug')->toArray();
        Flux::modal('permission-form')->show();
        }catch(Exception $ex) {
        Log::info("ERROR:PERM2 : " . $ex->getMessage());
        $this->dispatch('toast', message: "Some internal error !", type: "error");
        }
       
    }

    #[On('edit-table')]
    public function editTableData($modal, $row)
    {
        try {
        $permission = $this->service->find($row['id']);
        $this->modules = Module::pluck('name', 'slug')->toArray();
        $this->service->prepareForEdit($this, $permission, $modal);
        }catch(Exception $ex) {
        Log::info("ERROR:PERM3 : " . $ex->getMessage());
        $this->dispatch('toast', message: "Some internal error !", type: "error");
        }
       
    }

    #[On('view-table')]
    public function viewTableData($modal, $row)
    {
        try {
        $permission = $this->service->find($row['id']);
        $this->modules = Module::pluck('name', 'slug')->toArray();
        $this->service->prepareForView($this, $permission, $modal);
        }catch(Exception $ex) {
        Log::info("ERROR:PERM4 : " . $ex->getMessage());
        $this->dispatch('toast', message: "Some internal error !", type: "error");
        }
       
    }

    #[On('delete-table')]
    public function deleteTableData($row)
    {
        try {
        $this->service->delete($row['id']);
        $msg="Permission deleted successfully!";
        $type="success";
        }catch(Exception $ex) {
        Log::info("ERROR:PERM5 : " . $ex->getMessage());
        $msg="Some internal error !";
        $type="error";
        }

        $this->dispatch('toast', message: $msg, type: $type);
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
