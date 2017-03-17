<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
     
     protected $fillable = ['slotDuration','minTime','maxTime','freeDays'];
     public $timestamps = false;

     public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setFreeDaysAttribute($freeDays)
    {
        $this->attributes['freeDays'] = json_encode($freeDays);
    }
     public function freeDays()
    {
        return json_decode($this->freeDays);
    }
}
