<?php

namespace App\Http\Controllers\Api;


use App\Repositories\MedicRepository;
use App\Speciality;
use Illuminate\Http\Request;


class MedicController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MedicRepository $medicRepo)
    {
       
        $this->medicRepo = $medicRepo;

        
    }

     /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function search()
    {
     
       

       $medics = [];
       
       if(request()->all())
       {
            if(trim(request('q')) != '' || request('province') != '' || request('canton') != '' || request('district') != '' || request('lat') != '' || request('lon') != '' || request('speciality') != ''){
                $search['q'] = trim(request('q'));
                $search['speciality'] = request('speciality');
                $search['province'] = request('province');
                $search['canton'] = request('canton');
                $search['district'] = request('district');
                $search['lat'] = request('lat');
                $search['lon'] = request('lon');
                $search['general'] = request('general');
                $search['active'] = 1;
                $selectedSpeciality = $search['speciality'];
                
                
                $medics = $this->medicRepo->findAll($search);
               
               
                    
                

                 return $medics;
            }
        }

         return $medics;

    }

   function getSpecialities () {
        return Speciality::all();
    }



    
}
