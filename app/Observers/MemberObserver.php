<?php

namespace App\Observers;

use App\Models\Emi;
use App\Models\FinancialSummary;
use App\Models\Member;
use App\Sevices\GetFinacialReport;
use Carbon\Carbon;

class MemberObserver
{
    /**
     * Handle the Member "created" event.
     */
    public function created(Member $member): void
    {
        $startDate = Carbon::parse($member->disb_date);
          // Insert first month entry
        GetFinacialReport::create($member,$startDate->format('Y-m-d'),0);
        for($i=1;$i<=$member->mem_tenor;$i++){
         // IMPORTANT: Use copy() so original date NEVER changes
        $dueDate = $startDate->copy()->addMonths($i);
        Emi::create([
            "member_id" => $member->id,
            "inst_name" => "INSTALL_".$i,
            "inst_amount" => $member->monthly_inst,
            "due_date" => $dueDate->format('Y-m-d'),
        ]);
        GetFinacialReport::create($member,$dueDate->format('Y-m-d'),$i);
       }
      
    }

    /**
     * Handle the Member "updated" event.
     */
    public function updated(Member $member): void
    {
        //
    }

    /**
     * Handle the Member "deleted" event.
     */
    public function deleted(Member $member): void
    {
        //
    }

    /**
     * Handle the Member "restored" event.
     */
    public function restored(Member $member): void
    {
        //
    }

    /**
     * Handle the Member "force deleted" event.
     */
    public function forceDeleted(Member $member): void
    {
        //
    }
}
