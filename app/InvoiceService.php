<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceService extends Model
{
     protected $fillable = [
        'user_id','name','amount'
    ];

    protected $appends = array('name_price');

    public function getNamePriceAttribute()
    {
        return $this->name .' - ' .  money($this->amount);  
    }
    
}
