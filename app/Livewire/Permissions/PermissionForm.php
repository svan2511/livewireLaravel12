<?php

namespace App\Livewire\Permissions;

use App\Models\Module;
use App\Models\Permission;
use Flux\Flux;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PermissionForm extends Component
{
    public $buttonText = "Create Permission";
    public array $modules = [];
    public $editId = null;
    public $isView = false;
     

    public function render()
    {
        return view('livewire.permissions.permission-form');
    }

   
    #[Validate('required|string|max:255')]
    public $module = '';

    #[Validate('required')]
    public $label = '';

    #[Validate('required')]
    public $desc = '';


    public function handleSubmit()
    {
        //$this->validate();
        $permission = Permission::find($this->editId);
        $data = [
            'module'       => $this->module,
            'label'      => $this->label,
            'desc'      => $this->desc,
            'name' => Str::slug($this->label)
            ];
        if($this->editId ) {
        $permission->update($data);
        $msg= 'Permission updated successfully!';
        }
        else 
            {
        Permission::create($data);
        $msg = 'Permission created successfully!';
        }

        //$this->dispatch('toast', ['message' => $msg, 'type' => 'success']);
        $this->dispatch('toast', message: $msg, type: 'success');
        $this->dispatch('member-created');
        Flux::modal('permission-form')->close();
        
    }

    #[On('open-permission-modal')]
    public function openModalnnFunction() { 
       $this->reset();
      $this->resetValidation();
      $this->resetErrorBag();
      $this->modules = Module::pluck('name', 'slug')->toArray();
    
      Flux::modal('permission-form')->show();
    }

    protected function messages(): array
    {
    return [
        // Member Name
        'label.required'     => 'Please enter the label.',
        'module.required'       => 'Please enter the module.',
        'desc.required'          => 'Please enter the description.',
    ];
}

        #[On('edit-table')]
       public function editTableData($table, $row)
        {
            $this->editId = $row['id'];
             $this->isView = false;
            $permission = Permission::findOrFail($row['id']);
            $this->modules = Module::pluck('name', 'slug')->toArray();
            $this->label      = $permission->label;
            $this->desc     = $permission->desc;
            $this->module     = $permission->module;
            $this->buttonText = "Update Permission";
            Flux::modal($table)->show();
        }


        
        #[On('view-table')]
       public function viewTableData($table, $row)
        {
            $this->editId = $row['id'];
            $permission = Permission::findOrFail($row['id']);
            $this->label      = $permission->label;
            $this->desc     = $permission->desc;
            $this->module     = $permission->module;
            $this->buttonText = "View Permission";
            $this->isView = true;
            Flux::modal($table)->show();
        }

         #[On('close-modal')]
        public function closeModalFunction() {
            Flux::modal('permission-form')->close();
        }

         #[On('delete-table')]
        public function deleteTableData( $row) {
        $permission = Permission::find($row['id']);
        $permission->delete();
        $this->dispatch('member-created');
        $this->dispatch('toast', message: "Permission delete Successfully !", type: 'success');

        }
}
