<?php

namespace App\Http\Controllers\Medic;

use App\Allergy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AllergyController extends Controller
{
    function __construct()
    {
    	 $this->middleware('auth');
    	 
    }

    public function store()
    {
        $data = request()->all();

        $data['user_id'] = auth()->id();
       
        $allergy = Allergy::create($data);
        $allergy->load('user');
     
        return $allergy;
     
    }
    public function destroy($id)
    {
       
        $allergy = Allergy::findOrFail($id)->delete($id);

     
        return '';
     
    }
}
