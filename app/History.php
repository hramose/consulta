<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'histories'
    ];

     public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function allergies()
    {
        return $this->hasMany(Allergy::class);
    }
    public function pathologicals()
    {
        return $this->hasMany(Pathological::class);
    }
    public function nopathologicals()
    {
        return $this->hasMany(Nopathological::class);
    }
    public function heredos()
    {
        return $this->hasMany(Heredo::class);
    }
    public function ginecos()
    {
        return $this->hasMany(Gineco::class);
    }
    
}
