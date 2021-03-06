<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    protected $fillable = [
        'name', 'amount', 'quantity', 'total_line'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
