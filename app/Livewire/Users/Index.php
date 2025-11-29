<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination , WithoutUrlPagination;

    public function render()
    {
             $users = User::orderBy('created_at', 'desc')
             ->paginate(1);

             return view('livewire.users.index',compact('users'));
    }
}