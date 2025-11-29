<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    
    protected $fillable = ['mem_name','mem_img','center_id' ,'disb_amount','mem_tenor' ,'monthly_inst' ,'mem_phone','disb_date'];

    public function center(){
        return $this->belongsTo(Center::class)->select('id', 'center_name')->withDefault(null);
    }

    public function emis(){
        return $this->hasMany(Emi::class,'member_id','id');
    }

  
}
