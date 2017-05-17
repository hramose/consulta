<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    
    protected $fillable = [
        'client_name','discount','subtotal','total'
    ];

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function medic()
    {
        return $this->belongsTo(User::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    
}
