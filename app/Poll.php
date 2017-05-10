<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'user_id','name'
    ];

    public function user() //medic
    {
        return $this->belongsTo(User::class);
    }
    public function questions() 
    {
        return $this->hasMany(Question::class);
    }
    
 
}
