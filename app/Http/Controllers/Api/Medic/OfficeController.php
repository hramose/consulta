<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Mail\NewOffice;
use App\Mail\NewRequestOffice;
use App\Office;
use App\Repositories\OfficeRepository;
use App\RequestOffice;
use App\User;
use Illuminate\Http\Request;

class OfficeController extends ApiController
{
    
    function __construct(OfficeRepository $officeRepo)
    {
    	
        $this->middleware('auth');
    	$this->officeRepo = $officeRepo;

        $this->administrators = User::whereHas('roles', function ($query){
                        $query->where('name',  'administrador');
                    })->get();

    }

     public function index()
    {
        $search = request()->all();
        $search['active'] = 1;
        
        $offices = $this->officeRepo->findAllWithoutPagination(auth()->id(),$search);

        return $offices;
        
    }
    
     /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     * @internal param int $id
     */
     public function show($id)
     {
         $clinic = Office::find($id);
         $clinic['medics'] = $clinic->doctors();
         if(! $clinic)
         {
             return $this->respondNotFound('clinic does not exist');
 
         }
         return $this->respond([
            'data' => $clinic//$this->productTransformer->transform($product)
         ]);
     }

    
   
   

}
