<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
     protected $fillable = [
		'first_name', 'last_name', 'birth_date', 'gender', 'phone', 'phone2', 'email', 'address', 'province', 'conditions', 'city','created_by'
	];
    protected $appends = array('fullname','IDhash','photo');



    public function getPhotoAttribute()
    {
        return getPhoto($this);
    }

    public function getFullnameAttribute()
    {
        return $this->first_name. ' ' .$this->last_name;  
    }
     public function getIDhashAttribute()
    {
        return md5($this->id);  
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search)
        {
            $query->where('first_name', 'like', '%' . $search . '%')
               ->orWhere('last_name', 'like', '%' . $search . '%');
        });
    }


     public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
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
    public function labexams()
    {
        return $this->hasMany(Labexam::class);
    }
    public function labresults()
    {
        return $this->hasMany(Labresult::class);
    }
     public function pressures()
    {
        return $this->hasMany(Pressure::class);
    }
     public function sugars()
    {
        return $this->hasMany(Sugar::class);
    }
    public function vitalSigns()
    {
        return $this->hasOne(VitalSign::class);
    }
     public function createHistory($history = null)
    {
        
        $history = ($history) ? $history : new History();
        $history->histories = '';
        /*'{"version":"1.0","alergias":[{"name":"Alergias","value":""}],"patologicos":[{"name":"Hospitalizacion Previa","value":""},{"name":"Cirugías Previas","value":""},{"name":"Diabetes","value":""},{"name":"Enfermedades Tiroideas","value":""},{"name":"Hipertensión Arterial","value":""},{"name":"Cardiopatías","value":""},{"name":"Traumatismos","value":""},{"name":"Cáncer","value":""},{"name":"Tuberculosis","value":""},{"name":"Transfusiones","value":""},{"name":"Otros (patologicos)","value":""}],"no_patologicos":[{"name":"Actividad Física","value":""},{"name":"Tabaquismo","value":""},{"name":"Alcoholismo","value":""},{"name":"Uso de otras sustancias (Drogas)","value":""},{"name":"Otros (No Patológicos)","value":""}],"heredofamiliares":[{"name":"Diabetes (Heredofamiliares)","value":""},{"name":"Cardiopatías (Heredofamiliares)","value":""},{"name":"Hipertensión Arterial (Heredofamiliares)","value":""},{"name":"Enfermedades Tiroideas (Heredofamiliares)","value":""},{"name":"Otros (Heredofamiliares)","value":""}],"gineco_obstetricios":[{"name":"Fecha de primera menstruación","value":""},{"name":"Fecha de última menstruación","value":""},{"name":"Características menstruación","value":""},{"name":"Embarazos","value":""},{"name":"Cáncer Cérvico","value":""},{"name":"Cáncer Uterino","value":""},{"name":"Cáncer de Mama","value":""},{"name":"Otros (Gineco-Obstetricios)","value":""}]}';*/

        return $this->history()->save($history);
    }
     public function createVitalSigns($vitalSigns = null)
    {
        
        $vitalSigns = ($vitalSigns) ? $vitalSigns : new VitalSign();

        return $this->vitalSigns()->save($vitalSigns);
    }
    public function isPatientOf($user)
    {
        if (is_string($user) || is_numeric($user)) {
            return $this->user->contains('id', $user);
        }
        
        return $this->user->contains('id', $user->id);
    }

}
