<?php

namespace App\Console\Commands;

use App\Repositories\IncomeRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class MonthlyCharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consulta:monthlyCharge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cobro mensual por la citas atendidas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(IncomeRepository $incomeRepo)
    {
        parent::__construct();

        $this->incomeRepo = $incomeRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $medics = User::whereHas('roles', function ($query) {
                        $query->where('name',  'medico');
                    })->where('active', 1)->has('subscription')->with(['incomes' => function ($query){
                                $query->where('type', 'I')
                                ->orWhere('type', 'P');
                            }])->with('subscription')->get();

       
        $countMedics = 0;

        $month = Carbon::now()->subMonth()->month;
        $year = Carbon::now()->subMonth()->year;
       

        $currentDate = Carbon::now()->setTime(0,0,0);
        
        foreach($medics as $medic)
        {
          
            //dd($medic->subscription->ends_at->setTime(0,0,0)->gte($currentDate));
            if($medic->subscription->ends_at->setTime(0,0,0)->gte($currentDate)) //la fecha de la subscripcion es mayor o igual a la fecha actual
            {
                $dateStart = $medic->subscription->ends_at->subMonths($medic->subscription->quantity)->setTime(0,0,0);
                $dateEnd = $medic->subscription->ends_at->setTime(0,0,0);
                
               

                $incomes = $medic->incomes()->where([['date', '>=', $dateStart],
                    ['date', '<=', $dateEnd]])->where('type','I')->get();

                $incomesPending = $medic->incomes()->where([['date', '>=', $dateStart],
                    ['date', '<=', $dateEnd]])->where('type','P')->get();

                
                 $totalCharge = $incomes->sum('amount');
                 $totalChargePending = $incomesPending->sum('amount');
                 $monthlyPlanCharge = floatval($medic->subscription->cost);
                
                 

                $dataIncome['type'] = 'M';
                $dataIncome['medic_type'] = 'A';
                $dataIncome['amount'] = $monthlyPlanCharge + $totalCharge;
                $dataIncome['pending'] = $totalChargePending;
                $dataIncome['appointment_id'] = 0;
                $dataIncome['office_id'] = 0;
                $dataIncome['date'] = Carbon::now()->toDateString();
                $dataIncome['month'] = Carbon::now()->month;
                $dataIncome['year'] = Carbon::now()->year;
                $dataIncome['period_from'] = $dateStart->toDateString();
                $dataIncome['period_to'] = $dateEnd->toDateString();
                $dataIncome['subscription_cost'] = $monthlyPlanCharge;



                $income = $this->incomeRepo->store($dataIncome, $medic->id);

               

                
               $this->info('Total a cobrar , ' . $totalCharge.' medico:'.$medic->name. 'subscripcion: '. $monthlyPlanCharge);

               $countMedics++;
                
               // dd('subscripcion vencida');
            }

                
            

        }

        Log::info( $countMedics.' cobros' );
        $this->info('Hecho, ' . $countMedics.' cobros de medicos');

       
    }
}
