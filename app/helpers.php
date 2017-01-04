<?php 

function money($amount, $symbol = '$')
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