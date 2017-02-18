<?php namespace App\Repositories;


use App\Office;
use App\Role;
use App\User;
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

            $offices = Office::whereHas('user', function($q) use($search){

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
            
             $paginator = paginate($offices->with('user')->orderBy('distance', 'ASC')->get()->all(), $this->limit);
          
             return $paginator; //$offices->with('user')->orderBy('distance', 'ASC')->get();//paginate($this->limit);
        
        }else{



            if (trim($search['q']))
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


    



   


}