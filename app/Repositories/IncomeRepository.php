<?php

namespace App\Repositories;

use App\Income;
use App\Office;
use App\User;
use App\Plan;
use App\Subscription;
use Carbon\Carbon;

class IncomeRepository extends DbRepository
{
    /**
     * Construct
     * @param User $model
     */
    public function __construct(Income $model)
    {
        $this->model = $model;
        $this->limit = 10;
    }

    /**
     * save a appointment
     * @param $data
     */
    public function store($data, $user_id = null)
    {
        $medic = User::find(($user_id) ? $user_id : auth()->id()); // buscar doctor
        $income = $this->model->create($data);
        $income = $medic->incomes()->save($income);

        /* $income = $this->model;
         $income->appointment_id = $data['appointment_id'];
         $income->office_id = $data['office_id'];
         $income->type = $data['type'];
         $income->medic_type = $data['medic_type'];
         $income->date = $data['date'];
         $income->month = $data['month'];
         $income->year = $data['year'];

         $income->amount = $data['amount'];

         if (isset($data['description'])) {
             $income->description = $data['description'];
         }

         if (isset($data['pending'])) {
             $income->pending = $data['pending'];
         }

         if (isset($data['period_from'])) {
             $income->period_from = $data['period_from'];
         }

         if (isset($data['period_to'])) {
             $income->period_to = $data['period_to'];
         }

         if (isset($data['subscription_cost'])) {
             $income->subscription_cost = $data['subscription_cost'];
         }

         $income = $medic->incomes()->save($income);*/

        return $income;
    }

    /**
     * Find all the appointments by Doctor
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAllByDoctor($id, $search = null, $limit = 5)
    {
        $order = 'date';
        $dir = 'desc';

        $incomes = $this->model->where('user_id', $id);

        if (!count($search) > 0) {
            return $incomes->with('user', 'appointment')->orderBy('incomes.' . $order, $dir)->paginate($limit);
        }

        if (isset($search['type']) && $search['type'] != '') {
            $incomes = $incomes->where('type', $search['type']);
        }

        if (isset($search['paid']) && $search['paid'] != '') {
            $incomes = $incomes->where('paid', $search['paid']);
        }

        if (isset($search['date']) && $search['date'] != '') {
            $incomes = $incomes->whereDate('date', $search['date']);
        }

        if (isset($search['order']) && $search['order'] != '') {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != '') {
            $dir = $search['dir'];
        }

        return $incomes->with('user', 'appointment')->orderBy('incomes.' . $order, $dir)->paginate($limit);
    }

    /**
     * Get all the appointments for the admin panel
     * @param $search
     * @return mixed
     */
    public function reportsStatistics($search)
    {
        $order = 'created_at';
        $dir = 'desc';

        $medics = [];

        $date1 = new Carbon($search['date1']);
        $date2 = (isset($search['date2']) && $search['date2'] != '') ? $search['date2'] : $search['date1'];
        $date2 = new Carbon($date2);

        $medics = User::with('incomes')->whereHas('roles', function ($q) {
            $q->where('name', 'medico');
        })->where('active', 1)->get();

        $medicsArray = [];
        $totalAttended = 0;
        $totalPending = 0;
        foreach ($medics as $medic) {
            $incomesAttented = $medic->incomes()->where([['incomes.date', '>=', $date1],
                ['incomes.date', '<=', $date2->endOfDay()]])->where('type', 'I');

            $incomesPending = $medic->incomes()->where([['incomes.date', '>=', $date1],
            ['incomes.date', '<=', $date2->endOfDay()]])->where('type', 'P');

            $totalMedicAttended = $incomesAttented->sum('amount');
            $totalMedicPending = $incomesPending->sum('amount');

            $medicData = [
                'id' => $medic->id,
                'name' => $medic->name,
                'attented' => $incomesAttented->count(),
                'attented_amount' => $totalMedicAttended,
                'pending' => $incomesPending->count(),
                'pending_amount' => $totalMedicPending,
            ];

            $medicsArray[] = $medicData;

            $totalAttended += $totalMedicAttended;
            $totalPending += $totalMedicPending;
        }

        $attended = [
            'medics' => $medicsArray,
            'totalAttended' => $totalAttended,
            'totalPending' => $totalPending,
        ];

        $plans = Plan::all();

        foreach ($plans as $plan) {
            $subscriptions = Subscription::where('plan_id', $plan->id)->count();

            $planData = [
                'title' => $plan->title,
                'medics' => $subscriptions,
                'cost' => $plan->cost,
                'total' => $subscriptions * $plan->cost,
            ];

            $medicsPlans[] = $planData;
        }

        $statistics = [
            'medicsPlans' => $medicsPlans,
            'individualByAppointmentAttended' => $attended
        ];

        return $statistics;
    }

