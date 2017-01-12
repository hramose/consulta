<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\VitalSign;
use Illuminate\Http\Request;

class VitalSignController extends Controller
{
     function __construct()
    {
    	 $this->middleware('auth');
    	
    }
    public function update($id)
    {
       
        $this->validate(request(), [
            'height' => 'numeric',
            'weight' => 'numeric',
            'mass' => 'numeric',
            'temp' => 'numeric',
            'respiratory_rate' => 'numeric',
            'blood' => 'numeric',
            'heart_rate' => 'numeric'
           
        ]);

        $vitalSigns = VitalSign::findOrFail($id);

        $vitalSigns->fill(request()->all());
        $vitalSigns->save();
        
       return '';
    }
}
