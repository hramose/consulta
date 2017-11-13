<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        'date','amount','pending','paid', 'type', 'date','month','year','medic_type'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'date'
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
