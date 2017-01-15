<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Repositories\AppointmentRepository;
use App\Repositories\MedicRepository;
use App\Repositories\PatientRepository;
use App\Speciality;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MedicController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MedicRepository $medicRepo, AppointmentRepository $appointmentRepo, PatientRepository $patientRepo)
    {
        $this->middleware('auth');
        $this->medicRepo = $medicRepo;
        $this->appointmentRepo = $appointmentRepo;
        $this->patientRepo = $patientRepo;

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
    public function schedule($id)
    {
      
        return view('medics.schedule',compact('id'));

    }

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function storeAppointment()
    {
        
        $appointment = $this->appointmentRepo->store(request()->all());
        $appointment['patient'] = $appointment->patient;
        $appointment['user'] = $appointment->user;
        
        if(request('medic_id')) //agregar paciente del usuario al medico tambien
        {
            $medic = User::find(request('medic_id'));
            $medic->patients()->save($appointment->patient);
        }

        return $appointment;

        
        
    }

     /**
     * Actualizar consulta(cita)
     */
    public function updateAppointment($id)
    {
        
        $appointment = $this->appointmentRepo->update($id, request()->all());
        
        $appointment['patient'] = $appointment->patient;
        $appointment['user'] = $appointment->user;

        return $appointment;

    }

     /**
     * Eliminar consulta(cita) ajax desde calendar
     */
    public function deleteAppointment($id)
    {

        $appointment = $this->appointmentRepo->delete($id);
        
        if($appointment !== true)  return $appointment; //no se elimino correctamente

        return '';

    }


    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getAppointments($id)
    {

        $appointments = $this->appointmentRepo->findAllByDoctorWithoutPagination($id);
        
        return $appointments;
        
    }

    /**
     * Guardar paciente
     */
    public function storePatient(PatientRequest $request)
    {
        
        $patient =$this->patientRepo->store($request->all());



        return $patient;

    }

    /**
     * Actualizar Paciente
     */
    public function updatePatient($id, PatientRequest $request)
    {
       
        $patient = $this->patientRepo->update($id, $request->all());
        

        return $patient;

    }
}
