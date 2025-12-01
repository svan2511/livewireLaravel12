<?php

namespace App\Livewire\Users;

use App\Services\UserService;
use App\Models\User;
use Flux\Flux;
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

        $this->service->saveUser($this->editId, $this->name, $this->email, $this->user_role);

        $this->dispatch('toast', message: $this->service->successMessage($this->editId), type: 'success');
        $this->dispatch('member-created');

        Flux::modal('user-form')->close();
    }

    #[On('open-user-modal')]
    public function openModal()
    {
        $this->service->prepareForCreate($this);
    }

    #[On('edit-table')]
    public function edit($modal, $row)
    {
        $user = User::findOrFail($row['id']);
        $this->service->prepareForEdit($this, $user, $modal);
    }

    #[On('view-table')]
    public function view($modal, $row)
    {
        $user = User::findOrFail($row['id']);
        $this->service->prepareForView($this, $user, $modal);
    }

    #[On('delete-table')]
    public function delete($row)
    {
        $this->service->deleteUser($row['id']);
        $this->dispatch('toast', message: "User deleted successfully!", type: 'success');
        $this->dispatch('member-created');
    }

    #[On('close-modal')]
    public function closeModal()
    {
        Flux::modal('user-form')->close();
    }
}
