<?php

namespace App\Livewire\Centers;

use App\Services\CenterService;
use Exception;
use Flux\Flux;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CenterForm extends Component
{
    public function boot(CenterService $service)
    {
        $this->service = $service;
    }

    #[Validate('required|string|max:255')]
    public $centerName;

    #[Validate('required|string|max:255')]
    public $centerAddress;

    public $editCenterId = null;
    public $buttonText = "Create Center";

    public function render()
    {
        return view('livewire.centers.center-form');
    }

    /* --------------------
     | OPEN / CLOSE MODAL
     -------------------- */
    #[On('open-form-modal')]
    public function openModal()
    {
        $this->reset(['centerName', 'centerAddress', 'editCenterId']);
        $this->buttonText = "Create Center";
        $this->resetValidation();
        Flux::modal('center-form')->show();
    }

    #[On('close-modal')]
    public function closeModal()
    {
        Flux::modal('center-form')->close();
    }

    /* --------------------
     | EDIT FORM
     -------------------- */
    #[On('edit-table')]
    public function edit($modal, $row)
    {
        $this->centerName = $row['center_name'];
        $this->centerAddress = $row['center_address'];
        $this->editCenterId = $row['id'];

        $this->buttonText = "Update Center";

        $this->resetValidation();
        Flux::modal($modal)->show();
    }

    /* --------------------
     | DELETE
     -------------------- */
    #[On('delete-table')]
    public function delete($row)
    {
       try{
        $this->service->delete($row['id']);
        $this->dispatch('center-created');
        $msg = "Center deleted successfully!";
        $type = "success";
       }
       catch(Exception $ex) {
        Log::info("ERROR:CENTER1 : " . $ex->getMessage());
        $msg = "Some internal Error !";
        $type = "error";
       }
        $this->dispatch(
            'toast',
            message: $msg,
            type: $type
        );
    }

    /* --------------------
     | CUSTOM MESSAGES
     -------------------- */
    protected function messages(): array
    {
        return [
            'centerName.required'    => 'Center name is required',
            'centerAddress.required' => 'Center address is mandatory!',
        ];
    }

    /* --------------------
     | REAL-TIME VALIDATION
     -------------------- */
    public function updated($field)
    {
        $this->validateOnly($field); // removes only specific field error
    }

    /* --------------------
     | CREATE / UPDATE
     -------------------- */
    public function handleSubmit()
    {
        $this->validate();

        try{
            $payload = [
            'center_name'    => $this->centerName,
            'center_address' => $this->centerAddress,
        ];

        if ($this->editCenterId) {
            $this->service->update($this->editCenterId, $payload);
            $msg = "Center updated successfully!";
        } else {
            $this->service->create($payload);
            $msg = "Center created successfully!";
        }
        $type ="success";                        
        $this->dispatch('center-created');
        }
        catch(Exception $ex){
        Log::info("ERROR:CENTER2 : " . $ex->getMessage());
        $msg = "Some internal Error !";
        $type = "error";
        }
        $this->dispatch('toast', message: $msg, type: $type);
        Flux::modal('center-form')->close();
    }
}
