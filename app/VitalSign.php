<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VitalSign extends Model
{
    protected $fillable = [
        'height','weight','mass','temp','respiratory_rate','blood','heart_rate'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
