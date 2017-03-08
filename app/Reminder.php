<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
     
     protected $fillable  = ['appointment_id','reminder_time'];



    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

}
