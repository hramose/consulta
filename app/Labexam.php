<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Labexam extends Model
{
    protected $fillable = [
        'date','name','patient_id', 'appointment_id', 'description'
    ];

     public function results()
    {
        return $this->hasMany(LabResult::class);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
