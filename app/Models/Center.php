<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    use HasFactory;
    protected $fillable = ['center_name','center_address'];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function members(){
        return $this->hasMany(Member::class);
    }
}
