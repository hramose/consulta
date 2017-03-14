<?php

namespace App\Http\Controllers;

use App\Repositories\ClinicRepository;
use App\Repositories\MedicRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ClinicController extends Controller
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
                //$count = $clinics->count();

                //flash('Se '. (($count > 1) ? "encontraron" : "encontró ") . $count.' Clinica(s) u hospital(es)','success');

                return view('clinics.index',compact('clinics','search'));
            }
        }

        return view('clinics.index',compact('clinics'));

    }

    
}
