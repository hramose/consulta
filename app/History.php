<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'histories'
    ];

     public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    
}
