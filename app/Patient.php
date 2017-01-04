<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
     protected $fillable = [
		'first_name', 'last_name', 'birth_date', 'gender', 'phone', 'phone2', 'email', 'address', 'province', 'city'
	];

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search)
        {
            $query->where('first_name', 'like', '%' . $search . '%')
               ->orWhere('last_name', 'like', '%' . $search . '%');
        });
    }



	 public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function history()
    {
        return $this->hasOne(History::class);
    }
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
    public function vitalSigns()
    {
        return $this->hasOne(VitalSign::class);
    }
     public function createHistory($history = null)
    {
        
        $history = ($history) ? $history : new History();
        $history->histories = '';

        return $this->history()->save($history);
    }
     public function createVitalSigns($vitalSigns = null)
    {
        
        $vitalSigns = ($vitalSigns) ? $vitalSigns : new VitalSign();

        return $this->vitalSigns()->save($vitalSigns);
    }

}
