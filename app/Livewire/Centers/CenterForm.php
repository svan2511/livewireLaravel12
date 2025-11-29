<?php

namespace App\Livewire\Centers;

use App\Models\Center;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CenterForm extends Component
{

    #[Validate('required|string|max:255')]
    public $centerName;

    #[Validate('required|string|max:255',message: 'Center address is mandatory!')]
    public $centerAddress;
    
    public $buttonText = "Create Center";
    public $editCenterId = null;
    public function render()
    {
        return view('livewire.centers.center-form');
    }

    #[On('open-form-modal')]

    public function openModalFunction() {
        
         $this->reset(['centerName', 'centerAddress']);
        $this->resetValidation();
        $this->resetErrorBag();
         Flux::modal('center-form')->show();
    }

     #[On('close-modal')]
    public function closeModalFunction() {
       
        Flux::modal('center-form')->close();
    }

    #[On('edit-table')]
    public function editTableData($table , $row) {
       $this->centerName = $row['center_name'];
       $this->centerAddress = $row['center_address'];
       $this->buttonText = "Update Center";
       $this->editCenterId = $row['id'];
      Flux::modal($table)->show();
    }

    #[On('delete-table')]
    public function deleteTableData( $row) {
        $center = Center::find($row['id']);
        $center->delete();
        $this->dispatch('center-created');
        $this->dispatch('toast', message: "Center delete Successfully !", type: 'success');

    }

    
    
    protected function messages(): array
    {
        return [
            'centerName.required'    => __('The center name is required.'),
            'centerName.string'      => __('The center name must be a valid text.'),
            'centerName.max'         => __('The center name may not be greater than 255 characters.'),

            'centerAddress.required' => __('The center address is required.'),
            'centerAddress.string'   => __('The address must be valid text.'),
            'centerAddress.max'      => __('The address is too long (maximum 1000 characters).'),
        ];
    }

   

    public function handleSubmit() {
       $this->validate();
       if($this->editCenterId) {
        $center =  Center::find($this->editCenterId);
        $center->update([
            'center_name'    => $this->centerName,
            'center_address' => $this->centerAddress,
        ]);
         $msg = 'Center updated successfully!';
         $this->editCenterId = null;
       }else {
        Center::create([
            'center_name'    => $this->centerName,
            'center_address' => $this->centerAddress,
        ]);
        $msg = 'Center created successfully!';
       }
   
    $this->dispatch('toast', message: $msg, type: 'success');

        $this->dispatch('center-created');
        Flux::modal('center-form')->close();
         // Refresh table
    }

  
    
}
