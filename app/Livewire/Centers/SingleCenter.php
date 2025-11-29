<?php

namespace App\Livewire\Centers;

use App\Models\Center;
use Livewire\Component;

class SingleCenter extends Component
{


    public Center $center;

    public function mount(Center $center)
    {
        $this->center = $center;
    }


    public function render()
    {
        return view('livewire.centers.single-center');
    }
}
