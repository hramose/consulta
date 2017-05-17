<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
	 protected $fillable = [
        'service','amount','quantity','total_line'
    ];
	 
    
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
