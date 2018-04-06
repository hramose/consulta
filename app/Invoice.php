<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'client_name', 'client_email', 'discount', 'subtotal', 'total', 'status', 'pay_with', 'change', 'medio_pago', 'resp_hacienda', 'tipo_documento', 'condicion_venta'
    ];

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

}
