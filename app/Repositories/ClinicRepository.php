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

        if (! count($search) > 0) return $this->model-paginate($this->limit);

        if (trim($search['q']))
        {
            $offices = $this->model->Search($search['q']);
        } else
        {
            $offices = $this->model;
        }


        if (isset($search['lat']) && $search['lat'] != "" && isset($search['lon']) && $search['lon'] != "")
        {
            
            $offices = $offices->NearLatLng($search['lat'], $search['lon'], 5, 'K');
            $offices = $offices->orderBy('distance');

        }


        if (isset($search['province']) && $search['province'] != "")
        {
            $offices = $offices->where('province', $search['province']);
                               
        }


        if (isset($search['order']) && $search['order'] != "")
        {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != "")
        {
            $dir = $search['dir'];
        }


        return $offices->paginate($this->limit);

    }


    



   


}