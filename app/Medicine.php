<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'patient_id','name'
    ];

     public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
