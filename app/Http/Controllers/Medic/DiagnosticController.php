<?php

namespace App\Http\Controllers\Medic;

use App\Diagnostic;
use App\Http\Controllers\Controller;
use App\Repositories\AppointmentRepository;
use Illuminate\Http\Request;

class DiagnosticController extends Controller
{
    function __construct(AppointmentRepository $appointmentRepo)
    {
    	 $this->middleware('auth');
    	 $this->appointmentRepo = $appointmentRepo;
    }

    public function store()
    {
       
        $diagnostic = Diagnostic::create(request()->all());

     
        return $diagnostic;
     
    }
    public function destroy($id)
    {
       
        $diagnostic = Diagnostic::findOrFail($id)->delete($id);

     
        return '';
     
    }
}
