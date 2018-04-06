<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
     protected $fillable = [
        'client_name', 'client_email', 'discount', 'subtotal', 'total', 'status', 'pay_with', 'change', 'clave_fe', 'status_fe', 'medio_pago', 'resp_hacienda', 'sent_to_hacienda', 'consecutivo_hacienda', 'created_xml', 'tipo_documento', 'condicion_venta', 'obligado_tributario_id'
    ];

    public function lines()
    {
        return $this->hasMany(FacturaDetalle::class);
    }

    public function documentosReferencia()
    {
        return $this->hasMany(DocumentoReferencia::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }


    public function obligadoTributario()
    {
        return $this->belongsTo(ConfigFactura::class, 'obligado_tributario_id');
    }
}
