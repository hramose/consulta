<?php namespace App\Repositories;


use App\Appointment;
use App\Patient;
use App\User;

class AppointmentRepository extends DbRepository{


    /**
     * Construct
     * @param User $model
     */
    function __construct(Appointment $model)
    {
        $this->model = $model;
        $this->limit = 10;
    }

    /**
     * save a appointment
     * @param $data
     */
    public function store($data, $user_id = null)
    {
       
        
        $medic = User::find( ($user_id) ? $user_id : auth()->id() ); // buscar doctor

        $data['created_by'] = auth()->id(); // asignar el usuario que creo la cita, ya sea doctor o paciente

        $patient = Patient::find( $data['patient_id'] );
        
        if($patient)
        {
            $appointment = $medic->appointments()->create($data); //$this->model->create($data);
            $appointment->createDiseaseNotes();
            $appointment->createPhysicalExams();

            $appointment->patient()->associate($patient); // asociar la cita con el paciente
            $appointment->save();

        }else{

            if($data['patient_id']) // verificar si lo que viene es 0 o un numero que no existe por lo cual enviar mensaje de error o crear la cita como background
            {     
             
               return false;
            
            }

            $appointment = $medic->appointments()->create($data); //$this->model->create($data);
            $appointment->createDiseaseNotes();
            $appointment->createPhysicalExams();
            
        }

       


        return $appointment;
        
    }

    /**
     * Update a appointment
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function update($id, $data)
    {
        $appointment = $this->model->find($id);
        //$data = $this->prepareData($data);
        if(!$appointment) return '';

         if(auth()->user()->hasRole('paciente')){

            if($appointment->isStarted() || $appointment->isBackgroundEvent() || !$appointment->isOwner() )
            {
               return '';
            }

        }else{

            if($appointment->isStarted() && isset($data['date']))
            {
               return '';
            }
        }


        $appointment->fill($data);
        $appointment->save();

        $patient = Patient::find( isset($data['patient_id']) ? $data['patient_id'] : 0 );
        
        if($patient)
        {

            $appointment->patient()->associate($patient); // asociar la cita con el paciente
            $appointment->save();

        }
            
        return $appointment;

        
    }

    /**
     * Delete a appointment
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function delete($id)
    {
        $appointment = $this->model->find($id);
        
        if(!$appointment) return -1;

        if(auth()->user()->hasRole('paciente')){

            if( !$appointment->isStarted() && $appointment->isOwner() )
                return $appointment = $appointment->delete();
        }else{

            if( !$appointment->isStarted() )
                return $appointment = $appointment->delete();
        }


        return $appointment;
    }


    /**
     * Find all the appointments by Doctor
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAllByDoctor($id, $search = null, $limit = 5)
    {
        $order = 'date';
        $dir = 'desc';

        $appointments = $this->model->where('user_id', $id)->where('patient_id','<>',0);
       
        if (! count($search) > 0) return $appointments->with('user','patient')->orderBy('appointments.'.$order , $dir)->paginate($limit);

        if (isset($search['q']) && trim($search['q'] != ""))
        {
            
            $appointments = $appointments->Search($search['q']);
        }

        if (isset($search['date']) && $search['date'] != "")
        {

            $appointments = $appointments->whereDate('date', $search['date']);
        }


        if (isset($search['order']) && $search['order'] != "")
        {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != "")
        {
            $dir = $search['dir'];
        }


        return $appointments->with('user','patient')->orderBy('appointments.'.$order , $dir)->paginate($limit);

    }
    /**
     * Find all the appointments by Doctor
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAllByDoctorWithoutPagination($id, $search = null)
    {
        $order = 'created_at';
        $dir = 'desc';

        $appointments = $this->model->where('user_id', $id);

        if (! count($search) > 0) return $appointments->with('patient','user')->get();

        if (trim($search['q']))
        {
            $appointments = $appointments->Search($search['q']);
        } 


        if (isset($search['order']) && $search['order'] != "")
        {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != "")
        {
            $dir = $search['dir'];
        }


        return $appointments->with('patient', 'user')->orderBy('appointments.'.$order , $dir)->get();

    }
    /**
     * Find all the appointment by Patient
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAllByPatient($id, $search = null)
    {
        $order = 'created_at';
        $dir = 'desc';

        $appointments = $this->model->where('patient_id', $id);

        if (! count($search) > 0) return $appointments->with('user')->paginate($this->limit);

        if (trim($search['q']))
        {
            $appointments = $appointments->Search($search['q']);
        } 


        if (isset($search['order']) && $search['order'] != "")
        {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != "")
        {
            $dir = $search['dir'];
        }


        return $appointments->with('user')->orderBy('appointments.'.$order , $dir)->paginate($this->limit);

    }

   /*   public function findById($id)
    {
        return $this->model->with('diseaseNotes')->findOrFail($id);
    }*/

    private function prepareData($data)
    {
       

        return $data;
    }


}