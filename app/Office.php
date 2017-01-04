<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = [
        'user_id','name','address','province','city','phone','lat','lon'
    ];

     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
