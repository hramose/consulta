<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token','speciality_id'
    ];
    protected $appends = array('distance');

    public function getDistanceAttribute()
    {
        return 0;  
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search)
        {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }


    /**
     * A user may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Assign the given role to the user.
     *
     * @param  string $role
     * @return mixed
     */
    public function assignRole($role)
    {
        if (is_object($role)) {
            return $this->roles()->attach($role);
       
        }
       
        return $this->roles()->sync($role);
       
    }

    /**
     * Determine if the user has the given role.
     *
     * @param  mixed $role
     * @return boolean
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return !! $role->intersect($this->roles)->count();
    }


    public function specialities()
    {
        return $this->belongsToMany(Speciality::class);
    }

    /**
     * Assign the given role to the user.
     *
     * @param  string $role
     * @return mixed
     */
    public function assignSpeciality($speciality)
    {
        if (is_object($speciality)) {
            return $this->specialities()->attach($speciality);
       
        }
       
        return $this->specialities()->sync($speciality);
       
    }

    /**
     * Determine if the user has the given role.
     *
     * @param  mixed $role
     * @return boolean
     */
    public function hasSpeciality($speciality)
    {
        if (is_string($speciality)) {
            return $this->specialities->contains('name', $speciality);
        }

        return !! $speciality->intersect($this->specialities)->count();
    }

    public function patients()
    {
        return $this->belongsToMany(Patient::class);
    }

    public function offices()
    {
        return $this->hasMany(Office::class);
    }


    public function createOffice($office = null)
    {
        $office = ($office) ? $office : new Office();

        return $this->offices()->save($office);
    }

    public function getSpecialityName()
    {
        return $this->speciality_id != 0 ? Speciality::find($this->speciality_id)->name : '';
    }

     public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function settings()
    {
        return $this->hasOne(Setting::class);
    }
    /**
     * create a setting to user
     * @param null $profile
     * @return mixed
     */
    public function createSettings($setting = null)
    {
        $setting = ($setting) ? $setting : new Setting();

        return $this->settings()->save($setting);
    }

    public function appointmentsToday()
    {
        
         //dd(Appointment::where('created_by', $this->id)->whereDate('created_at', Carbon::Now()->toDateString())->count());
        
        return Appointment::where('created_by', $this->id)->whereDate('created_at', Carbon::Now()->toDateString())->count();
    } 
    
 
  
}
