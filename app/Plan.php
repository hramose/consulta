<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    
       protected $fillable  = ['title','description','cost','quantity'];

    public function scopeSearch($query, $search)
    {
        if($search){

             return $query->where(function ($query) use ($search)
            {
                $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('cost', 'like', '%' . $search . '%');
                    
            });
        }

        
        return $query;
       
    }
}
