<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Labexam extends Model
{
    protected $fillable = [
        'date','name','patient_id', 'description'
    ];

     public function results()
    {
        return $this->hasMany(Labresult::class);
    }
    public function appointments()
    {
        return $this->belongsToMany(Appointment::class);
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
