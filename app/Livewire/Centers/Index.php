<?php

namespace App\Livewire\Centers;

use App\Models\Center;
use Flux\Flux;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

      public $buttonText = 'Add New Center';

    public function render()
    {
        return view('livewire.centers.index');
    }
    
}
