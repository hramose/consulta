<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'poll_id','name'
    ];

    public function poll() 
    {
        return $this->belongsTo(Poll::class);
    }
    public function answers() //medic
    {
        return $this->hasMany(Answer::class);
    }
}
