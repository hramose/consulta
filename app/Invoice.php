<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'client_name', 'client_email', 'discount', 'subtotal', 'total', 'status', 'pay_with', 'change', 'clave_fe', 'status_fe', 'medio_pago', 'resp_hacienda', 'fe', 'sent_to_hacienda', 'consecutivo_hacienda', 'created_xml','tipo_documento','condicion_venta'
    ];

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class);
    }
    
    public function documentosReferencia()
    {
        return $this->hasMany(DocumentoReferencia::class);
    }

    public function medic()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Office::class, 'office_id');
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
