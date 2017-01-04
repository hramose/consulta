<?php

namespace App;

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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


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

    public function patients()
    {
        return $this->belongsToMany(Patient::class);
    }

    public function office()
    {
        return $this->hasOne(Office::class);
    }


    public function createOffice($office = null)
    {
        $office = ($office) ? $office : new Office();

        return $this->office()->save($office);
    }

    public function getSpecialityName()
    {
        return $this->speciality_id != 0 ? Speciality::find($this->speciality_id)->name : '';
    }

     public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    
 
  
}
