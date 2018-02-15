<?php

namespace App\Http\Controllers\Clinic;

use App\Balance;
use App\Office;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceService;
use App\Repositories\InvoiceRepository;
use App\Repositories\MedicRepository;
use App\Repositories\PatientRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    
    function __construct(InvoiceRepository $invoiceRepo, MedicRepository $medicRepo, PatientRepository $patientRepo)
    {
    	
        $this->middleware('authByRole:clinica');
    	$this->medicRepo = $medicRepo;
        $this->invoiceRepo = $invoiceRepo;
        $this->patientRepo = $patientRepo;
       

    }

    
     /**
     * update consulta(cita)
     */
    public function update($id)
    {
       
        $invoice = $this->invoiceRepo->update($id, request()->all());

        return $invoice;


    }

   
     /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getDetails($id)
    {
        $invoice = Invoice::find($id);
        $invoice->load('lines');
        $invoice->load('medic');
        $invoice->load('appointment.patient');
       

        return $invoice;
        
    }
    /**
     * imprime resumen de la consulta
     */
    public function print($id)
    {

        $invoice = $this->invoiceRepo->print($id);
        
        return view('clinic.invoices.print',compact('invoice'));
        
    }

    /**
     * imprime resumen de la consulta
     */
    public function ticket($id)
    {

        $invoice = $this->invoiceRepo->print($id);

        
        return view('clinic.invoices.ticket',compact('invoice'));
        
    }
    

   

}
