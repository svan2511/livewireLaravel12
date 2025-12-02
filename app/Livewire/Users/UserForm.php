<?php

namespace App\Livewire\Users;

use App\Services\UserService;
use App\Models\User;
use Exception;
use Flux\Flux;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;

class UserForm extends Component
{
    public $buttonText = "Create User";
    public $roles = [];
    public $editId = null;
    public $isView = false;

    public $name = '';
    public $email = '';
    public $user_role = [];

    protected $service;

    // Livewire-safe service injection
    public function boot(UserService $service)
    {
        $this->service = $service;
    }

    protected function rules()
    {
        return $this->service->rules($this->editId);
    }

    protected function messages()
    {
        return $this->service->messages();
    }

    // Real-time validation
    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function render()
    {
        return view('livewire.users.user-form');
    }

    public function handleSubmit()
    {
        $this->validate();

        try {
        $this->service->saveUser($this->editId, $this->name, $this->email, $this->user_role);
        $msg= $this->service->successMessage($this->editId);
        $type="success";
        }catch(Exception $ex) {
        Log::info("ERROR:USER5 : " . $ex->getMessage());
        $msg="Some internal error !";
        $type="error";
        }
        $this->dispatch('toast', message: $msg, type: $type);
        $this->dispatch('member-created');
        Flux::modal('user-form')->close();
    }

    #[On('open-user-modal')]
    public function openModal()
    {
        try {
        $this->service->prepareForCreate($this);
        }catch(Exception $ex) {
        Log::info("ERROR:USER1 : " . $ex->getMessage());
        $msg="Some internal error !";
        $type="error";
        $this->dispatch('toast', message: $msg, type: $type);

        }
    }

    #[On('edit-table')]
    public function edit($modal, $row)
    {
        try {
        $user = User::findOrFail($row['id']);
        $this->service->prepareForEdit($this, $user, $modal);
        }catch(Exception $ex) {
        Log::info("ERROR:USER2 : " . $ex->getMessage());
        $msg="Some internal error !";
        $type="error";
        $this->dispatch('toast', message: $msg, type: $type);
        }
        
    }

    #[On('view-table')]
    public function view($modal, $row)
    {
        try {
        $user = User::findOrFail($row['id']);
        $this->service->prepareForView($this, $user, $modal);
        }catch(Exception $ex) {
        Log::info("ERROR:USER3 : " . $ex->getMessage());
        $msg="Some internal error !";
        $type="error";
        $this->dispatch('toast', message: $msg, type: $type);

        }
       
    }

    #[On('delete-table')]
    public function delete($row)
    {
        try {
        $this->service->deleteUser($row['id']);
         $msg="User deleted successfully!";
        $type="success";
        }catch(Exception $ex) {
        Log::info("ERROR:USER4 : " . $ex->getMessage());
        $msg="Some internal error !";
        $type="error";
        }
        
        $this->dispatch('toast', message: $msg, type: $type);
        $this->dispatch('member-created');
    }

    #[On('close-modal')]
    public function closeModal()
    {
        Flux::modal('user-form')->close();
    }
}
