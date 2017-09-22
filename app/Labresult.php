<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Labresult extends Model
{
    protected $fillable = [
        'date','name','patient_id','url', 'description'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
       
    }


}
