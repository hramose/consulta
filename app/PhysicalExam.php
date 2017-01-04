<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhysicalExam extends Model
{
    protected $fillable = [
        'cardio','digestivo','urinario','linfatico','dermatologico','neurologico','osteoarticular','otorrinolaringológico','pulmonar','psiquiatrico','reproductor'
    ];

     public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
