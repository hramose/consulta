<?php

namespace App\Console\Commands;

use App\Repositories\IncomeRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Income;

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
            $query->where('name', 'medico');
        })->where('active', 1)->has('subscription')->with(['incomes' => function ($query) {
            $query->where('type', 'I')
                                ->orWhere('type', 'P');
        }])->with('subscription')->get();

        $countMedics = 0;

        $month = Carbon::now()->subMonth()->month;
        $year = Carbon::now()->subMonth()->year;

        $currentDate = Carbon::now()->setTime(0, 0, 0);

        foreach ($medics as $medic) {
          
            $incomes = $medic->incomes()->where('month', $month)->where('year', $year)->where('type', 'I')->get();

            $incomesPending = $medic->incomes()->where('month', $month)->where('year', $year)->where('type', 'P')->get();

            $totalCharge = $incomes->sum('amount');
            $totalChargePending = $incomesPending->sum('amount');

            if ($totalCharge > 0) {

                $dataIncome['type'] = 'M';
                $dataIncome['medic_type'] = 'A';
                $dataIncome['amount'] = $totalCharge;
                $dataIncome['pending'] = $totalChargePending;
                $dataIncome['appointment_id'] = 0;
                $dataIncome['office_id'] = 0;
                $dataIncome['date'] = Carbon::now()->toDateString();
                $dataIncome['month'] = Carbon::now()->month;
                $dataIncome['year'] = Carbon::now()->year;
                $dataIncome['description'] = 'Cobro mensual por cita atentida';

                $this->incomeRepo->store($dataIncome, $medic->id);


                $countMedics++;

                $this->info('Total a cobrar: ' . $totalCharge . ' medico: ' . $medic->name);



            }


            
        }

        Log::info($countMedics . ' cobros por cita atendida');
        $this->info('Hecho, ' . $countMedics . ' cobros de citas atendidas por cata medico');
    }
}
