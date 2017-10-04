<?php namespace App\Repositories;


use App\Appointment;
use App\Balance;
use App\Income;
use App\Office;
use App\Patient;
use App\Repositories\IncomeRepository;
use App\User;
use Carbon\Carbon;

class AppointmentRepository extends DbRepository{


    /**
     * Construct
     * @param User $model
     */
    function __construct(Appointment $model, IncomeRepository $incomeRepo)
    {
        $this->model = $model;
        $this->incomeRepo = $incomeRepo;
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
           

            $appointment->patient()->associate($patient); // asociar la cita con el paciente
            $appointment->save();

        }else{

            if($data['patient_id']) // verificar si lo que viene es 0 o un numero que no existe por lo cual enviar mensaje de error o crear la cita como background
            {     
             
               return false;
            
            }

            $appointment = $medic->appointments()->create($data); //$this->model->create($data);
           
            
        }
         
          $appointment->createDiseaseNotes();
          $appointment->createPhysicalExams();
          
          //$appointment->createPoll();
          /*if(auth()->user()->hasRole('paciente'))
            $appointment->createPoll(auth()->id());
          else
             $appointment->createPoll($data['patient_id']);*/
       


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

     public function update_status($id, $status)
    {

        $appointment = $this->findById($id);

        if($appointment->status == 0){
          
            $dataIncome['type'] = 'I';
            $dataIncome['medic_type'] = auth()->user()->specialities->count() > 0 ? 'S' :  'G';
            $dataIncome['amount'] = auth()->user()->specialities->count() > 0 ? getAmountSpecialistPerAppointment() :getAmountGeneralPerAppointment(); 
            $dataIncome['appointment_id'] = $appointment->id;
            $dataIncome['date'] = $appointment->date;
            $dataIncome['month'] = $appointment->date->month;
            $dataIncome['year'] = $appointment->date->year;



            $income = $this->incomeRepo->store($dataIncome);
        }

         if($appointment->status == 2){ // si esta finalizada no la actualizamos
              return $appointment;
         }

        $appointment->status = $status;
        $appointment->save();

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
            {
                $appointment->reminders()->delete();

                return $appointment = $appointment->delete();
            }
        }else{

            if( !$appointment->isStarted() )
            {
                $appointment->reminders()->delete();
                
                return $appointment = $appointment->delete();
            }
        }


