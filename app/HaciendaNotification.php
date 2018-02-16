<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HaciendaNotification extends Model
{
    protected $fillable = [
        'title', 'body', 'user_id','office_id'
    ];
}
