<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pmedicine extends Model
{
    protected $fillable = [
        'name', 'date_purchase', 'remember'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
