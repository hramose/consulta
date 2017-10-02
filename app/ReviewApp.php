<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewApp extends Model
{
    protected $table = 'review_apps';
    protected $fillable = [
        'user_id','app','comment'
    ];

      public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
