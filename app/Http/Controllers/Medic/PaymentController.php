<?php

namespace App\Http\Controllers\Medic;


use App\Http\Controllers\Controller;
use App\Repositories\IncomeRepository;
use Illuminate\Http\Request;


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
        $income->paid = 1;
        $income->save();

        /*$updatedIncomes = \DB::table('incomes')
            ->where('month', $income->month)
            ->where('year', $income->year)
            ->update(['paid' => 1]);*/


        return back();

    }

    /**
     * Guardar consulta(cita)
     */
    public function details($id)
    {

        $income = $this->incomeRepo->findById($id);
        $user = $income->medic;

        $incomesAttented = $user->incomes()->where('type','I')->where('month', $income->month)->where('year', $income->year);
        $incomesPending = $user->incomes()->where('type','P')->where('month', $income->month)->where('year', $income->year);

        $medicData = [
            'id' =>  $user->id,
            'name' =>  $user->name,
            'attented'=> $incomesAttented->count(),
            'attented_amount' => $incomesAttented->sum('amount'),
            'pending' => $incomesPending->count(),
            'pending_amount' => $incomesPending->sum('amount'),
            'monthly_payment' => getAmountPerExpedientUse(),
            
        ];


        return $medicData;

    }


   
   

}
