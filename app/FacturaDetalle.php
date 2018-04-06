<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturaDetalle extends Model
{
    protected $fillable = [
        'name', 'amount', 'quantity', 'total_line'
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}
