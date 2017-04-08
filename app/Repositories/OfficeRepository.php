<?php namespace App\Repositories;


use App\Office;
use App\User;

class OfficeRepository extends DbRepository{


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
     * save a user
     * @param $data
     */
    public function store($data)
    {
        
       
        //$office = auth()->user()->offices()->create($data);
        
        $office = $this->model->create($data);
        
        
        $office = auth()->user()->offices()->save($office);

        return $office;
    }

    /**
     * Update a Office
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function update($id, $data)
    {
               
        $office = $this->model->findOrFail($id);

        $office->fill($data);
        $office->save();
       


        return $office;
    }

    /**
     * Delete medicine to user
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function delete($id)
    {
        
        $office = $this->model->findOrFail($id)->delete();
     
        return $office;
    }

    /**
     * Find all the appointments by Doctor
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAllByDoctorWithoutPagination($medic, $search = null)
    {
        $order = 'created_at';
        $dir = 'desc';

        $offices = $medic->offices();

        if (! count($search) > 0) return $offices->get();

        if (trim($search['q']))
        {
            $offices = $offices->Search($search['q']);
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