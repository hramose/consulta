<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
     
     protected $guarded = ['user_id'];
     public $timestamps = false;

     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
