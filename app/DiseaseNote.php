<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiseaseNote extends Model
{
    
    protected $fillable = [
        'reason','symptoms','phisical_review'
    ];

     public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
