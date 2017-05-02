<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Repositories\AppointmentRepository;
use App\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    function __construct(AppointmentRepository $appointmentRepo)
    {
    	 $this->middleware('auth');
    	 $this->appointmentRepo = $appointmentRepo;
    }

    public function store()
    {
       
        $treatment = Treatment::create(request()->all());

     
        return $treatment;
     
    }
    public function destroy($id)
    {
       
        $treatment = Treatment::findOrFail($id)->delete($id);

     
        return '';
     
    }
}
