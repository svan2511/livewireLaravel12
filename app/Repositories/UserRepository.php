<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function getRoleList()
    {
        return Role::pluck('label', 'id')->toArray();
    }

    public function extractUserData(User $user)
    {
        return [
            'name'      => $user->name,
            'email'     => $user->email,
            'user_role' => $user->roles->pluck('id')->toArray(),
        ];
    }

    public function createUser($name, $email, $roles)
    {
       return DB::transaction(function () use ($name, $email, $roles) {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make("password"),
        ]);
        
        $roleNames = Role::whereIn('id', $roles)->pluck('name')->toArray();
        $user->syncRoles($roleNames);

        return $user;
    });
    }

    public function updateUser($id, $name, $email, $roles)
    {
        $user = User::findOrFail($id);
        $user->update([
            'name' => $name,
            'email' => $email,
        ]);

        $user->syncRoles(Role::find($roles));
        return $user;
    }

    public function deleteUser($id)
    {
        return User::findOrFail($id)->delete();
    }
}
