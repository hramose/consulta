<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Repositories\PatientRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PatientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PatientRepository $patientRepo)
    {
        $this->middleware('auth');
        $this->patientRepo = $patientRepo;

        
    }


    /**
     * Guardar paciente
     */
    public function create()
    {
        
        $user = auth()->user();
        
        return view('patients.register')->with('user');

    }

    /**
     * Guardar paciente
     */
    public function register(PatientRequest $request)
    {
        
        $patient =$this->patientRepo->store($request->all());



       return Redirect('/');

    }

    /**
     * Guardar paciente
     */
    public function store(PatientRequest $request)
    {
        
        $patient =$this->patientRepo->store($request->all());



        return $patient;

    }

    /**
     * Actualizar Paciente
     */
    public function update($id, PatientRequest $request)
    {
       
        $patient = $this->patientRepo->update($id, $request->all());
        

        return $patient;

    }
}
