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

    public static function create($data,$type) {
    [$day,$year,$monthName,$monthNumber] = self::extractDateComponents($data->disb_date ?? $data->due_date );
        $insertedData = [
            'member_id' => $type === 'member' ? $data->id  : $data->member_id,
            'year' => $year,
            'month_num' => $monthNumber,
            'month_name' => $monthName,
            'disbursement_amount' => $data->disb_amount ?? 0,
            'collection_amount' => $data->paid_amount ?? 0,
            'demand_amount' => $data->inst_amount ?? 0,
            'outstanding_amount' =>  $data->remain_amount ?? 0
            
        ];

        FinancialSummary::create($insertedData);
    }

    public static function extractDateComponents($dateString) {
        $date = Carbon::createFromFormat('Y-m-d', $dateString);
        $day = $date->day;              
        $monthNumber = $date->month;  
        $monthName = $date->format('M');
        $year = $date->year;
        return [$day, $year ,$monthName , $monthNumber];
    }
}

