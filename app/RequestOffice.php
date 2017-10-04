<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestOffice extends Model
{
    protected $fillable = [
        'name','phone','address'
    ];

     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
