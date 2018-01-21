<?php

namespace App\Console\Commands;

use App\Repositories\IncomeRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Income;
use App\Plan;

class SubscriptionCharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consulta:subscriptionCharge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cobro de subscripción';

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
        })->where('active', 1)->has('subscription')->with('subscription')->get();

        $countMedics = 0;

        $currentDate = Carbon::now()->setTime(0, 0, 0);

        foreach ($medics as $medic) {
            //dd($medic->subscription->ends_at->setTime(0, 0, 0) . '---' . $currentDate->addMonths($medic->subscription->quantity));
            //dd($medic->subscription->ends_at->setTime(0, 0, 0)->eq($currentDate->addMonths($medic->subscription->quantity)));

            $monthlyPlanCharge = 0;

            if ($medic->subscription->ends_at->setTime(0, 0, 0)->eq($currentDate)) { //la fecha de la subs de finalizado es igual a la fecha actual
                $dateStart = $medic->subscription->ends_at->subMonths($medic->subscription->quantity)->setTime(0, 0, 0);
                $dateEnd = $medic->subscription->ends_at->setTime(0, 0, 0);

                $monthlyPlanCharge = floatval($medic->subscription->cost);
                $plan = Plan::find($medic->subscription->plan_id);

                $dataIncome['type'] = 'MS';
                $dataIncome['medic_type'] = 'A';
                $dataIncome['amount'] = $monthlyPlanCharge;
                $dataIncome['appointment_id'] = 0;
                $dataIncome['office_id'] = 0;
                $dataIncome['date'] = Carbon::now()->toDateString();
                $dataIncome['month'] = Carbon::now()->month;
                $dataIncome['year'] = Carbon::now()->year;
                $dataIncome['period_from'] = $dateStart->toDateString();
                $dataIncome['period_to'] = $dateEnd->toDateString();
                $dataIncome['subscription_cost'] = $monthlyPlanCharge;
                $dataIncome['description'] = 'Cobro por subscripción de ' . $plan->title;

                $this->incomeRepo->store($dataIncome, $medic->id);

                // $newIncome = Income::create($dataIncome);
                // $medic->incomes()->save($newIncome);

                $countMedics++;

                $this->info('Total a cobrar: ' . $monthlyPlanCharge . ' medico: ' . $medic->name . ' subscripcion: ' . $monthlyPlanCharge);
            }
        }

        Log::info($countMedics . ' cobros de subscripciones');
        $this->info('Hecho, ' . $countMedics . ' cobros de subscripciones de medicos');
    }
}
