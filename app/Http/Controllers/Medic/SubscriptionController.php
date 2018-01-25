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

        // $user->subscription()->create([
        //     'plan_id' => $newPlan->id,
        //     'cost' => $newPlan->cost,
        //     'quantity' => $newPlan->quantity,
        //     'ends_at' => Carbon::now()->addMonths($newPlan->quantity)

        // ]);
        
        $amountTotal = $newPlan->cost;
        $description = $newPlan->description;

        $purchaseOperationNumber = getUniqueNumber();
        $amount = fillZeroRightNumber($amountTotal);
        $purchaseCurrencyCode = env('CURRENCY_CODE');
        $purchaseVerification = getPurchaseVerfication($purchaseOperationNumber, $amount, $purchaseCurrencyCode);

        $medic_name = $user->name;
        $medic_email = $user->email;

        return view('medic.payments.buySubscription')->with(compact('newPlan', 'purchaseOperationNumber', 'amount', 'amountTotal', 'purchaseOperationNumber', 'purchaseCurrencyCode', 'purchaseVerification', 'medic_name', 'medic_email', 'description'));

    }

    
    
}
