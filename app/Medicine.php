<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name','medic_id'
    ];

     public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
