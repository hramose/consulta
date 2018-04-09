<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentoReferencia extends Model
{
     protected $guarded = [];
    
     protected $appends = ['tipo_documento_name', 'codigo_referencia_name'];

    public function getTipoDocumentoNameAttribute()
    {

        return trans('utils.tipo_documento.' . $this->tipo_documento);
    }

    public function getCodigoReferenciaNameAttribute()
    {

        return trans('utils.codigo_referencia.' . $this->codigo_referencia);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    
}
