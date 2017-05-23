<?php namespace App\Repositories;


use App\Appointment;
use App\Office;
use App\Poll;
use App\Question;
use App\Role;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class ClinicRepository extends DbRepository{


    /**
     * Construct
     * @param User $model
     */
    function __construct(Office $model)
    {
        $this->model = $model;
        $this->limit = 10;
    }

   
   

    /**
     * Find all the users for the admin panel
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAll($search = null)
    {
        $order = 'distance';
        $dir = 'desc';

        if (! count($search) > 0) return $this->model->paginate($this->limit);

        if (isset($search['q']) && trim($search['q']))
        {
            $offices = $this->model->Active(1)->Search($search['q']);
        } else
        {
            $offices = $this->model->Active(1);
        }
       
        if (isset($search['lat']) && $search['lat'] != "" && isset($search['lon']) && $search['lon'] != "")
        {
            
            $offices = $offices->NearLatLng($search['lat'], $search['lon'], 25, 'K');
            $offices = $offices->orderBy('distance','ASC');

        }


        if (isset($search['province']) && $search['province'] != "")
        {
            $offices = $offices->where('province', $search['province']);
                               
        }

         if (isset($search['canton']) && $search['canton'] != "")
        {
            $offices = $offices->where('canton', $search['canton']);
                               
        }
         if (isset($search['district']) && $search['district'] != "")
        {
            $offices = $offices->where('district', $search['district']);
                                
        }


        if (isset($search['order']) && $search['order'] != "")
        {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != "")
        {
            $dir = $search['dir'];
        }

        $paginator = paginate($offices->get()->all(), $this->limit);
          
        return $paginator; //$offices->paginate($this->limit);

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

        
        $total_rating_service_cache = 0;
        $total_rating_service_count = 0;

        if (isset($search['clinic']) && $search['clinic'] != "")
        {
           
            $office = Office::find($search['clinic']);
            $medics = $office->users()->whereHas('roles', function($q){
                                                    $q->where('name', 'medico');
                                                })->get();

          
            $total_rating_service_cache = ($medics->count() > 0 ) ? $medics->sum('rating_service_cache') / $medics->count() : 0;

            $total_rating_service_count = $medics->sum('rating_service_count');
        }
        
        
        /*if (isset($search['date1']) && $search['date1'] != "")
        {
           
            
            
            $date1 = new Carbon($search['date1']);
            $date2 = (isset($search['date2']) && $search['date2'] != "") ? $search['date2'] : $search['date1'];
            $date2 = new Carbon($date2);
            
         
            $polls = $polls->where([['polls.created_at', '>=', $date1],
                    ['polls.created_at', '<=', $date2->endOfDay()]]);
            
        }*/

      

        $statisticsReview = [
            'rating_service_cache' => $total_rating_service_cache,
            'rating_service_count' => $total_rating_service_count,

        ];
        
      return $statisticsReview;
       
    }

    /**
     * Get all the appointments for the admin panel
     * @param $search
     * @return mixed
     */
    public function reportsStatisticsAppointments($search)
    {
        
         $order = 'created_at';
         $dir = 'desc';

    
        $clinics = $this->model->count();

        if (isset($search['clinic']) && $search['clinic'] != "")
        {
            $appointments = Appointment::where('office_id', $search['clinic']);
        }
        
        if (isset($search['medic']) && $search['medic'] != "")
        {
            $appointments = $appointments->where('user_id', $search['medic']);
        }
        

        if (isset($search['date1']) && $search['date1'] != "")
        {
           
            
            
            $date1 = new Carbon($search['date1']);
            $date2 = (isset($search['date2']) && $search['date2'] != "") ? $search['date2'] : $search['date1'];
            $date2 = new Carbon($date2);
            
         
            $appointments = $appointments->where([['appointments.date', '>=', $date1],
                    ['appointments.date', '<=', $date2->endOfDay()]]);
            
        }

        $appointments = $appointments->selectRaw('status, count(*) items')
                         ->groupBy('status')
                         ->orderBy('status','DESC')
                         ->get()
                         ->toArray();
        $statistics = [
            'clinics' => $clinics,
            'appointments' => $appointments
        ];
         
      return $statistics;
       
    }


     /**
     * Find all the appointments by Doctor
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAllWithoutPagination($id, $search = null)
    {
        $order = 'created_at';
        $dir = 'desc';

        $offices = $this->model;

        if (! count($search) > 0) return $offices->get();

        if (isset($search['q']) && trim($search['q']))
        {
            $offices = $offices->Search($search['q']);
        } 
        if (isset($search['type']) && trim($search['type']))
        {
            $offices = $offices->where('type', $search['type']);
        } 

        if (isset($search['order']) && $search['order'] != "")
        {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != "")
        {
            $dir = $search['dir'];
        }


        return $offices->orderBy('offices.'.$order , $dir)->get();

    }


    



   


}