<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'client_name', 'client_email', 'discount', 'subtotal', 'total', 'status', 'pay_with', 'change', 'clave_fe', 'status_fe', 'medio_pago', 'resp_hacienda', 'sent_to_hacienda', 'consecutivo_hacienda', 'created_xml', 'tipo_documento', 'condicion_venta', 'obligado_tributario_id','user_id','office_id', 'consecutivo','fe'
    ];

    protected $appends = ['tipo_documento_name'];

    public function getTipoDocumentoNameAttribute()
    {
       
        return trans('utils.tipo_documento.' . $this->tipo_documento);
    }
   

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class);
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

    public function saveDetails($services)
    {
        $totalInvoice = 0;

        foreach ($services as $service) {

            $totalLine = 1 * $service['amount'];
            $totalInvoice += $totalLine;

            $this->lines()->create(
                [
                    'name' => $service['name'],
                    'amount' => $service['amount'],
                    'quantity' => 1,
                    'total_line' => $totalLine,
                ]

            );


        }

        $this->subtotal = $totalInvoice;
        $this->total = $totalInvoice;
        $this->save();

        return $this;
    }

    public function saveReferencias($referencias)
    {
        foreach ($referencias as $ref) {
            
            $this->documentosReferencia()->create(
                [
                    'tipo_documento' => $ref['tipo_documento'],
                    'numero_documento' => $ref['numero_documento'],
                    'fecha_emision' => $ref['fecha_emision'],
                    'codigo_referencia' => $ref['codigo_referencia'],
                    'razon' => $ref['razon'],
                ]

            );

        }

        $this->save();

        return $this;

    }

}
