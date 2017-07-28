<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Repositories\AppointmentRepository;
use App\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    function __construct(AppointmentRepository $appointmentRepo)
    {
    	 $this->middleware('auth');
    	 $this->appointmentRepo = $appointmentRepo;
    }

    public function store()
    {
        $data = request()->all();
        $data['user_id'] = auth()->id();
        $note = Note::create($data);

     
        return $note;
     
    }
    public function destroy($id)
    {
       
        $note = Note::findOrFail($id)->delete($id);

     
        return '';
     
    }
}
