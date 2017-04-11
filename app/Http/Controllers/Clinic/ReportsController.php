<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
     function __construct()
    {
    	$this->middleware('auth');
        
    }

    /**
     * Mostrar lista de pacientes de un doctor
     */
    public function index()
    {
    	return view('clinic.reports.index');
    }
}
