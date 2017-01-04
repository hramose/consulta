<?php

namespace App\Http\Controllers;

use App\DiseaseNote;
use App\Repositories\AppointmentRepository;
use Illuminate\Http\Request;

class DiseaseNoteController extends Controller
{
    function __construct(AppointmentRepository $appointmentRepo)
    {
    	 $this->middleware('auth');
    	 $this->appointmentRepo = $appointmentRepo;
    }
    public function update($id)
    {
       
        $diseaseNote = DiseaseNote::findOrFail($id);

        $diseaseNote->fill(request('data'));
        $diseaseNote->save();
        
       return '';
    }
}
