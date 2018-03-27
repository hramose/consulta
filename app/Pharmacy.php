<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable = [
        'name','address','province','canton','district','city','phone','ide','ide_name','lat','lon','address_map','notification','notification_date','active'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function assistants()
    {
        return $this->users()->whereHas('roles', function ($query){
                        $query->where('name',  'asistente');
                          
                    })->where('active', 1)->get();
    }
}
