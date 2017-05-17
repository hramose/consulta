<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\InvoiceService;
use App\Repositories\InvoiceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    
    function __construct(InvoiceRepository $invoiceRepo)
    {
    	
        $this->middleware('auth');
    	$this->invoiceRepo = $invoiceRepo;
       

    }

    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function index()
    {
        $search['q'] = request('q');
      
    	$invoices =$this->invoiceRepo->findAllByDoctor(auth()->id(), $search);

    	return view('invoices.index',compact('invoices','search'));

    }

    /**
     * Guardar consulta(cita)
     */
    public function store()
    {

       // dd(request()->all());

        $invoice = $this->invoiceRepo->store(request()->all());
        


        return $invoice;

    }

   

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getServices()
    {

        $services = InvoiceService::where('name', 'like', '%'. request('q').'%')->get();

        return $services;
        
    }
    public function saveService()
    {
        $service = InvoiceService::create(request()->all());

         return $service;
    }
    /**
     * imprime resumen de la consulta
     */
    public function print($id)
    {

        /*$appointment =  $this->appointmentRepo->findById($id);
        $history =  $this->patientRepo->findById($appointment->patient->id)->history;
        
        return view('appointments.print-summary',compact('appointment','history'));*/
        
    }

   

}