    /**
     * Get all the appointments for the admin panel
     * @param $search
     * @return mixed
     */
    public function reportsStatisticsByClinic($search)
    {
        $order = 'created_at';
        $dir = 'desc';

        $medics = [];

        $date1 = new Carbon($search['date1']);
        $date2 = (isset($search['date2']) && $search['date2'] != '') ? $search['date2'] : $search['date1'];
        $date2 = new Carbon($date2);

        $medics = Office::find($search['clinic'])->medicsWithIncomes($date1, $date2);

        if (isset($search['medic']) && $search['medic'] != '') { //si es por clinica y por medico individual
            $medics = $medics->where('id', $search['medic']);
        }

        $expedient = [
            'medics' => $medics->count(),
            'monthly_payment' => getAmountPerExpedientUse(),
            'total' => $medics->count() * getAmountPerExpedientUse(),
        ];

        $medicsArray = [];
        $totalAttended = 0;
        $totalPending = 0;
        foreach ($medics as $medic) {
            $incomesAttented = $medic->incomes()->where('office_id', $search['clinic'])->where([['incomes.date', '>=', $date1],
                ['incomes.date', '<=', $date2->endOfDay()]])->where('type', 'I');

            $incomesPending = $medic->incomes()->where('office_id', $search['clinic'])->where([['incomes.date', '>=', $date1],
            ['incomes.date', '<=', $date2->endOfDay()]])->where('type', 'P');

            $invoicesBilled = $medic->invoices()->where('office_id', $search['clinic'])->where([
                ['invoices.created_at', '>=', $date1],
                ['incomes.created_at', '<=', $date2->endOfDay()]
            ])->where('status', 1);

            $totalMedicAttended = $incomesAttented->sum('amount');
            $totalMedicPending = $incomesPending->sum('amount');
            $totalMedicBilled = $invoicesBilled->sum('total');
            $totalMedicCommissionBilled = $totalMedicBilled * ($medic->commission / 100);

            $medicData = [
                'id' => $medic->id,
                'name' => $medic->name,
                'commission' => $medic->commission,
                'attented' => $incomesAttented->count(),
                'attented_amount' => $totalMedicAttended,
                'pending' => $incomesPending->count(),
                'pending_amount' => $totalMedicPending,
                'billed' => $invoicesBilled->count(),
                'billed_amount' => $totalMedicBilled,
                'billed_commission_amount' => $totalMedicCommissionBilled,
            ];

            $medicsArray[] = $medicData;

            $totalAttended += $totalMedicAttended;
            $totalPending += $totalMedicPending;
            $totalBilled += $totalMedicBilled;
            $totalBilledCommission += $totalMedicCommissionBilled;
        }

        $attended = [
            'medics' => $medicsArray,
            'totalAttended' => $totalAttended,
            'totalPending' => $totalPending,
        ];

        $billed = [
            'medics' => $medicsArray,
            'totalBilled' => $totalBilled,
            'totalBilledCommission' => $totalBilledCommission,
           
        ];

        $statistics = [
            'generalByExpedientUse' => $expedient,
            'individualByAppointmentAttended' => $attended,
            'individualByinvoiceBilled' => $billed
        ];

        return $statistics;
    }

    /**
    * Get all the appointments for the admin panel
    * @param $search
    * @return mixed
    */
    public function reportByMedic($search)
    {
        $medic = User::find($search['medic']);
        $medicData = [];

        if (isset($search['date1']) && $search['date1'] != '') {
            $date1 = new Carbon($search['date1']);
            $date2 = (isset($search['date2']) && $search['date2'] != '') ? $search['date2'] : $search['date1'];
            $date2 = new Carbon($date2);

            $incomesAttented = $medic->incomes()->where([['incomes.date', '>=', $date1],
                    ['incomes.date', '<=', $date2->endOfDay()]])->where('type', 'I');

            $incomesPending = $medic->incomes()->where([['incomes.date', '>=', $date1],
                ['incomes.date', '<=', $date2->endOfDay()]])->where('type', 'P');

            $totalMedicAttended = $incomesAttented->sum('amount');
            $totalMedicPending = $incomesPending->sum('amount');

            $medicData = [
                'id' => $medic->id,
                'name' => $medic->name,
                'attented' => $incomesAttented->count(),
                'attented_amount' => $totalMedicAttended,
                'pending' => $incomesPending->count(),
                'pending_amount' => $totalMedicAttended,
                'monthly_payment' => ($medic->subscription) ? $medic->subscription->cost : 0,
            ];
        }

        return $medicData;
    }

    private function prepareData($data)
    {
        return $data;
    }
}
