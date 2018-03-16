<?php 

use App\Configuration;
use Illuminate\Support\Facades\Session;

function money($amount, $symbol = '₡', $decimals = 2)
{
    return (!$symbol) ? number_format($amount, $decimals, '.', ',') : $symbol . number_format($amount, $decimals, '.', ',');
}
function numberFE($amount, $decimals = 3)
{
    return  number_format($amount, $decimals, '.', '');
}

function number($amount)
{
    return preg_replace('/([^0-9\\.])/i', '', $amount);
}
function percent($amount, $symbol = '%')
{
    return $symbol . number_format($amount, 0, '.', ',');
}
function age($birthdate)
{
    return $birthdate;
}
function flash($message, $level = 'info')
{
    session()->flash('flash_message', $message);
    session()->flash('flash_message_level', $level);
}
function paginate($items, $perPage)
{
    $pageStart = \Request::get('page', 1);
    $offSet = ($pageStart * $perPage) - $perPage;
    $itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);

    return new Illuminate\Pagination\LengthAwarePaginator(
        $itemsForCurrentPage,
        count($items),
        $perPage,
        Illuminate\Pagination\Paginator::resolveCurrentPage(),
        ['path' => Illuminate\Pagination\Paginator::resolveCurrentPath()]
    );
}
/*function set_active($path, $active = 'active') {
        return call_user_func_array('Request::is', (array)$path) ? $active : '';
    }*/

function set_active($path, $active = 'active')
{
    return Request::is($path) ? $active : '';
}

function dayName($day)
{
    $dayName = '';

    if (Carbon\Carbon::SUNDAY == $day) {                          // int(0)
        $dayName = 'Domingo';
    }

    if (Carbon\Carbon::MONDAY == $day) {                       // int(1)
        $dayName = 'Lunes';
    }

    if (Carbon\Carbon::TUESDAY == $day) {                         // int(2)
        $dayName = 'Martes';
    }

    if (Carbon\Carbon::WEDNESDAY == $day) {                       // int(3)
        $dayName = 'Miércoles';
    }

    if (Carbon\Carbon::THURSDAY == $day) {                       // int(4)
        $dayName = 'Jueves';
    }

    if (Carbon\Carbon::FRIDAY == $day) {                          // int(5)
        $dayName = 'Viernes';
    }

    if (Carbon\Carbon::SATURDAY == $day) {                        // int(6)
        $dayName = 'Sábado';
    }

    return $dayName;
}
function getPhoto($user)
{
    $url = '';

    if (Storage::disk('public')->exists('patients/' . $user->id . '/photo.jpg')) {
        $url = Storage::url('patients/' . $user->id . '/photo.jpg');
    } else {
        $url = '/img/default-avatar.jpg';
    }

    return $url;
}
function getAvatar($user)
{
    $url = '';

    if (Storage::disk('public')->exists('avatars/' . $user->id . '/avatar.jpg')) {
        $url = Storage::url('avatars/' . $user->id . '/avatar.jpg');
    } else {
        $url = '/img/default-avatar.jpg';
    }

    return $url;
}
function getLogo($clinic)
{
    $url = '';

    if ($clinic && Storage::disk('public')->exists('offices/' . $clinic->id . '/photo.jpg')) {
        $url = Storage::url('offices/' . $clinic->id . '/photo.jpg');
    } else {
        $url = '/img/logo.png';
    }

    return $url;
}
function existsCertFile($config)
{
    $resp = false;

    $cert = 'cert';
    if($config){
        if (Storage::disk('local')->exists('facturaelectronica/' . $config->id . '/' . $cert . '.p12')) {
            $resp = true;
        }
    }

    return $resp;
}
function existsCertTestFile($config)
{
    $resp = false;
    if ($config) {
        if (Storage::disk('local')->exists('facturaelectronica/' . $config->id . '/test.p12')) {
            $resp = true;
        }
    }

    return $resp;
}

/**
     * obtiene el monto que se cobra por cita atendita de los medicos generales
     *
     * @param $monto El monto a convertir
     * @return float El monto convertido
     */
    function getAmountPerAppointmentAttended()
    {
        $amount = 0;

        $amount = Configuration::first()->amount_attended;

        return $amount;
    }

    /**
     * obtiene el monto que se cobra por cita atendita de los medicos generales
     *
     * @param $monto El monto a convertir
     * @return float El monto convertido
     */
    function getAmountPerExpedientUse()
    {
        $amount = 0;

        $amount = Configuration::first()->amount_expedient;

        return $amount;
    }

     /**
     * obtiene el monto que se cobra por cita atendita de los medicos especialistas
     *
     * @param $monto El monto a convertir
     * @return float El monto convertido
     */
     function totalInvoices($invoices)
     {
         $total = 0;

         foreach ($invoices as $inv) {
             $total += $inv->total;
         }

         return $total;
     }

    function fillZeroLeftNumber($value, $lenght = 9)
    {
        return str_pad($value, $lenght, '0', STR_PAD_LEFT);
    }
    function fillZeroRightNumber($value, $lenght = 2)
    {
        return $value * 100;//$value. "00";
    }

function getPurchaseVerfication($purchaseOperationNumber, $purchaseAmount, $purchaseCurrencyCode)
{
    //dd(env('ACQUIRE_ID') .'-'. env('COMMERCE_ID') . '-' . env('CLAVE_SHA2'));
    return openssl_digest(env('ACQUIRE_ID') . env('COMMERCE_ID') . $purchaseOperationNumber . $purchaseAmount . $purchaseCurrencyCode . env('CLAVE_SHA2'), 'sha512');
}

function getUniqueNumber($length = 9, $id = null)
{
    $d = date('d');
    $m = date('m');
    $y = date('Y');
    $t = time();
    $dmt = $d + $m + $y + $t;
    $ran = rand(0, 10000000);
    $dmtran = $dmt + $ran;
    if ($id) {
        $dmtran = $dmtran + $id;
    }
    //$un = uniqid();
    //$dmtun = $dmt . $un;
    //$mdun = md5($dmtran . $un);
    $sort = substr($dmtran, 0, $length);
    return $sort;
}

function is_blank($value)
{
    return empty($value) && !is_numeric($value);
}
