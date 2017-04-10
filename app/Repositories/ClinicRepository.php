<?php namespace App\Repositories;


use App\Office;
use App\Role;
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


    



   


}