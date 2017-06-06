<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Sugar extends Model
{
     protected $fillable = [
        'date_control','time_control','glicemia'
    ];

     public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function getDateControlAttribute()
    {
        return Carbon::parse($this->attributes['date_control'])->format('Y-m-d');
    }
     public function getTimeControlAttribute()
    {
        return Carbon::parse($this->attributes['time_control'])->toTimeString();
    }
}
