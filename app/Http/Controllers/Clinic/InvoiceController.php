<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\Repositories\InvoiceRepository;
use App\Repositories\MedicRepository;
use App\Repositories\PatientRepository;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(InvoiceRepository $invoiceRepo, MedicRepository $medicRepo, PatientRepository $patientRepo)
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
        $office = $invoice->clinic;

        // if ($office && str_slug($office->type, '-') == 'clinica-privada') {
        //     $configFactura = $office->configFactura->first();
        // } else {
            $configFactura = $invoice->medic->configFactura->first();
       // }

        if (!$invoice->appointment) {
            return view('medic.invoices.print-general', compact('invoice', 'configFactura'));
        }

        return view('clinic.invoices.print', compact('invoice', 'configFactura'));
    }

    /**
     * imprime resumen de la consulta
     */
    public function ticket($id)
    {
        $invoice = $this->invoiceRepo->print($id);
        $office = $invoice->clinic;

        // if ($office && str_slug($office->type, '-') == 'clinica-privada') {
        //     $configFactura = $office->configFactura->first();
        // } else {
            $configFactura = $invoice->medic->configFactura->first();
        //}

        return view('clinic.invoices.ticket', compact('invoice', 'configFactura'));
    }
}
