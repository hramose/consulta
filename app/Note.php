<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
     protected $fillable = [
        'user_id', 'appointment_id','description'
     ];


     public function medic()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }


}
