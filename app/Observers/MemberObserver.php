<?php

namespace App\Observers;

use App\Models\Emi;
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
        for($i=1;$i<=$member->mem_tenor;$i++){
        // $currentDate = Carbon::now();
        $startDate = Carbon::parse($member->disb_date);
        $dueDate = $startDate->addMonths($i);
       
        Emi::create([
            "member_id" => $member->id,
            "inst_name" => "INSTALL_".$i,
            "inst_amount" => $member->monthly_inst,
            "due_date" => $dueDate->format('Y-m-d'),
        ]);
       }
       GetFinacialReport::create($member,'member');
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
