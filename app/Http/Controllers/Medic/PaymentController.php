<?php

namespace App\Http\Controllers\Medic;


use App\Http\Controllers\Controller;
use App\Repositories\IncomeRepository;
use Illuminate\Http\Request;
use App\Plan;
use Carbon\Carbon;


class PaymentController extends Controller
{
    
    function __construct(IncomeRepository $incomeRepo)
    {
    	
        $this->middleware('auth');
        $this->incomeRepo = $incomeRepo;

        $this->acquirerId = env('ACQUIRE_ID');
        $this->commerceId = env('COMMERCE_ID');
        $this->mallId = env('MALL_ID');
        $this->purchaseCurrencyCode = env('CURRENCY_CODE');
        $this->terminalCode = env('TERMINAL_CODE');
        $this->claveSHA2 = env('CLAVE_SHA2');
        
        
      
       // $purchaseOperationNumber = '000000047';
      //  $purchaseAmount = '10000';
      //  $purchaseCurrencyCode = '840';
			
      
          
       // $purchaseVerification = openssl_digest($acquirerId . $idCommerce . $purchaseOperationNumber . $purchaseAmount . $purchaseCurrencyCode . $claveSecreta, 'sha512');    
       

    }

    

    /**
     * Guardar consulta(cita)
     */
    public function pay($id)
    {
        //$purchaseOperationNumber = $this->getUniqueNumber();
        dd(request()->all());
        $income = $this->incomeRepo->findById($id);
        $medic = $income->medic;
        //$income->paid = 1;
        //$income->save();

        $purchaseOperationNumber = str_pad($income->id, 9, "0", STR_PAD_LEFT);
        
        dd($purchaseOperationNumber);


        $plan = Plan::find($medic->subscription->plan_id);
        $subscription = $medic->subscription;

        $subscription->cost = $plan->cost;
        $subscription->quantity = $plan->quantity;
        $subscription->ends_at =  Carbon::parse($income->period_to)->addMonths($plan->quantity);
        $subscription->save();


        return back();

    }

    /**
     * Guardar consulta(cita)
     */
    public function details($id)
    {

        $income = $this->incomeRepo->findById($id);
        $medic = $income->medic;

        
         $medicData = [];
        //if($medic->subscription){

            //$dateStart = Carbon::parse($income->period_from)->setTime(0,0,0);
            //$dateEnd = Carbon::parse($income->period_to)->setTime(0,0,0);
            $month = Carbon::parse($income->date)->subMonth()->month;
            $year = Carbon::parse($income->date)->subMonth()->year;

            $incomesAttented = $medic->incomes()->where('month', $month)->where('year', $year)->where('type', 'I')->get();

            $incomesPending = $medic->incomes()->where('month', $month)->where('year', $year)->where('type', 'P')->get();
            // $incomesAttented = $medic->incomes()->where([['date', '>=', $dateStart],
            //     ['date', '<=', $dateEnd]])->where('type','I');

            // $incomesPending = $medic->incomes()->where([['date', '>=', $dateStart],
            //     ['date', '<=', $dateEnd]])->where('type','P');

            $medicData = [
                'id' =>  $medic->id,
                'name' =>  $medic->name,
                'attented'=> $incomesAttented->count(),
                'attented_amount' => $incomesAttented->sum('amount'),
                'pending' => $incomesPending->count(),
                'pending_amount' => $incomesPending->sum('amount'),
                'amountByAttended' => getAmountPerAppointmentAttended(),
                'month' => $month .' - '. $year,
                
            ];
  //  }


        return $medicData;

    }

    

   
   

}
