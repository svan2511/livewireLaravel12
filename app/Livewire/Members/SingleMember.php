<?php

namespace App\Livewire\Members;

use App\Models\Emi;
use App\Models\Member;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SingleMember extends Component
{
    public Member $member;
    /**
     * Mount the component with route model binding
     */

     public $paid_amounts = [];   // This holds live inputs

    public function mount($member)
    {
        $this->member = $member->load('emis');

        // Pre-fill if already paid something
        foreach ($this->member->emis as $emi) {
            $this->paid_amounts[$emi->id] = $emi->paid_amount ?? 0; // Start at 0 for live entry
        }
    }

    public function markpartiallyPaid($emiId) {

        try {
            
        $amount = (int) $this->paid_amounts[$emiId];
        if ($amount <= 0) {
            $this->dispatch('toast', message: "Please enter some amount!", type: 'error');
            return;
        }
        // Fetch EMI
        $emi = Emi::find($emiId);

        if (!$emi) {
            $this->dispatch('toast', message: "EMI not found!", type: 'error');
            return;
        }

        if ($amount > $emi->inst_amount) {
            $this->dispatch('toast', message: "Amount cannot exceed installment!", type: 'error');
            return;
        }

        $remaining = $emi->inst_amount - $amount;

        // Save paid amount to DB
        $emi->update([
            'paid_amount' => $amount,
            'remain_amount' => $remaining,
            'paid_on'        => now(),
            'status' => 2,
        ]);

        // Reset input field
         $this->paid_amounts[$emiId] = $amount;
        $msg =  "Payment updated successfully! !";
        $type = "success";

        } catch(Exception $ex) {
        Log::info("ERROR:MEM4 : " . $ex->getMessage());
        $msg =  "Some Internal Error !";
        $type = "error";
        }

        $this->dispatch('toast', message: $msg, type: $type);
       
    }

        public function markFullPaid($emiId)
        {

        try {
             $emi = $this->member->emis()->findOrFail($emiId);
            // Prevent double full payment
            if ($emi->status === 1 ) {
                $this->dispatch('toast', [
                    'message' => 'This EMI is already marked as Full Paid!',
                    'type'    => 'warning'
                ]);
                return;
            }

            // Check if due date allows collection (optional – you can remove if needed)
            $dueDate = \Carbon\Carbon::parse($emi->due_date);
            if ($dueDate->isFuture()) {
                $this->dispatch('toast', [
                    'message' => 'Cannot mark future EMI as paid yet!',
                    'type'    => 'error'
                ]);
                return;
            }

            DB::transaction(function () use ($emi) {
                // Update EMI
                $emi->update([
                    'paid_amount'    => $emi->inst_amount,
                    'remain_amount'  => 0,
                    'status'         => 1,
                    'paid_on'        => now(),
                    // 'collected_by'   => Auth::id(), // or Auth::user()->name
                    // 'payment_method' => 'cash',     // or make dynamic later
                ]);
            });

            // Reset the input field after marking full paid
           $this->paid_amounts[$emiId] = $emi->inst_amount;
           $msg = "EMI #{$emi->id} marked as Full Paid (₹" . number_format($emi->inst_amount) . ")";
           $type = "success";

        } catch(Exception $ex) {
        Log::info("ERROR:MEM5 : " . $ex->getMessage());
        $msg =  "Some Internal Error !";
        $type = "error";
        }
            // Success message
         $this->dispatch('toast', 
                message : $msg,
                type  : $type
            );

        }

        public function calculateRemaining($inst,$paid){
           $paid = $paid > 0 ? (int) $paid : 0;
           return $paid < $inst ? (int)  ($inst-$paid) : 0 ;
           
        }

        function daysBetween($dueDate)
        {
            $currentDate = new DateTime();
            $dueDateObj  = new DateTime($dueDate);

            // If overdue → return negative days
            if ($dueDateObj < $currentDate) {
                return -$currentDate->diff($dueDateObj)->days;
            }

            // Upcoming → return positive days
            return $currentDate->diff($dueDateObj)->days;
        }

         function getLabelStatus($dueDate)
        {
           $days = $this->daysBetween($dueDate);  
            // If overdue
            if ($days < 0) {
                return "Overdue";
            }
            // Switch statement
            switch (true) {
                case ($days === 0):
                    return "Due Today";
                case ($days === 1):
                    return "Due Tomorrow";
                case ($days > 7):
                    return (new DateTime($dueDate))->format('d M Y');
                default:
                    return "Due in {$days} days";
            }
        }


    public function render()
    {
        return view('livewire.members.single-member');
    }
}
