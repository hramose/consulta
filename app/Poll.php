<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'appointment_id','medical_care','treatment','satisfaction','completed'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
 
}
