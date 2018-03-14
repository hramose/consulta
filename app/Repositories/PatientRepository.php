<?php namespace App\Repositories;


use App\Medicine;
use App\Patient;
use App\User;
use Carbon\Carbon;
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
    public function store($data, $user = null )
    {
        
        $data = $this->prepareData($data);
       
        $patient = $this->model->create($data);
        
        $patient->createHistory();
        //$patient->createVitalSigns(); ya no se ocupa por que se hizo como un historial en citas y pacientes
        
        $patient = ($user) ? $user->patients()->save($patient) : auth()->user()->patients()->save($patient);
      

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
        $patient = $this->model->findOrFail($id);
        
        $medicine = $patient->medicines()->create($data);
     
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
        
        //dd($patient->created_by == auth()->id());
        
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
    public function findAllOfClinic($clinic, $search = null)
    {
        $order = 'created_at';
        $dir = 'desc';

        $userIds = $clinic->users()->pluck('users.id');
        
        $patients = $this->model;
        
        if(isset($search['q']) && trim($search['q']))
        {
           $patients = $patients->Search($search['q']);
        
        }
           //$patients = auth()->user()->patients();
                
            $patients = $patients->whereHas('user', function ($query) use($userIds) {
                $query->whereIn('users.id', $userIds);
            });
            
            /*$items =  User::selectRaw('users.id as createdby, patients.*')
            ->join('patient_user', 'patient_user.user_id', '=', 'users.id')
            ->join('patients', 'patients.id', '=', 'patient_user.patient_id')
            ///->with('estados.user')
            //->with('banco')
            ->whereIn('users.id',$userIds)
            ->orderBy($order, $dir)
            ->get();
        
            dd($items->all());
            $paginator = paginate($items->all(), $this->limit);
              
            return $paginator;*/
       

        


        return $patients->with('appointments')->orderBy('patients.'.$order , $dir)->paginate($this->limit);

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
        
        if(isset($search['q']) && trim($search['q']))
        {
           $patients = $this->model;
        
        }else{
           $patients = auth()->user()->patients();
        }

        if (!$search) return $patients->with('appointments')->paginate($this->limit);

        if (isset($search['q']) && trim($search['q']))
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


        return $patients->with('appointments')->orderBy('patients.'.$order , $dir)->paginate($this->limit);

    }

    public function findById($id)
    {
        return $this->model->with('vitalSigns','medicines','history.allergies.user','history.pathologicals.user','history.nopathologicals.user','history.heredos.user','history.ginecos.user')->findOrFail($id);
    }

    /**
     * List of patients for the appointments form
     */
   public function list($search = null, $user = null)
   {
        $patients = [];
        
        if(!$search) return $patients;

        $patients = ($user) ? $user->patients()->search($search)->paginate(10) : auth()->user()->patients()->search($search)->paginate(10);

        return $patients;
    }

     /**
     * List of patients for the appointments form
     */
   public function listForClinics($search = null)
   {
       
        $patients = [];

         
        if (isset($search) && trim($search))
        {
            $patients = $this->model->search($search)->paginate(10);

        } 

       

        return $patients;
    }

    private function prepareData($data)
    {
        
        $data['created_by'] = auth()->id();

        return $data;
    }

    /**
     * Get all the appointments for the admin panel
     * @param $search
     * @return mixed
     */
    public function reportsStatistics($search)
    {
        
         $order = 'created_at';
         $dir = 'desc';

    
        $patients = $this->model;


        if (isset($search['date1']) && $search['date1'] != "")
        {
           
            
            
            $date1 = new Carbon($search['date1']);
            $date2 = (isset($search['date2']) && $search['date2'] != "") ? $search['date2'] : $search['date1'];
            $date2 = new Carbon($date2);
            
         
            $patients = $patients->where([['patients.created_at', '>=', $date1],
                    ['patients.created_at', '<=', $date2->endOfDay()]]);
            
        }

        $patients = $patients->selectRaw('province, count(*) items')
                         ->groupBy('province')
                         ->orderBy('province','DESC')
                         ->get()
                         ->toArray();
        $statistics = [
            'patients' => $patients
        ];
         
      return $statistics;
       
    }


}