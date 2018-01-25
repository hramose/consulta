<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Repositories\IncomeRepository;
use Illuminate\Http\Request;
use App\Plan;
use Carbon\Carbon;
use App\Income;

class PaymentController extends Controller
{
    public function __construct(IncomeRepository $incomeRepo)
    {
        $this->middleware('auth');
        $this->incomeRepo = $incomeRepo;

        $this->acquirerId = env('ACQUIRE_ID');
        $this->commerceId = env('COMMERCE_ID');
        $this->mallId = env('MALL_ID');
        $this->purchaseCurrencyCode = env('CURRENCY_CODE');
        $this->terminalCode = env('TERMINAL_CODE');
        $this->claveSHA2 = env('CLAVE_SHA2');
    }

    public function create($id = null)
    {
        if ($id) {
            $incomes = Income::where('id', $id)->where('user_id', auth()->id())->where(function ($query) {
                $query->where('type', 'M') // por cita atendida
                    ->orWhere('type', 'MS'); // por subscripcion de paquete
            })->where('paid', 0)->get();
        } else {
            $incomes = auth()->user()->monthlyCharge();
        }

        $amountTotal = $incomes->sum('amount');
        $incomesIds = $incomes->pluck('id')->implode(',');
        $description = $incomes->pluck('description')->implode(',');

        $purchaseOperationNumber = getUniqueNumber();
        $amount = fillZeroRightNumber($amountTotal);
        $purchaseCurrencyCode = env('CURRENCY_CODE');
        $purchaseVerification = getPurchaseVerfication($purchaseOperationNumber, $amount, $purchaseCurrencyCode);

        $medic_name = auth()->user()->name;
        $medic_email = auth()->user()->email;

        return view('medic.payments.create')->with(compact('incomes', 'purchaseOperationNumber', 'amount', 'amountTotal', 'purchaseOperationNumber', 'purchaseCurrencyCode', 'purchaseVerification', 'medic_name', 'medic_email', 'incomesIds', 'description'));
    }

    /**
     * Purchase VPOS Response
     * @param Request $request
     * @return $this
     */
    public function purchaseResponse()
    {
        //purchaseVerication que devuelve la Pasarela de Pagos
        $purchaseVericationVPOS2 = request('purchaseVerification');
        \Log::info('purchaseVerication VPOS: ' . $purchaseVericationVPOS2);

        //purchaseVerication que genera el comercio
        $purchaseVericationComercio = openssl_digest(request('acquirerId') . request('idCommerce') . request('purchaseOperationNumber') . request('purchaseAmount') . request('purchaseCurrencyCode') . request('authorizationResult') . $this->claveSHA2, 'sha512');

        \Log::info('purchaseVerication Comercio: ' . $purchaseVericationComercio);
        //Si ambos datos son iguales
        if ($purchaseVericationVPOS2 == $purchaseVericationComercio || $purchaseVericationVPOS2 == '') {
            $authorizationResult = request('authorizationResult');
            $authorizationCode = request('authorizationCode');
            $errorCode = request('errorCode');
            $errorMessage = request('errorMessage');
            $bin = request('bin');
            $brand = request('brand');
            $paymentReferenceCode = request('paymentReferenceCode');
            $reserved1 = request('reserved1');
            $reserved2 = request('reserved2'); // income id or ids or plan id
            $reserved3 = request('reserved3'); // compra de plan o subscripcion
            $reserved22 = request('reserved22');
            $reserved23 = request('reserved23');
            $purchaseOperationNumber = request('purchaseOperationNumber');
            $total = request('purchaseAmount') / 100;
            $income = null;
           
           

            if ($authorizationResult == 00) {
                //actualizamos la operacion en db
                if($reserved3 && $reserved3 == 1) // es la compra de una subscripcion
                {
                    $plan = Plan::find($reserved2);

                    auth()->user()->subscription()->create([
                        'plan_id' => $plan->id,
                        'cost' => $plan->cost,
                        'quantity' => $plan->quantity,
                        'ends_at' => Carbon::now()->addMonths($plan->quantity)

                    ]);

                   

                }else{
                    
                    $incomesIds = explode(',', $reserved2);

                    $income = $this->incomeRepo->findById(trim($incomesIds[0]));
                    $incomes = Income::whereIn('id', $incomesIds)->get();

                    $medic = $income->medic;

                    \DB::table('incomes')
                        ->whereIn('id', $incomesIds)
                        ->update(['paid' => 1, 'purchase_operation_number' => $purchaseOperationNumber]);

                    // informamos via email del producto recien creado y su confirmacion de pago
                    // try {

                    //     $this->mailer->paymentConfirmation(['email' => $medic->email, 'servicio' => $income->description, 'purchaseOperationNumber' => $purchaseOperationNumber, 'total' => $total]);

                    // } catch (\Swift_TransportException $e)  //Swift_RfcComplianceException
                    // {
                    //     \Log::error($e->getMessage());
                    // }

                    
                }

                flash('Pago realizado con exito', 'success');
            }
            if ($authorizationResult == 01) {
                flash('La operación ha sido denegada en el Banco Emisor', 'error');
            }
            if ($authorizationResult == 05) {
                flash('La operación ha sido rechazada', 'error');
            }
        } else {
            \Log::info('Transacción Invalida. Los datos fueron alterados en el proceso de respuesta');
        }

        \Log::info('results of VPOS: ' . json_encode(request()->all()));

        if ($reserved3 && $reserved3 == 1){

            return view('medic.payments.responseSubscription')->with(compact('authorizationCode', 'total', 'authorizationResult', 'purchaseOperationNumber', 'errorCode', 'errorMessage', 'plan'));

        }else{

            return view('medic.payments.response')->with(compact('authorizationCode', 'total', 'authorizationResult', 'purchaseOperationNumber', 'errorCode', 'errorMessage', 'income', 'incomes'));
        }
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

        $purchaseOperationNumber = str_pad($income->id, 9, '0', STR_PAD_LEFT);

        dd($purchaseOperationNumber);

        $plan = Plan::find($medic->subscription->plan_id);
        $subscription = $medic->subscription;

        $subscription->cost = $plan->cost;
        $subscription->quantity = $plan->quantity;
        $subscription->ends_at = Carbon::parse($income->period_to)->addMonths($plan->quantity);
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
                'id' => $medic->id,
                'name' => $medic->name,
                'attented' => $incomesAttented->count(),
                'attented_amount' => $incomesAttented->sum('amount'),
                'pending' => $incomesPending->count(),
                'pending_amount' => $incomesPending->sum('amount'),
                'amountByAttended' => getAmountPerAppointmentAttended(),
                'month' => $month . ' - ' . $year,
            ];
        //  }

        return $medicData;
    }
}
