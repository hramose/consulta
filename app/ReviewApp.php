<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewApp extends Model
{
    protected $table = 'review_apps';
    protected $fillable = [
        'app','comment'
    ];
}
