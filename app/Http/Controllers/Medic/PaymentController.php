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

        $updatedIncomes = \DB::table('incomes')
            ->where('month', $income->month)
            ->where('year', $income->year)
            ->update(['paid' => 1]);


        return back();

    }

   
   

}