        return $appointment;
    }


    /**
     * Find all the appointments by Doctor
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAllByDoctor($id, $search = null, $limit = 10)
    {
        $order = 'date';
        $dir = 'desc';

        $appointments = $this->model->where('user_id', $id)->where('patient_id','<>',0);
       
        if (! count($search) > 0) return $appointments->with('user','patient')->orderBy('appointments.'.$order , $dir)->orderBy('appointments.start', $dir)->paginate($limit);

        if (isset($search['q']) && trim($search['q'] != ""))
        {
            
            $appointments = $appointments->Search($search['q']);
        }

        if (isset($search['calendar']) && $search['calendar'] != "")
        {
            $appointments = $appointments->where('visible_at_calendar', $search['calendar']);
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


        return $appointments->with('user','patient','notes')->orderBy('appointments.'.$order , $dir)->orderBy('appointments.start', $dir)->paginate($limit);

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

        if (! count($search) > 0) return $appointments->with('patient','user','office')->get();

        if (isset($search['q']) && trim($search['q']))
        {
            $appointments = $appointments->Search($search['q']);
        }

         if (isset($search['office']) && $search['office'] != "")
        {
            $appointments = $appointments->where('office_id', $search['office']);
        } 

        if (isset($search['calendar']) && $search['calendar'] != "")
        {
            $appointments = $appointments->where('visible_at_calendar', $search['calendar']);
        } 

         if (isset($search['date1']) && $search['date1'] != "")
        {
           
           // dd($search['date2']);
            
            $date1 = $search['date1'];
            $date2 = (isset($search['date2']) && $search['date2'] != "") ? $search['date2'] : $search['date1'];
            $date2 = $date2;
            
         
            $appointments = $appointments->where([['appointments.date', '>=', $date1],
                    ['appointments.date', '<=', $date2->endOfDay()]]);
            
        }


        if (isset($search['order']) && $search['order'] != "")
        {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != "")
        {
            $dir = $search['dir'];
        }


        return $appointments->with('patient', 'user','office')->orderBy('appointments.'.$order , $dir)->get();

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

        if (isset($search['q']) && trim($search['q']))
        {
            $appointments = $appointments->Search($search['q']);
        }
        if (isset($search['office']) && $search['office'] !="")
        {
            $appointments = $appointments->where('office_id', $search['office']);
        }
        if (isset($search['status']) && $search['status'] == 1)
        {
            $appointments = $appointments->where('status','>=', 1);
        }
        
        if (isset($search['status']) && $search['status'] == 0)
        {
            $appointments = $appointments->where('status', 0);
        } 
        if (isset($search['calendar']) && $search['calendar'] != "")
        {
            $appointments = $appointments->where('visible_at_calendar', $search['calendar']);
        }  


        if (isset($search['order']) && $search['order'] != "")
        {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != "")
        {
            $dir = $search['dir'];
        }


        return $appointments->with('user','notes')->orderBy('appointments.'.$order , $dir)->paginate($this->limit);

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

    
        $appointments = $this->model;
        $balances = Balance::get();
        $medics = [];

        if (isset($search['clinic']) && $search['clinic'] != "")
        {
            $appointments = $appointments->where('office_id', $search['clinic']);
            //$balances = $balances->where('office_id', $search['clinic']);
          
           
           
          
        }
        
        if (isset($search['medic']) && $search['medic'] != "")
        {
            $appointments = $appointments->where('user_id', $search['medic']);
            //$balances = $balances->where('user_id', $search['medic']);
            $medics = User::where('id',$search['medic']);
            
        }
        if (isset($search['speciality']) && $search['speciality'] != "")
        {
                $usersIds = User::whereHas('specialities', function($q) use($search){
                                        $q->where('specialities.id', $search['speciality']);
                                    })->pluck('users.id');


               $appointments = $appointments->whereIn('user_id', $usersIds);           
            
        }

        if (isset($search['date1']) && $search['date1'] != "")
        {
           
           
            
            $date1 = new Carbon($search['date1']);
            $date2 = (isset($search['date2']) && $search['date2'] != "") ? $search['date2'] : $search['date1'];
            $date2 = new Carbon($date2);
            
            if($appointments->count())
                $appointments = $appointments->where([['appointments.date', '>=', $date1],
                    ['appointments.date', '<=', $date2->endOfDay()]]);

            // if($balances->count())
            //     $balances = $balances->where([['balances.created_at', '>=', $date1],
            //        ['balances.created_at', '<=', $date2->endOfDay()]]);
            

            if (isset($search['clinic']) && $search['clinic'] != "" && !count($medics))
            {

              $medics = Office::find($search['clinic'])->medicsWithInvoices($date1, $date2);

            }else{

               $medics = $medics->with(['invoices' => function ($query) use($date1, $date2) {
                                $query->where('status', 1)
                                ->where([['invoices.created_at', '>=', $date1],
                                    ['invoices.created_at', '<=', $date2->endOfDay()]]);
                            }])->get();
            }
           
        }


        $statisticsAppointment = $appointments->selectRaw('status, count(*) items')
                         ->groupBy('status')
                         ->orderBy('status','DESC')
                         ->get()
                         ->toArray();

        $statisticsPatients = $appointments->where('status', 1)->count();
   
       
        /*$statisticsSales = [
            'invoices' => $balances->sum('invoices'),
            'total' => $balances->sum('total'),

        ];*/
        
        $totalAppointments = 0;
        $totalInvoices = 0;
        $totalCommission = 0;
       

        foreach ($medics as $medic) {
            $invoicesTotalMedic = $medic->invoices->sum('total');
            $totalAppointments += $medic->invoices->count();
            $totalInvoices += $invoicesTotalMedic;
            $totalCommission += ($medic->commission / 100 ) * $invoicesTotalMedic;
            
            /*$paid =  $medic->invoices->filter(function ($item, $key) {
                        return $item->status == 1;
                    });*/
           // $totalPaid =  $paid->sum('total');
           //  $totalPending = $totalIncomes - $totalPaid;

        }

          $statisticsInvoices = [
            'medics' => $medics,
            'totalAppointments' => $totalAppointments,
            'totalInvoices' => $totalInvoices,
            'totalCommission' => $totalCommission,
           

        ];


        $data =  [
            'appointments' => $statisticsAppointment,
            'patients' => $statisticsPatients,
            //'sales' => $statisticsSales,
            'invoices' => $statisticsInvoices

        ];
         
      return $data;
       
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