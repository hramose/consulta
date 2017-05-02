<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nopathological extends Model
{
     protected $fillable = [
        'user_id','history_id','name','type'
    ];

    public function history()
    {
        return $this->belongsTo(History::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
