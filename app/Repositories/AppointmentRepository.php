<?php namespace App\Repositories;


use App\Appointment;

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
    public function store($data)
    {
        
        $data = $this->prepareData($data);
        
        $appointment = $this->model->create($data);
        
        $appointment->createDiseaseNotes();
        $appointment->createPhysicalExams();

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
        $appointment = $this->model->findOrFail($id);
        $data = $this->prepareData($data);
        
        if(!$appointment->status)
        {

            $appointment->fill($data);
            $appointment->save();
            
            return $appointment;
        }


        return '';
    }

    /**
     * Delete a appointment
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function delete($id)
    {
        $appointment = $this->model->findOrFail($id);
        
        if(!$appointment->status)
            return $appointment = $appointment->delete();


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

        $appointments = $this->model->where('user_id', $id);
       
        if (! count($search) > 0) return $appointments->orderBy('appointments.'.$order , $dir)->paginate($limit);

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


        return $appointments->orderBy('appointments.'.$order , $dir)->paginate($limit);

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

        if (! count($search) > 0) return $appointments->get();

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


        return $appointments->orderBy('appointments.'.$order , $dir)->get();

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

        if (! count($search) > 0) return $appointments->paginate($this->limit);

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


        return $appointments->orderBy('appointments.'.$order , $dir)->paginate($this->limit);

    }

    private function prepareData($data)
    {
       

        return $data;
    }


}