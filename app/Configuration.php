<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
     protected $fillable = ['amount_general','amount_specialist'];
     public $timestamps = false;

   
}
