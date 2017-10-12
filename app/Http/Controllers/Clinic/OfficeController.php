<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Office;
use App\Repositories\OfficeRepository;
use App\User;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    
    function __construct(OfficeRepository $officeRepo)
    {
    	
        $this->middleware('auth');
    	$this->officeRepo = $officeRepo;

        $this->administrators = User::whereHas('roles', function ($query){
                        $query->where('name',  'administrador');
                    })->get();

    }

   
    public function updateOfficeNotification($id)
    {
        /*$this->validate(request(),[
                'notification_date' => 'required',      
        ]);*/

        $data = request()->all();
        $data['notification_date'] = '0000-00-00 00:00:00';

        $office = $this->officeRepo->update($id,  $data);

        return $office;
        //return redirect()->back();

    }
   

}
