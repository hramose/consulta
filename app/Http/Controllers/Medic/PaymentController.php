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
       

    }

    

    /**
     * Guardar consulta(cita)
     */
    public function pay($id)
    {

        $income = $this->incomeRepo->findById($id);
        $medic = $income->medic;
        $income->paid = 1;
        $income->save();
        


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

        //$incomesAttented = $user->incomes()->where('type','I')->where('month', $income->month)->where('year', $income->year);
        //$incomesPending = $user->incomes()->where('type','P')->where('month', $income->month)->where('year', $income->year);
         $medicData = [];
        if($medic->subscription){

            $dateStart = Carbon::parse($income->period_from)->setTime(0,0,0);
            $dateEnd = Carbon::parse($income->period_to)->setTime(0,0,0);
                    
                

            $incomesAttented = $medic->incomes()->where([['date', '>=', $dateStart],
                ['date', '<=', $dateEnd]])->where('type','I');

            $incomesPending = $medic->incomes()->where([['date', '>=', $dateStart],
                ['date', '<=', $dateEnd]])->where('type','P');

            $medicData = [
                'id' =>  $medic->id,
                'name' =>  $medic->name,
                'attented'=> $incomesAttented->count(),
                'attented_amount' => $incomesAttented->sum('amount'),
                'pending' => $incomesPending->count(),
                'pending_amount' => $incomesPending->sum('amount'),
                'monthly_payment' => $income->subscription_cost,
                
            ];
    }


        return $medicData;

    }


   
   

}
