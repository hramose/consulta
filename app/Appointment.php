<?php

namespace App;

use App\Answer;
use App\Poll;
use App\Question;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'created_by','date','start','end','allDay','title','backgroundColor','borderColor','medical_instructions','office_info','office_id','tracing','visible_at_calendar'
    ];
     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'date'
    ];

     public function scopeSearch($query, $search)
    {
        
        return $query->where(function ($query) use ($search)
        {
            $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
                  
        });
    }
   
    public function isStarted()
    {
        return $this->status != 0;
    }
    public function isFinished()
    {
        return $this->finished == 1;
    }
    public function isBackgroundEvent()
    {
        return $this->patient_id == 0;
    }

    public function isOwner($user = null) 
    {
        return $this->created_by == ($user) ? $user->id : auth()->id();
    }

     public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
        public function office()
    {
        return $this->belongsTo(Office::class);
    }
    /* public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }*/
     public function diseaseNotes()
    {
        return $this->hasOne(DiseaseNote::class);
    }
     public function physicalExams()
    {
        return $this->hasOne(PhysicalExam::class);
    }
    public function labexams()
    {
        return $this->belongsToMany(Labexam::class);
    }
    
     public function diagnostics()
    {
        return $this->hasMany(Diagnostic::class);
    }
      public function treatments()
    {
        return $this->hasMany(Treatment::class);
    }
     public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }
      public function notes()
    {
        return $this->hasMany(Note::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
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

     public function createPoll($type = null)
    {
        
        $poll = new Poll();
        $poll->user_id = $this->user->id;
        $poll->appointment_id = $this->id;
        $poll->name = ($type) ? $type : 'medico';
        $poll->save();
        //$user = $this->polls()->save($poll);


        $poll->questions()->saveMany([
            new Question(['name' => 'Cómo considera el servicio de atención médica recibida?']),
            new Question(['name' => 'La respuesta del paciente tras el tratamiento estuvo a la altura de sus expectativas?']),
            new Question(['name' => 'Califique con estrellas su nivel de satisfacción tras la atención de la consulta']),
            
        ]);

        foreach ($poll->questions as $q) {

            $q->answers()->saveMany([
               new Answer(['name' => 'Excelente']),
               new Answer(['name' => 'Muy Bueno']),
               new Answer(['name' => 'Regular']),
               new Answer(['name' => 'Malo'])
                
            ]);
        }

            

    

        return $poll;
    }
    
}
