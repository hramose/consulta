<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\Repositories\InvoiceRepository;
use Illuminate\Http\Request;
use App\Repositories\FacturaElectronicaRepository;

class NotaCreditoController extends Controller
{
    public function __construct(InvoiceRepository $invoiceRepo)
    {
        $this->middleware('authByRole:asistente');
        $this->invoiceRepo = $invoiceRepo;
        $this->feRepo = new FacturaElectronicaRepository('test');
    }

    public function create($invoice_id)
    {
        $invoice = $this->invoiceRepo->findById($invoice_id);
        
        $office = auth()->user()->clinicsAssistants->first();

        if ($invoice->office_id != $office->id) {
            return redirect('/');
        } //verifica que la factura es del medico q la solicita
        $typeDocument = '03';

        return view('assistant.invoices.nota-credito-debito', compact('invoice', 'typeDocument'));
    }

    /**
     * Guardar consulta(cita)
     */
    public function store($invoice_id)
    {
        $invoice = $this->invoiceRepo->findById($invoice_id);

        if ($invoice->medic->fe && !existsCertFile($invoice->medic)) {
            $errors = [
                'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que el medico lo tenga configurado en su perfil']
            ];

            return response()->json(['errors' => $errors], 422, []);
        }

        $notaCredito = $this->invoiceRepo->notaCreditoDebito(request()->all(), $invoice_id);

        return $notaCredito;
    }

}
