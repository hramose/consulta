<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
     protected $fillable = [
        'question_id','name','rate'
    ];

     public function question() //medic
    {
        return $this->belongsTo(Question::class);
    }
}
