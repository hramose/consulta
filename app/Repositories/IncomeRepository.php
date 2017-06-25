<?php namespace App\Repositories;





use App\Income;
use App\User;
use Carbon\Carbon;

class IncomeRepository extends DbRepository{


    /**
     * Construct
     * @param User $model
     */
    function __construct(Income $model)
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
        
        $income = $this->model;
        $income->appointment_id = $data['appointment_id'];
        $income->type = $data['type'];
        $income->medic_type = $data['medic_type'];
        $income->date = $data['date'];
        $income->month = $data['month'];
        $income->year = $data['year'];
        $income->amount = $data['amount'];


        $income = $medic->incomes()->save($income);
        



       
        return $income;
        
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

        $incomes = $this->model->where('user_id', $id);
       
        if (! count($search) > 0) return $incomes->with('user','appointment')->orderBy('incomes.'.$order , $dir)->paginate($limit);

       

        if (isset($search['type']) && $search['type'] != "")
        {

            $incomes = $incomes->where('type', $search['type']);
        }

        if (isset($search['paid']) && $search['paid'] != "")
        {

            $incomes = $incomes->where('paid', $search['paid']);
        }

        if (isset($search['date']) && $search['date'] != "")
        {

            $incomes = $incomes->whereDate('date', $search['date']);
        }

        if (isset($search['order']) && $search['order'] != "")
        {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != "")
        {
            $dir = $search['dir'];
        }


        return $incomes->with('user','appointment')->orderBy('incomes.'.$order , $dir)->paginate($limit);

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

    
       
       // $incomes = $this->model->where('type', 'I');
        $incomesGeneral =  $this->model->where('type', 'I')->where('medic_type',"G");
        $incomesSpecialist =  $this->model->where('type', 'I')->where('medic_type','S');
       
        if (isset($search['date1']) && $search['date1'] != "")
        {
           
            
            
            $date1 = new Carbon($search['date1']);
            $date2 = (isset($search['date2']) && $search['date2'] != "") ? $search['date2'] : $search['date1'];
            $date2 = new Carbon($date2);
            
         
            $incomesGeneral = $incomesGeneral->where([['incomes.date', '>=', $date1],
                    ['incomes.date', '<=', $date2->endOfDay()]]);

             $incomesSpecialist = $incomesSpecialist->where([['incomes.date', '>=', $date1],
                    ['incomes.date', '<=', $date2->endOfDay()]]);
           
        }
         
       
      
        $countSpecialist = $incomesSpecialist->count();
        $countGeneral = $incomesGeneral->count();
        $incomesG = $incomesGeneral->sum('amount');
        $paidGeneral = ($countGeneral) ? $incomesGeneral->where('paid', 1)->sum('amount') : 0;
        $pendingGeneral =  ($countGeneral) ? ($incomesG - $paidGeneral) : 0;

        $incomesS= $incomesSpecialist->sum('amount');
        $paidSpecialist = ($countSpecialist) ? $incomesSpecialist->where('paid', 1)->sum('amount') : 0;
        $pendingSpecialist = ($countSpecialist) ? ($incomesS - $paidSpecialist) : 0;

        $specialist = [
            'appointments' => $countSpecialist,
            'incomes' => $incomesS,
            'pending' => $pendingSpecialist,
            'paid' => $paidSpecialist
        ];

        $general = [
            'appointments' =>  $countGeneral,
            'incomes' =>   $incomesG,
            'pending' => $pendingGeneral,
            'paid' => $paidGeneral
        ];

 

        $statistics = [
            'specialist' => $specialist,
            'general' => $general
        ];
      

         
      return $statistics;
       
    }
    

    

    private function prepareData($data)
    {
       

        return $data;
    }


}