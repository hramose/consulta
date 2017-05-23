<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $fillable = [
        'user_id','invoices','total'
    ];


    public function medic()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
}
