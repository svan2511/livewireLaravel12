<?php

namespace App\Livewire\Members;

use App\Models\Center;
use App\Models\Member;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class MemberForm extends Component
{
     public $buttonText = "Create member"; 

     public array $centers = [];

     public $editId = null;
     

     use WithFileUploads;

    #[Validate('required|string|max:255')]
    public $mem_name = '';

    #[Validate('required|string|regex:/^[6-9]\d{9}$/|unique:members,mem_phone')]
    public $mem_phone = '';

    #[Validate('required')]
    public $center_id = '';

    #[Validate('nullable|image|mimes:jpg,jpeg,png,webp|max:2048')] // max 2MB
    public $mem_img;

    #[Validate('required|numeric|min:1')]
    public $disb_amount = '';

    #[Validate('required|integer|min:1|max:60')]
    public $mem_tenor = '';

    #[Validate('required|numeric|min:1')]
    public $monthly_inst = '';

    #[Validate('required')]
    public $disb_date = '';


    public function render()
    {
        return view('livewire.members.member-form');
    }

    public function handleSubmit()
    {
        //$this->validate();
        $member = Member::find($this->editId);
        $data = [
            'mem_name'       => $this->mem_name,
            'mem_phone'      => $this->mem_phone,
            'center_id'      => $this->center_id,
            'disb_amount'    => $this->disb_amount,
            'mem_tenor'      => $this->mem_tenor,
            'monthly_inst'   => $this->monthly_inst,
            'disb_date'      => $this->disb_date
               ];
        if($this->editId ) {
            if(is_string($this->mem_img)) {
            $data['mem_img'] =  $member->mem_img;
            }else {
            $data['mem_img'] =  $this->mem_img->store('members', 'public');
            Storage::disk('public')->delete($member->mem_img);
            }
        $member->update($data);
        $msg= 'Member updated successfully!';
        }
        else 
            {
        // Handle image upload
        if ($this->mem_img) {
            $data['mem_img'] = $this->mem_img->store('members', 'public');
        }
      
        Member::create($data);
        $msg = 'Member created successfully!';
        }

        //$this->dispatch('toast', ['message' => $msg, 'type' => 'success']);
        $this->dispatch('toast', message: $msg, type: 'success');
        $this->dispatch('member-created');
        Flux::modal('member-form')->close();

        
    }

    #[On('open-member-modal')]
    public function openModalnnFunction() { 
       $this->reset();
      $this->centers = Center::pluck('center_name', 'id')->toArray();
      $this->resetValidation();
      $this->resetErrorBag();
      Flux::modal('member-form')->show();
    }

    protected function messages(): array
{
    return [
        // Member Name
        'mem_name.required'     => 'Please enter the member name.',
        'mem_name.string'       => 'Member name must be valid text.',
        'mem_name.max'          => 'Member name cannot exceed 255 characters.',

        // Phone Number
        'mem_phone.required'    => 'Phone number is required.',
        'mem_phone.regex'       => 'Please enter a valid Indian mobile number (10 digits, starts with 6–9).',
        'mem_phone.unique'      => 'This phone number is already registered. Please use a different one.',

        // Center
        'center_id.required'    => 'Please select a center.',
        // 'center_id.exists'      => 'The selected center is invalid or no longer exists.',

        // Image
        'mem_img.image'         => 'The file must be an image (JPG, PNG, WebP).',
        'mem_img.mimes'         => 'Only JPG, JPEG, PNG, and WebP images are allowed.',
        'mem_img.max'           => 'Image size cannot exceed 2 MB.',

        // Loan Details
        'disb_amount.required'  => 'Please enter the disbursed amount.',
        'disb_amount.numeric'   => 'Disbursed amount must be a valid number.',
        'disb_amount.min'       => 'Disbursed amount must be at least ₹1.',

        'mem_tenor.required'    => 'Please enter the loan tenor.',
        'mem_tenor.integer'     => 'Tenor must be a whole number.',
        'mem_tenor.min'         => 'Tenor must be at least 1 month.',
        'mem_tenor.max'         => 'Tenor cannot exceed 60 months (5 years).',

        'monthly_inst.required' => 'Please enter the monthly installment.',
        'monthly_inst.numeric'  => 'Monthly installment must be a valid number.',
        'monthly_inst.min'      => 'Monthly installment must be at least ₹1.',
    ];
}

        #[On('edit-table')]
       public function editTableData($table, $row)
        {
            $this->editId = $row['id'];
            $member = Member::findOrFail($row['id']);
            $this->centers = Center::pluck('center_name', 'id')->toArray();
            $this->mem_name      = $member->mem_name;
            $this->mem_phone     = $member->mem_phone;
            $this->center_id     = $member->center_id;
            $this->disb_amount   = $member->disb_amount;
            $this->mem_tenor     = $member->mem_tenor;
            $this->monthly_inst  = $member->monthly_inst;
            $this->buttonText = "Update Member";
            // Image will remain null until re-upload
            $this->mem_img = $member->mem_img;

            // Open modal
            Flux::modal($table)->show();
        }

         #[On('close-modal')]
        public function closeModalFunction() {
            Flux::modal('member-form')->close();
        }

         #[On('delete-table')]
        public function deleteTableData( $row) {
        $member = Member::find($row['id']);
        $member->delete();
        $this->dispatch('member-created');
        $this->dispatch('toast', message: "Member delete Successfully !", type: 'success');

        }

        function calculateInstallment()
         {
            $a = (int) $this->disb_amount;
            $b = (int) $this->mem_tenor;
            $principleAmount = $a / $b;
            $intrstAmount = 0;
        if ($a) {
            switch ($b) {
                case 15:
                    $intrstAmount = $a * 0.015833;
                    break;
                case 18:
                    $intrstAmount = $a * 0.016444;
                    break;
                case 22:
                    if ($a == 150000) {
                        $intrstAmount = $a * 0.012212;
                    } else {
                        $intrstAmount = $a * 0.016545;
                    }
                    break;
                case 24:
                    $intrstAmount = $a * 0.01833;
                    break;
                default:
                    $intrstAmount = 0;
            }
        }

       $totalInstallment = ceil($principleAmount + $intrstAmount);
       $this->monthly_inst = $totalInstallment;
    }

    public function updatedMemTenor() {
        $this->calculateInstallment();
    }

    public function updatedDisbAmount()
    {
         $this->mem_tenor =  $this->monthly_inst  = '';
        //$this->calculateInstallment();
    }

   

}
