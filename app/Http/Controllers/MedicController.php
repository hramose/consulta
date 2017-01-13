<?php

namespace App\Http\Controllers;

use App\Repositories\MedicRepository;
use App\Speciality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MedicController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MedicRepository $medicRepo)
    {
        $this->middleware('auth');
        $this->medicRepo = $medicRepo;

        View::share('specialities', Speciality::all());
    }


    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function index()
    {
       $medics = [];

       if(request()->all())
       {
            $search['q'] = trim(request('q'));
            $search['speciality'] = request('speciality');
            $search['province'] = request('province');
            $search['lat'] = request('lat');
            $search['lon'] = request('lon');
            $selectedSpeciality = $search['speciality'];
            $selectedProvince = $search['province'];
            
            $medics = $this->medicRepo->findAll($search);
            //dd( $medics);
        
            return view('medics.index',compact('medics','search','selectedSpeciality','selectedProvince'));
        }

        return view('medics.index',compact('medics'));

    }

    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function search()
    {

        
        $search['q'] = trim(request('q'));
        $search['speciality'] = request('speciality');
        $selectedSpeciality = $search['speciality'];
        
        $medics = $this->medicRepo->getAll($search);
        $specialities = Speciality::all();
    
        

        return view('medics.index',compact('medics','search','selectedSpeciality'));

    }
}
