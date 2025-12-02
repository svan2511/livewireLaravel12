<?php

namespace App\Livewire\Members;

use App\Models\Center;
use App\Models\Member;
use App\Services\MemberService;
use Exception;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class MemberForm extends Component
{
    use WithFileUploads;

    
    public $buttonText = "Create Member";
    public array $centers = [];
    public $editId = null;   
    public $old_img = null;


      protected MemberService $service;

     public function boot(MemberService $service)
    {
        $this->service = $service;
        $this->centers = Center::pluck('center_name', 'id')->toArray();
    }


    // #[Validate('required|string|max:255')]
    public $mem_name = '';

    // #[Validate('required|string|regex:/^[6-9]\d{9}$/')]
    public $mem_phone = '';

    // #[Validate('required')]
    public $center_id = '';

    // #[Validate('nullable|image|mimes:jpg,jpeg,png,webp|max:2048')]
    public $mem_img;

    // #[Validate('required|numeric|min:1')]
    public $disb_amount = '';

    // #[Validate('required|integer|min:1|max:60')]
    public $mem_tenor = '';

    // #[Validate('required|numeric|min:1')]
    public $monthly_inst = '';

    // #[Validate('required')]
    public $disb_date = '';

   
   
    public function updated($property)
    {
        $this->validateOnly($property, $this->validationRules(), $this->messages());
    }


    public function render()
    {
        // No extra variables required — Livewire exposes public props to the view.
        return view('livewire.members.member-form');
    }

    /**
     * Validation rules that consider edit case for unique phone.
     */
   protected function validationRules(): array
    {
        $phoneRule = 'required|string|regex:/^[6-9]\d{9}$/|unique:members,mem_phone';
        if ($this->editId) {
            $phoneRule .= ',' . $this->editId;
        }

        return [
            'mem_name'     => 'required|string|max:255',
            'mem_phone'    => $phoneRule,
            'center_id'    => 'required|exists:centers,id',

            // Image required only on create
            'mem_img' => $this->editId
                ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
                : 'required|image|mimes:jpg,jpeg,png,webp|max:2048',

            'disb_amount'  => 'required|numeric|min:1',
            'mem_tenor'    => 'required|integer|min:1|max:60',
            'monthly_inst' => 'required|numeric|min:1',
            'disb_date'    => 'required|date',
        ];
    }


    protected function messages(): array
    {
        return [
            'mem_name.required'     => 'Please enter the member name.',
            'mem_phone.required'    => 'Phone number is required.',
            'mem_phone.regex'       => 'Please enter a valid Indian mobile number (10 digits, starts with 6–9).',
            'mem_phone.unique'      => 'This phone number is already registered.',
            'center_id.required'    => 'Please select a center.',
            'mem_img.image'         => 'The file must be an image (JPG, PNG, WebP).',
            'mem_img.mimes'         => 'Only JPG, JPEG, PNG, and WebP images are allowed.',
            'mem_img.max'           => 'Image size cannot exceed 2 MB.',
            'mem_img'               => 'Please select member Image.',
            'disb_amount.required'  => 'Please enter the disbursed amount.',
            'mem_tenor.required'    => 'Please enter the loan tenor.',
            'monthly_inst.required' => 'Please enter the monthly installment.',
            'disb_date.required'    => 'Please select disbursement date.',
        ];
    }

    /** Open create modal */
    #[On('open-member-modal')]
    public function openModal()
    {
        $this->resetForm();
        Flux::modal('member-form')->show();
    }

    /** Close modal */
    #[On('close-modal')]
    public function closeModal()
    {
        Flux::modal('member-form')->close();
    }


    public function handleSubmit()
    {
        $this->validate($this->validationRules(), $this->messages());

        $data = [
            'mem_name'     => $this->mem_name,
            'mem_phone'    => $this->mem_phone,
            'center_id'    => $this->center_id,
            'disb_amount'  => $this->disb_amount,
            'mem_tenor'    => $this->mem_tenor,
            'monthly_inst' => $this->monthly_inst,
            'disb_date'    => $this->disb_date,
        ];

        try{
            // ✅ FIXED IMAGE HANDLING CODE GOES HERE
        if ($this->mem_img && !is_string($this->mem_img)) {

            // new image uploaded
            $data['mem_img'] = $this->mem_img->store('members', 'public');

            // delete old image
            if ($this->old_img) {
                Storage::disk('public')->delete($this->old_img);
            }

        } else {
            // keep existing image
            $data['mem_img'] = $this->old_img;
        }
        // ✅ END FIX

        if ($this->editId) {
            $this->service->updateMember($this->editId, $data);
            $msg = 'Member updated successfully!';
        } else {
          DB::transaction(function () use ($data) {
            $this->service->createMember($data);
        });
            $msg = 'Member created successfully!';
        }

        $type = "success";

        }
        catch(Exception $ex) {
        Log::info("ERROR:MEM12 : " . $ex->getMessage());
        $msg = "Some internal Error !";
        $type = "error";
        }

        $this->dispatch('toast', message: $msg, type: $type);
        $this->dispatch('member-created');
        Flux::modal('member-form')->close();
    }

    /** Edit — populate form and open modal */
    #[On('edit-table')]
    public function editTableData($modal, $row)
    {
        $this->resetForm();

        $this->editId = $row['id'];
        try {
            $member = $this->service->findOrFail($this->editId);
        }
        catch(Exception $ex) {
        Log::info("ERROR:MEM2 : " . $ex->getMessage());
        $this->dispatch('toast', message: "Some internal error !", type: "error");
        }

        $this->mem_name     = $member->mem_name;
        $this->mem_phone    = $member->mem_phone;
        $this->center_id    = $member->center_id;
        $this->disb_amount  = $member->disb_amount;
        $this->mem_tenor    = $member->mem_tenor;
        $this->monthly_inst = $member->monthly_inst;
        $this->disb_date    = $member->disb_date;

        // Store old image path separately
        $this->old_img = $member->mem_img;

        // keep upload field empty
        $this->mem_img = null;

        $this->buttonText = "Update Member";
        Flux::modal($modal)->show();
    }


    /** Delete */
    #[On('delete-table')]
    public function deleteTableData($row)
    {
        try {
        $this->service->deleteMember($row['id']);
        $this->dispatch('member-created');
        $msg =  "Member deleted successfully!";
        $type = "success";
        }
        catch(Exception $ex) {
        Log::info("ERROR:MEM3 : " . $ex->getMessage());
        $msg =  "Some Internal Error !";
        $type = "error";
        }
       $this->dispatch('toast', message: $msg, type: $type);
    }

    /** Reset everything for create */
    private function resetForm()
    {
         $this->reset([
        'mem_name', 'mem_phone', 'center_id', 'mem_img',
        'disb_amount', 'mem_tenor', 'monthly_inst',
        'disb_date', 'editId', 'old_img'
         ]);

        $this->buttonText = "Create Member";
        $this->resetValidation();
        $this->resetErrorBag();
        // centers already loaded in mount()
    }

    /** Installment calculation (unchanged) */
    public function calculateInstallment()
    {
        $a = (int)$this->disb_amount;
        $b = (int)$this->mem_tenor;

        if ($a && $b) {
            $principle = $a / $b;
            $intrst = match ($b) {
                15 => $a * 0.015833,
                18 => $a * 0.016444,
                22 => $a == 150000 ? $a * 0.012212 : $a * 0.016545,
                24 => $a * 0.01833,
                default => 0,
            };
            $this->monthly_inst = ceil($principle + $intrst);
        } else {
            $this->monthly_inst = '';
        }
    }

    public function updatedMemTenor() { $this->calculateInstallment(); }
    public function updatedDisbAmount() { $this->mem_tenor = $this->monthly_inst = ''; }
}
