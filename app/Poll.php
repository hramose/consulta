<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'appointment_id','user_id','name'
    ];

    public function user() //medic
    {
        return $this->belongsTo(User::class);
    }
    public function appointment() 
    {
        return $this->belongsTo(Appointment::class);
    }
    public function questions() 
    {
        return $this->hasMany(Question::class);
    }
    
 
}
