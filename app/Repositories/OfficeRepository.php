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




}