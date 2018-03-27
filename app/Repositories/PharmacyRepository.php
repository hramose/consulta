<?php namespace App\Repositories;


use App\Pharmacy;
use App\User;

class PharmacyRepository extends DbRepository{


    /**
     * Construct
     * @param User $model
     */
    function __construct(Pharmacy $model)
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
        
       
        //$pharmacy = auth()->user()->offices()->create($data);
        
        $pharmacy = $this->model->create($data);
        
        
        $pharmacy = auth()->user()->pharmacies()->save($pharmacy);

        return $pharmacy;
    }

    /**
     * Update a Office
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function update($id, $data)
    {
               
        $pharmacy = $this->model->findOrFail($id);

        $pharmacy->fill($data);
        $pharmacy->save();
       


        return $pharmacy;
    }

    /**
     * Delete medicine to user
     * @param $id
     * @param $data
     * @return \Illuminate\Support\Collection|static
     */
    public function delete($id)
    {
        
        $pharmacy = $this->model->findOrFail($id)->delete();
        
       /* \DB::table('verified_offices')->where([
            ['user_id', '=', auth()->id()],
            ['office_id', '=', $id],
        ])->delete();*/
     
        return $pharmacy;
    }

   




}