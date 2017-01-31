<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'created_by','date','start','end','allDay','title','backgroundColor','borderColor','medical_instructions'
    ];

     public function scopeSearch($query, $search)
    {
        
        return $query->where(function ($query) use ($search)
        {
            $query->where('title', 'like', '%' . $search . '%');
                  
        });
    }
   
    public function isStarted()
    {
        return $this->status == 1;
    }
    public function isBackgroundEvent()
    {
        return $this->patient_id == 0;
    }

    public function isOwner() 
    {
        return $this->created_by == auth()->id();
    }

     public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
     public function diseaseNotes()
    {
        return $this->hasOne(DiseaseNote::class);
    }
     public function physicalExams()
    {
        return $this->hasOne(PhysicalExam::class);
    }
     public function diagnostics()
    {
        return $this->hasMany(Diagnostic::class);
    }

     public function createDiseaseNotes($diseaseNotes = null)
    {
        
        $diseaseNotes = ($diseaseNotes) ? $diseaseNotes : new DiseaseNote();
        

        return $this->diseaseNotes()->save($diseaseNotes);
    }
     public function createPhysicalExams($physicalExams = null)
    {
        
        $physicalExams = ($physicalExams) ? $physicalExams : new PhysicalExam();
        

        return $this->physicalExams()->save($physicalExams);
    }
}
