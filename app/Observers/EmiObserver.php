<?php

namespace App\Observers;

use App\Models\Emi;
use App\Sevices\GetFinacialReport;

class EmiObserver
{
    /**
     * Handle the Emi "created" event.
     */
    public function created(Emi $emi): void
    {
        //
    }

    /**
     * Handle the Emi "updated" event.
     */
    public function updated(Emi $emi): void
    {
       if( $emi->remain_amount != 0 ) {
            $findId = $emi->id + 1 ;
            $emiToUpdate = Emi::where('id',$findId)->where('member_id' , $emi->member_id)->first();
            if($emiToUpdate) {
            $emiToUpdate->incrementQuietly('inst_amount', $emi->remain_amount);
            }
       }

       GetFinacialReport::update($emi);
    }

    /**
     * Handle the Emi "deleted" event.
     */
    public function deleted(Emi $emi): void
    {
        //
    }

    /**
     * Handle the Emi "restored" event.
     */
    public function restored(Emi $emi): void
    {
        //
    }

    /**
     * Handle the Emi "force deleted" event.
     */
    public function forceDeleted(Emi $emi): void
    {
        //
    }
}
