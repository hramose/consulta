<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diagnostic extends Model
{
     
    protected $fillable = [
        'appointment_id','name','comments'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
