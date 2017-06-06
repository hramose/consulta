<?php

namespace App\Http\Controllers;

use App\Repositories\ClinicRepository;
use App\Repositories\MedicRepository;
use App\Repositories\OfficeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ClinicController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ClinicRepository $clinicRepo, OfficeRepository $officeRepo, MedicRepository $medicRepo)
    {
        $this->middleware('auth');
        $this->clinicRepo = $clinicRepo;
        $this->officeRepo = $officeRepo;
         $this->medicRepo = $medicRepo;

       
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
              

                return view('search.clinics.index',compact('clinics','search'));
            }
        }

        return view('search.clinics.index',compact('clinics'));

    }

    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function schedule($office_id)
    {
        if(!auth()->user()->active) return redirect('/');

        
         $office = $this->officeRepo->findbyId($office_id);
         $medics = $this->medicRepo->findAllByOffice($office->id);

        if(request('medic'))
            $medic = $this->medicRepo->findById(request('medic'));
        else
            $medic = null;
        
       // if(!$medic->hasrole('medico')) return redirect('/');
        
        return view('search.clinics.schedule',compact('medics','medic','office'));
    }

    public function getAllOffices()
    {
        
        $offices = $this->clinicRepo->findAllWithoutPagination(auth()->id(),request()->all());

        return $offices;
        
    }

    
}
