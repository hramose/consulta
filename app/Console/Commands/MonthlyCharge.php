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
                    })->where('active', 1)->with(['incomes' => function ($query){
                                $query->where('type', 'I');
                            }])->get();

        
        $countMedics = 0;

        $month = Carbon::now()->subMonth()->month;
        $year = (Carbon::now()->month == 1) ? Carbon::now()->subyear()->year : Carbon::now()->year;

        $currentMonth =  Carbon::now()->month;
        $currentyear = Carbon::now()->year;

        foreach($medics as $medic)
        {
           
            //if(!$medic->settings->trial){ // si no esta en periodo de prueba ejecute el pago

                $incomes = $medic->incomes()->where(\DB::raw('MONTH(date)'), '=', $month)->where(\DB::raw('YEAR(date)'), '=', $year)->get();
                
                $totalCharge = $incomes->sum('amount');
             
                if($totalCharge > 0)
                {
                    $dataIncome['type'] = 'M';
                    $dataIncome['medic_type'] = 'A';
                    $dataIncome['amount'] = $totalCharge;
                    $dataIncome['appointment_id'] = 0;
                    $dataIncome['date'] = Carbon::now();
                    $dataIncome['month'] = $month;
                    $dataIncome['year'] = $year;



                    $income = $this->incomeRepo->store($dataIncome, $medic->id);
                    
                    $this->info('Total a cobrar , ' . $totalCharge.' medico:'.$medic->name);

                    $countMedics++;
                }
                

          //   }

                
            

        }

        Log::info( $countMedics.' cobros' );
        $this->info('Hecho, ' . $countMedics.' cobros de medicos');

       
    }
}
