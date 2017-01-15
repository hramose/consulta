<?php namespace App\Repositories;


use App\Medicine;
use App\Patient;
use Illuminate\Support\Facades\Storage;

class PatientRepository extends DbRepository{


    /**
     * Construct
     * @param User $model
     */
    function __construct(Patient $model)
    {
        $this->model = $model;
        $this->limit = 10;
    }

    /**
     * save a patient
     * @param $data
     */
    public function store($data)
    {
        
        $data = $this->prepareData($data);
       
        $patient = $this->model->create($data);
        
        $patient->createHistory();
        $patient->createVitalSigns();
        
        $patient = auth()->user()->patients()->save($patient);
      

        return $patient;
    }

    /**
     * Update a patient
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function update($id, $data)
    {
        $patient = $this->model->findOrFail($id);
        $data = $this->prepareData($data);

        $patient->fill($data);
        $patient->save();


        return $patient;
    }
    /**
     * Update a history patient
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function updateHistory($id, $data)
    {
        $patient = $this->model->findOrFail($id);
        
        $history = $patient->history;
        
        $history->histories = $data;
       
        $history->save();


        return $history;
    }

    /**
     * Add medicine to user
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function addMedicine($id, $data)
    {
       
        $medicine = Medicine::create($data);
     
        return $medicine;
    }
    /**
     * Delete medicine to user
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function deleteMedicine($id)
    {
        
        $medicine = Medicine::findOrFail($id)->delete();
     
        return $medicine;
    }
    /**
     * Delete patient
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function delete($id)
    {
        
       // $patient = $this->model->findOrFail($id)->delete();

        $patient = $this->model->findOrFail($id);

        
        if(!$patient->appointments->count())
        {
            if($patient->created_by == auth()->id())
            {
                return $patient = $patient->delete();
            }

            $patient = auth()->user()->patients()->detach($id); 

            return true;
        }

     
        return $patient;
    }

    

    /**
     * Find all the patients for the admin panel
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAll($search = null)
    {
        $order = 'created_at';
        $dir = 'desc';

        $patients = auth()->user()->patients();

        if (! count($search) > 0) return $patients->paginate($this->limit);

        if (trim($search['q']))
        {
            $patients = $patients->Search($search['q']);
        } 


        if (isset($search['order']) && $search['order'] != "")
        {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != "")
        {
            $dir = $search['dir'];
        }


        return $patients->orderBy('users.'.$order , $dir)->paginate($this->limit);

    }

    /**
     * List of patients for the appointments form
     */
   public function list($search = null)
   {
        $patients = [];
        
        if(!$search) return $patients;

        $patients = auth()->user()->patients()->search($search)->paginate(10);

        return $patients;
    }

    private function prepareData($data)
    {
        
        $data['created_by'] = auth()->id();

        return $data;
    }


}