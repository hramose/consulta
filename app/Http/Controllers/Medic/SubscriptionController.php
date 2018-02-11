<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Plan;
use Carbon\Carbon;
use App\Income;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
    * Lista de todas las citas de un doctor sin paginar
    */
    public function list()
    {
        $plans = Plan::all();

        return $plans;
    }

    /**
    * Guardar consulta(cita)
    */
    public function buy($id)
    {
        $user = auth()->user();

        if ($user->subscription) return Redirect('/');

        $newPlan = Plan::find($id);

        // $user->subscription()->create([
        //     'plan_id' => $newPlan->id,
        //     'cost' => $newPlan->cost,
        //     'quantity' => $newPlan->quantity,
        //     'ends_at' => Carbon::now()->addMonths($newPlan->quantity)

        // ]);

        $amountTotal = $newPlan->cost;
        $description = $newPlan->title;

        $purchaseOperationNumber = getUniqueNumber();
        $amount = fillZeroRightNumber($amountTotal);
        $purchaseCurrencyCode = env('CURRENCY_CODE');
        $purchaseVerification = getPurchaseVerfication($purchaseOperationNumber, $amount, $purchaseCurrencyCode);

        $medic_name = $user->name;
        $medic_email = $user->email;

        $planBuyChange = 1; // 1 compra de plan

        if (request('fe') == '1' || request('fe') == '0') { //actualizamos el campo si esta utilizando factura eletronica
            $user->fe = request('fe');
            $user->save();
        }

        return view('medic.payments.buySubscription')->with(compact('newPlan', 'purchaseOperationNumber', 'amount', 'amountTotal', 'purchaseOperationNumber', 'purchaseCurrencyCode', 'purchaseVerification', 'medic_name', 'medic_email', 'description', 'planBuyChange'));
    }

    /**
    * Guardar consulta(cita)
    */
    public function change($id)
    {
       
        $user = auth()->user();

        if(!$user->expiredSubscription()->count()) return Redirect('/');
        
        $newPlan = Plan::find($id);

        $amountTotal = $newPlan->cost;
        $description = $newPlan->title;

        $purchaseOperationNumber = getUniqueNumber();
        $amount = fillZeroRightNumber($amountTotal);
        $purchaseCurrencyCode = env('CURRENCY_CODE');
        $purchaseVerification = getPurchaseVerfication($purchaseOperationNumber, $amount, $purchaseCurrencyCode);

        $medic_name = $user->name;
        $medic_email = $user->email;

        $planBuyChange = 2; // 2 cambio de plan

        $income = Income::where('user_id', auth()->id())->where(function ($query) {
            $query->Where('type', 'MS'); // por subscripcion de paquete
        })->where('paid', 0)->first();

        if (request('fe') == '1' || request('fe') == '0') { //actualizamos el campo si esta utilizando factura eletronica
            $user->fe = request('fe');
            $user->save();
        }

        return view('medic.payments.buySubscription')->with(compact('newPlan', 'purchaseOperationNumber', 'amount', 'amountTotal', 'purchaseOperationNumber', 'purchaseCurrencyCode', 'purchaseVerification', 'medic_name', 'medic_email', 'description', 'planBuyChange', 'income'));
    }
}
