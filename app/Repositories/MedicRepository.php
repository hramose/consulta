<?php namespace App\Repositories;


use App\Appointment;
use App\Office;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;

class MedicRepository extends DbRepository{


    /**
     * Construct
     * @param User $model
     */
    function __construct(User $model)
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
        $order = 'created_at';
        $dir = 'desc';

        if (! count($search) > 0) return $this->model->whereHas('roles', function($q){
                                                    $q->where('name', 'medico');
                                                })->paginate($this->limit);

        
          if (isset($search['lat']) && $search['lat'] != "" && isset($search['lon']) && $search['lon'] != "")
        {
             /*raw query*/
             /*$officesNear = \DB::select('SELECT id, name,province, (6371 * ACOS( 
                                                                    SIN(RADIANS(lat)) * SIN(RADIANS('.$search['lat'].')) 
                                                                    + COS(RADIANS(lon - '.$search['lon'].')) * COS(RADIANS(lat)) 
                                                                    * COS(RADIANS('.$search['lat'].'))
                                                                    )
                                                       ) AS distance
                                    FROM offices
                                    HAVING distance < 25 /* 1 KM  a la redonda */
                                    /*ORDER BY distance ASC');*/

             /*$officesNearIds = \DB::table('offices')
                     ->select(\DB::raw('id, name,province, (6371 * ACOS( 
                                                                    SIN(RADIANS(lat)) * SIN(RADIANS('.$search['lat'].')) 
                                                                    + COS(RADIANS(lon - '.$search['lon'].')) * COS(RADIANS(lat)) 
                                                                    * COS(RADIANS('.$search['lat'].'))
                                                                    )
                                                       ) AS distance
                                    '))
                    
                     ->having('distance','<', 1)
                     ->orderBy('distance', 'ASC')
                     ->pluck('id')->all();*/
            //dd($officesNear);

            $offices = Office::whereHas('users', function($q) use($search){

                                        //$q->where('speciality_id', '=', $search['speciality']);
                                           //->where('name', 'like', '%' . $search['q'] . '%');
                                        if (trim($search['q']))
                                        {
                                            $q->where('name', 'like', '%' . $search['q'] . '%');
                                              
                                            return $q;
                                        } 
                                       
                                   
                                    });
           // dd($offices->get()->all());

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



            
             $offices = $offices->NearLatLng($search['lat'], $search['lon'], 25, 'K');
             //dd($offices->with('users')->orderBy('distance', 'ASC')->get()->all());
             $paginator = paginate($offices->with('users')->orderBy('distance', 'ASC')->get()->all(), $this->limit);
          
             return $paginator; //$offices->with('user')->orderBy('distance', 'ASC')->get();//paginate($this->limit);
        
        }else{



            if (isset($search['q']) && trim($search['q']))
            {
                $users = $this->model->whereHas('roles', function($q){
                                                        $q->where('name', 'medico');
                                                    })->Search($search['q']);
            } else
            {
                $users = $this->model->whereHas('roles', function($q){
                                        $q->where('name', 'medico');
                                    });
            }

            if (isset($search['active']) && $search['active'] != "")
            {
                $users = $users->where('active', '=', $search['active']);
            }

            if (isset($search['province']) && $search['province'] != "")
            {
                
                $users = $users->whereHas('offices', function($q) use($search){
                                        $q->where('province', $search['province']);
                                    });
                //dd($users->get());
            }
             if (isset($search['canton']) && $search['canton'] != "")
            {
                $users = $users->whereHas('offices', function($q) use($search){
                                        $q->where('canton', $search['canton']);
                                    });
            }
             if (isset($search['district']) && $search['district'] != "")
            {
                $users = $users->whereHas('offices', function($q) use($search){
                                        $q->where('district', $search['district']);
                                    });
            }

            if (isset($search['speciality']) && $search['speciality'] != "")
            {
                $users = $users->whereHas('specialities', function($q) use($search){
                                        $q->where('specialities.id', $search['speciality']);
                                    });
                                //$users->where('speciality_id', '=', $search['speciality']);
            
            }
            
            if (isset($search['general']) && $search['general'] != ""){

                $users = $users->whereHas('specialities', function($q) use($search){
                                        $q->where('specialities.id', 53);
                                    });
                /*$users->where(function($q) use($search){
                                        $q->where('speciality_id', 53)
                                        ->orWhere('speciality_id', 0);
                                    });*/               
            }


            if (isset($search['order']) && $search['order'] != "")
            {
                $order = $search['order'];
            }
            if (isset($search['dir']) && $search['dir'] != "")
            {
                $dir = $search['dir'];
            }

            return $users->with('offices')->orderBy('users.'.$order , $dir)->paginate($this->limit);
        }



    }

     /**
     * Find all the users for the admin panel
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAllByOffice($office_id, $search = null)
    {
        $order = 'created_at';
        $dir = 'desc';

        $office = Office::findOrfail($office_id);
        
        /*if($except)
            $medics = $office->users()->where('users.id','<>',$except)->whereHas('roles', function($q){
                                                    $q->where('name', 'medico');
                                                });*/
        //else
           if (isset($search['q']) && trim($search['q']))
            {
               $medics = $office->users()->whereHas('roles', function($q){
                                                    $q->where('name', 'medico');
                                                })->Search($search['q']);
            } else
            {
               $medics = $office->users()->whereHas('roles', function($q){
                                                    $q->where('name', 'medico');
                                                });
            }
           

        
       

        return $medics->with('offices')->orderBy('users.'.$order , $dir)->paginate($this->limit);
       



    }

    /**
     * Find all the users for the admin panel
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAllByOffices($office_ids, $search = null)
    {
        $order = 'created_at';
        $dir = 'desc';

        //$office = Office::findOrfail($office_id);

        $medic_ids = \DB::table('office_user')->whereIn('office_id',$office_ids)->pluck('user_id');

       
        
        /*if($except)
            $medics = $office->users()->where('users.id','<>',$except)->whereHas('roles', function($q){
                                                    $q->where('name', 'medico');
                                                });*/
        //else
           if (isset($search['q']) && trim($search['q']))
            {
               //$medics = $office->users()->whereHas('roles', function($q){
                                               //     $q->where('name', 'medico');
                                               // })->Search($search['q']);

                $medics = User::whereIn('users.id', $medic_ids)->whereHas('roles', function($q){
                                                    $q->where('name', 'medico');
                                                })->Search($search['q']);
            } else
            {
               // $medics = $office->users()->whereHas('roles', function($q){
               //                                      $q->where('name', 'medico');
               //                                  });
                 $medics = User::whereIn('users.id', $medic_ids)->whereHas('roles', function($q){
                                                    $q->where('name', 'medico');
                                                });
            }
           

        
       

        return $medics->with('offices')->orderBy('users.'.$order , $dir)->paginate($this->limit);
       



    }

    /**
     * Find all the users for the admin panel
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAllByOfficeWithoutPaginate($office_id, $search = null)
    {
        $order = 'created_at';
        $dir = 'desc';

        $office = Office::findOrfail($office_id);
        
        /*if($except)
            $medics = $office->users()->where('users.id','<>',$except)->whereHas('roles', function($q){
                                                    $q->where('name', 'medico');
                                                });*/
        //else
           if (isset($search['q']) && trim($search['q']))
            {
               $medics = $office->users()->whereHas('roles', function($q){
                                                    $q->where('name', 'medico');
                                                })->Search($search['q']);
            } else
            {
               $medics = $office->users()->whereHas('roles', function($q){
                                                    $q->where('name', 'medico');
                                                });
            }
           

        
       

        return $medics->with('offices')->orderBy('users.'.$order , $dir)->get();
       



    }

    /**
     * Find all the users for the admin panel
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAllWithoutPaginate($search = null)
    {
        $order = 'created_at';
        $dir = 'desc';
        
            
            if (isset($search['clinic']) && $search['clinic'] != "")
            {
                $medics = Office::find($search['clinic'])->users();
            }else{
                
                $medics = $this->model;

            }
            
           
        
           if (isset($search['q']) && trim($search['q']))
            {
               $medics = $medics->whereHas('roles', function($q){
                                                    $q->where('name', 'medico');
                                                })->Search($search['q']);
            } else
            {
               $medics = $medics->whereHas('roles', function($q){
                                                    $q->where('name', 'medico');
                                                });
            }
           

        
       

        return $medics->orderBy('users.'.$order , $dir)->get();
       



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

    
       
        $medics = $this->model->whereHas('roles', function ($query) use ($search) {
                        $query->where('name',  'medico');
                    });

        $appointments = \DB::table('appointments');

       
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

        $medics = $medics->selectRaw('active, count(*) items')
                         ->groupBy('active')
                         ->orderBy('active','DESC')
                         ->get()
                         ->toArray();
        $statistics = [
            'medics' => $medics,
            'appointments' => $appointments
        ];
        //dd($statistics);

         
      return $statistics;
       
    }

     /**
     * Get all the appointments for the admin panel
     * @param $search
     * @return mixed
     */
    public function reportsStatisticsByMedic($search)
    {
        
         $order = 'created_at';
         $dir = 'desc';

    
        $medics = $this->model;
        
        //$appointments = \DB::table('appointments');
        
        if (isset($search['medic']) && $search['medic'] != "")
        {
            $medic = $medics->where('id', $search['medic'])->first();
            $appointments = $medic->appointments();
            
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
            
            'appointments' => $appointments
        ];

        
         
      return $statistics;
       
    }

    /**
     * Get all the appointments for the admin panel
     * @param $search
     * @return mixed
     */
    public function reportsStatisticsReviews($search)
    {
        
         $order = 'created_at';
         $dir = 'desc';

        
        $total_rating_service_cache = 0;
        $total_rating_service_count = 0;

        if (isset($search['medic']) && $search['medic'] != "")
        {
           
            $medic = User::find($search['medic']);
           

          
            $total_rating_service_cache = $medic->rating_service_cache;

            $total_rating_service_count =$medic->rating_service_count;

             $total_rating_medic_cache = $medic->rating_medic_cache;

            $total_rating_medic_count =$medic->rating_medic_count;
        }
        
        
       
      

        $statisticsReview = [
            'rating_service_cache' => $total_rating_service_cache,
            'rating_service_count' => $total_rating_service_count,
            'rating_medic_cache' => $total_rating_medic_cache,
            'rating_medic_count' => $total_rating_medic_count,

        ];
        
      return $statisticsReview;
       
    }



    



   


}