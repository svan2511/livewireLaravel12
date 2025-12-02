<?php

namespace App\Sevices;

use App\Models\FinancialSummary;
use Carbon\Carbon;

class GetFinacialReport
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function create($data,$dueDate,$monthIndex) {
    [$day,$year,$monthName,$monthNumber] = self::extractDateComponents($dueDate);
        $insertedData = [
            'member_id' => $data->id,
            'year' => $year,
            'month_num' => $monthNumber,
            'month_name' => $monthName,
            'disbursement_amount' => $monthIndex === 0 ? $data->disb_amount : 0,
            'collection_amount' => $data->paid_amount ?? 0,
            'demand_amount' => $monthIndex === 0 ?  0 : $data->monthly_inst,
            'outstanding_amount' =>  $data->remain_amount ?? 0
            
        ];

        FinancialSummary::create($insertedData);
    }

     public static function update($data) {
     [$day,$year,$monthName,$monthNumber] = self::extractDateComponents($data->due_date );
        $summery = FinancialSummary::where('member_id',$data->member_id)
                                    ->where('year',$year)
                                    ->where('month_num',$monthNumber)
                                    ->first();
        if($summery) {
            $summery->update([
                'collection_amount' => $data->paid_amount ?? 0 ,
                'outstanding_amount' =>  $data->remain_amount ?? 0
            ]);

            self::nextSummaryUpdate($data);
        }
    }

    public static function extractDateComponents($dateString) {
        $date = Carbon::createFromFormat('Y-m-d', $dateString);
        $day = $date->day;              
        $monthNumber = $date->month;  
        $monthName = $date->format('M');
        $year = $date->year;
        return [$day, $year ,$monthName , $monthNumber];
    }

    private static function nextSummaryUpdate($data) {
      [$day,$year,$monthName,$monthNumber] = self::extractDateComponents($data->due_date );
        $nextMont = (int)$monthNumber === 12 ? 1 : (int)$monthNumber + 1;
        $year = (int)$monthNumber === 12 ? (int)$year + 1 : $year ;
        $nextSummary = FinancialSummary::where('member_id',$data->member_id)
                                    ->where('year',$year)
                                    ->where('month_num',$nextMont)
                                    ->first();
        $nextSummary->incrementQuietly('demand_amount', $data->remain_amount);

    }
}

