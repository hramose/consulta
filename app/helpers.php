<?php 

use App\Configuration;
use Illuminate\Support\Facades\Session;



function money($amount, $symbol = '₡')
{
    return (!$symbol) ? number_format($amount, 2, ".", ",") : $symbol . number_format($amount, 2, ".", ",");
}
function number($amount)
{
    return preg_replace("/([^0-9\\.])/i", "", $amount);
}
function percent($amount, $symbol = '%')
{
    return $symbol . number_format($amount, 0, ".", ",");
}
function age($birthdate)
{
	return $birthdate;
}
function flash($message, $level = 'info')
{
	session()->flash('flash_message',$message);
	session()->flash('flash_message_level',$level);
}
function paginate($items, $perPage)
{
    $pageStart           = \Request::get('page', 1);
    $offSet              = ($pageStart * $perPage) - $perPage;
    $itemsForCurrentPage = array_slice($items, $offSet, $perPage, TRUE);

    return new Illuminate\Pagination\LengthAwarePaginator(
        $itemsForCurrentPage, count($items), $perPage,
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
    $dayName = "";

    if(Carbon\Carbon::SUNDAY == $day)                          // int(0)
        $dayName = "Domingo";

    if(Carbon\Carbon::MONDAY == $day)                       // int(1)
        $dayName = "Lunes";

    if(Carbon\Carbon::TUESDAY == $day)                         // int(2)
        $dayName = "Martes";

    if(Carbon\Carbon::WEDNESDAY == $day)                       // int(3)
        $dayName = "Miércoles";

    if(Carbon\Carbon::THURSDAY == $day)                       // int(4)
        $dayName = "Jueves";

    if(Carbon\Carbon::FRIDAY == $day)                          // int(5)
        $dayName = "Viernes";

    if(Carbon\Carbon::SATURDAY == $day)                        // int(6)
        $dayName = "Sábado";

    return $dayName;
}
function getAvatar($user)
{
   

    $url = '';
    
    if(Storage::disk('public')->exists('avatars/'. $user->id.'/avatar.jpg'))
        $url = Storage::url('avatars/'.$user->id.'/avatar.jpg');
    else
        $url = "/img/default-avatar.jpg";

    return $url;
        
     
}
function getLogo($clinic)
{
   

    $url = '';
    
    if(Storage::disk('public')->exists('offices/'. $clinic->id.'/photo.jpg'))
        $url = Storage::url('offices/'.$clinic->id.'/photo.jpg');
    else
        $url = "/img/logo.png";

    return $url;
        
     
}
/**
     * obtiene el monto que se cobra por cita atendita de los medicos generales
     *
     * @param $monto El monto a convertir
     * @return float El monto convertido
     */
    function getAmountGeneralPerAppointment()
    {
         $amount = 0;
        
        /*if(Session::has('amount_general'))
        {
            $amount =  Session::get('amount_general');

        }else{
            $amount = Configuration::first()->amount_general;
            Session::put('amount_general', $amount);
        }*/
        $amount = Configuration::first()->amount_general;
       
        return $amount;
    }  

    /**
     * obtiene el monto que se cobra por cita atendita de los medicos especialistas
     *
     * @param $monto El monto a convertir
     * @return float El monto convertido
     */
    function getAmountSpecialistPerAppointment()
    {
        $amount = 0;

        /*if(Session::has('amount_specialist'))
        {
            $amount =  Session::get('amount_specialist');

        }else{
            $amount = Configuration::first()->amount_specialist;
            Session::put('amount_specialist', $amount);
        }*/
        $amount = Configuration::first()->amount_specialist;
       
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

        foreach($invoices as $inv){
            $total += $inv->total;
        }
        
                   
                      
      return $total;
     } 
