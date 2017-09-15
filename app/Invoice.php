<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    
    protected $fillable = [
        'client_name','discount','subtotal','total','status','pay_with','change'
    ];

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function medic()
    {
        return $this->belongsTo(User::class,'user_id');
    }
     public function clinic()
    {
        return $this->belongsTo(Office::class,'office_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    
}
