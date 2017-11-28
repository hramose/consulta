<?php namespace App\Http\Controllers\Medic;



use App\Http\Controllers\Controller;
use App\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth');
    	
    }

    

     /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function list()
    {

        $plans = Plan::all();

        return $plans;
        
    }

    
    
}
