<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceService extends Model
{
     protected $fillable = [
        'name','amount'
    ];
    
}
