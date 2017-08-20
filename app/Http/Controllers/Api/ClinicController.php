<?php

namespace App\Http\Controllers\Api;

use App\Office;
use App\Repositories\ClinicRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ClinicController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ClinicRepository $clinicRepo)
    {
        $this->middleware('auth');
        $this->clinicRepo = $clinicRepo;
       

       
    }


    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function index()
    {
       $clinics = [];
       
       if(request()->all())
       {
            if(trim(request('q')) != '' || request('province') != '' || request('canton') != '' || request('district') != '' || request('lat') != '' || request('lon') != ''){

                $search['q'] = trim(request('q'));
                $search['province'] = request('province');
                $search['canton'] = request('canton');
                $search['district'] = request('district');
                $search['lat'] = request('lat');
                $search['lon'] = request('lon');

                
                $clinics = $this->clinicRepo->findAll($search);
              

               return $clinics;
            }
        }

        return $clinics;

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
         $clinic = Office::with('users','specialities')->find($id);
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
