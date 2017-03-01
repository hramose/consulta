<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
     
     protected $fillable = ['slotDuration','minTime','maxTime'];
     public $timestamps = false;

     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
