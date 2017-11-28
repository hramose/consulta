<?php namespace App\Http\Controllers\Medic;



use App\Http\Controllers\Controller;
use App\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

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

     /**
     * Guardar consulta(cita)
     */
    public function buy($id)
    {

         $user = auth()->user();
         $newPlan = Plan::find($id);

        $user->subscription()->create([
            'plan_id' => $newPlan->id,
            'cost' => $newPlan->cost,
            'quantity' => $newPlan->quantity,
            'ends_at' => Carbon::now()->addMonths($newPlan->quantity)

        ]);

        return back();

    }

    
    
}
