<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfigFactura extends Model
{
    protected $fillable = ['nombre', 'nombre_comercial', 'tipo_identificacion', 'identificacion', 'sucursal', 'pos', 'codigo_pais_tel', 'telefono', 'codigo_pais_fax', 'fax', 'provincia', 'canton', 'distrito', 'barrio', 'otras_senas', 'email', 'consecutivo_inicio', 'atv_user', 'atv_password', 'pin_certificado', 'consecutivo_inicio_ND', 'consecutivo_inicio_NC'];

    public $timestamps = false;

    public function facturable()
    {
        return $this->morphTo();
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
