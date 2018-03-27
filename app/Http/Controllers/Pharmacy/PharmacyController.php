<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Repositories\PharmacyRepository;

class PharmacyController extends Controller
{
    
    function __construct(PharmacyRepository $pharmacyRepo)
    {
    	
        $this->middleware('auth');
    	$this->pharmacyRepo = $pharmacyRepo;

        $this->administrators = User::whereHas('roles', function ($query){
                        $query->where('name',  'administrador');
                    })->get();

    }

   
    public function updatePharmacyNotification($id)
    {
        /*$this->validate(request(),[
                'notification_date' => 'required',      
        ]);*/

        $data = request()->all();
        $data['notification_date'] = '0000-00-00 00:00:00';

        $pharmacy = $this->pharmacyRepo->update($id,  $data);

        return $pharmacy;
        //return redirect()->back();

    }
   

}
