<?php

namespace App\Livewire\Roles;

use App\Models\Permission;
use App\Models\Role;
use Flux\Flux;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class RoleForm extends Component
{

    public $buttonText = "Create Role";
    public $editId = null;
    public $isView = false;
     
    public function render()
    {
        return view('livewire.roles.role-form');
    }

   
    #[Validate('required')]
    public $label = '';

    #[Validate('required')]
    public $desc = '';


    public function handleSubmit()
    {
        //$this->validate();
        $role = Role::find($this->editId);
        $data = [
            'module'       => $this->module,
            'label'      => $this->label,
            'desc'      => $this->desc,
            'name' => Str::slug($this->label)
            ];
        if($this->editId ) {
        $role->update($data);
        $msg= 'Role updated successfully!';
        }
        else 
            {
        Role::create($data);
        $msg = 'Role created successfully!';
        }

        //$this->dispatch('toast', ['message' => $msg, 'type' => 'success']);
        $this->dispatch('toast', message: $msg, type: 'success');
        $this->dispatch('member-created');
        Flux::modal('role-form')->close();
        
    }

    #[On('open-role-modal')]
    public function openModalnnFunction() { 
       $this->reset();
      $this->resetValidation();
      $this->resetErrorBag();
      $permissions = Permission::with('module')->orderBy('name')->get();
      $grpData = $permissions->groupBy(function($p){
        return $p->module->name ?? null ;
      });

      dd($grpData);
      Flux::modal('role-form')->show();
    }

    protected function messages(): array
    {
    return [
        // Member Name
        'label.required'     => 'Please enter the label.',
        'desc.required'          => 'Please enter the description.',
    ];
}

        #[On('edit-table')]
       public function editTableData($table, $row)
        {
            $this->editId = $row['id'];
             $this->isView = false;
            $role = Role::findOrFail($row['id']);
            $this->label      = $role->label;
            $this->desc     = $role->desc;
            $this->buttonText = "Update Role";
            Flux::modal($table)->show();
        }


        
        #[On('view-table')]
       public function viewTableData($table, $row)
        {
            $this->editId = $row['id'];
            $role = Role::findOrFail($row['id']);
            $this->label      = $role->label;
            $this->desc     = $role->desc;
            $this->buttonText = "View Role";
            $this->isView = true;
            Flux::modal($table)->show();
        }

         #[On('close-modal')]
        public function closeModalFunction() {
            Flux::modal('role-form')->close();
        }

         #[On('delete-table')]
        public function deleteTableData( $row) {
        $role = Role::find($row['id']);
        $role->delete();
        $this->dispatch('member-created');
        $this->dispatch('toast', message: "Role delete Successfully !", type: 'success');

        }
}
