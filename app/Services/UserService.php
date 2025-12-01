<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Flux\Flux;

class UserService
{
    public function __construct(
        protected UserRepository $repo
    ) {}

    /** -----------------------------
     * VALIDATION RULES
     * ----------------------------- */
    public function rules($editId)
    {
        return [
            'name'      => 'required|string|max:255',
            'email'     => $editId
                            ? "required|email|unique:users,email,$editId"
                            : 'required|email|unique:users,email',
            'user_role' => 'required|array|min:1',
            'user_role.*' => 'exists:roles,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter the user name.',
            'email.required' => 'Please enter email id.',
            'email.email' => 'Enter valid email.',
            'user_role.required' => 'Please select role.',
        ];
    }

    /** -----------------------------
     * CREATE/EDIT/VIEW USER MODAL
     * ----------------------------- */
    public function prepareForCreate($component)
    {
        $component->reset();
        $component->resetErrorBag();
        $component->resetValidation();
        $component->buttonText = "Create User";
        $component->roles = $this->repo->getRoleList();
        Flux::modal('user-form')->show();
    }

    public function prepareForEdit($component, $user, $modal)
    {
        $component->editId = $user->id;
        $component->isView = false;
        $component->fill($this->repo->extractUserData($user));
        $component->buttonText = "Update User";
        $component->roles = $this->repo->getRoleList();
        Flux::modal($modal)->show();
    }

    public function prepareForView($component, $user, $modal)
    {
        $component->editId = $user->id;
        $component->isView = true;
        $component->fill($this->repo->extractUserData($user));
        $component->buttonText = "View User";
        $component->roles = $this->repo->getRoleList();
        Flux::modal($modal)->show();
    }

    /** -----------------------------
     * SAVE / DELETE USER
     * ----------------------------- */
    public function saveUser($id, $name, $email, $roles)
    {
        return $id
            ? $this->repo->updateUser($id, $name, $email, $roles)
            : $this->repo->createUser($name, $email, $roles);
    }

    public function deleteUser($id)
    {
        return $this->repo->deleteUser($id);
    }

    public function successMessage($id)
    {
        return $id
            ? "User updated successfully!"
            : "User created successfully!";
    }
}
