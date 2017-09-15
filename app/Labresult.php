<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Labresult extends Model
{
    protected $fillable = [
        'date','name','labexam_id','url', 'description'
    ];

    public function labexam()
    {
        return $this->belongsTo(Labexam::class);
       
    }


}
