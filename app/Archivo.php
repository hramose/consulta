<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    protected $fillable = [
        'name','patient_id','appointment_id', 'description'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
       
    }
   
}
