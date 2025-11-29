<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Emi extends Model
{
 protected $fillable = ["id","member_id","inst_name","inst_amount","status","paid_on","paid_amount","remain_amount","due_date"];

   public function getDueStatusAttribute()
    {
        if (!$this->due_date) {
            return '—';
        }

        $dueDate = Carbon::parse($this->due_date);
        $today = Carbon::today();

        if ($dueDate->isToday()) {
            return '<span class="text-orange-600 font-medium">Due Today</span>';
        }

        if ($dueDate->isPast()) {
            $days = $today->diffInDays($dueDate);
            return '<span class="text-red-600 font-medium">Overdue by ' . $days . ' day' . ($days > 1 ? 's' : '') . '</span>';
        }

        $days = $dueDate->diffInDays($today);
        return '<span class="text-green-600 font-medium">Due in ' . $days . ' day' . ($days > 1 ? 's' : '') . '</span>';
    }

}
