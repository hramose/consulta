<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $guarded = ['idRemove','office_info','ini','fin'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function office()
    {
        return $this->belongsTo(Office::class);
    }
    /* public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function hasAppointments()
    {
        
        return Appointment::where('schedule_id', $this->id)->count();
    } */
    
}
