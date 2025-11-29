<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialSummary extends Model
{
    protected $fillable = [
        'member_id',
        'year',
        'month_num',
        'month_name',
        'disbursement_amount',
        'collection_amount',
        'demand_amount',
        'outstanding_amount'
    ];
}
