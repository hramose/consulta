<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable  = ['plan_id','cost','quantity','ends_at', 'purchase_operation_number'];
    
    protected $dates = ['ends_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->hasOne(Plan::class);
    }

}
