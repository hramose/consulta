<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VitalSign extends Model
{
    protected $fillable = [
        'height','weight','mass','temp','respiratory_rate','blood_ps','blood_pd','heart_rate'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
