<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\Repositories\InvoiceRepository;
use Illuminate\Http\Request;
use App\Repositories\FacturaElectronicaRepository;

class NotaCreditoController extends Controller
{
    public function __construct(InvoiceRepository $invoiceRepo)
    {
        $this->middleware('auth');
        $this->invoiceRepo = $invoiceRepo;
        $this->feRepo = new FacturaElectronicaRepository('test');
    }

    public function create($invoice_id)
    {

        $invoice = $this->invoiceRepo->findById($invoice_id);

        if ($invoice->user_id != auth()->id()) {
            return redirect('/');
        } //verifica que la factura es del medico q la solicita

        $typeDocument = '03';

        return view('medic.invoices.nota-credito-debito', compact('invoice', 'typeDocument'));
    }

    /**
     * Guardar consulta(cita)
     */
    public function store($invoice_id)
    {
        $invoice = $this->invoiceRepo->findById($invoice_id);
        $office = $invoice->clinic;

        if ($office && str_slug($office->type, '-') == 'clinica-privada') {
            $config = $office->configFactura->first();

        } else {
            $config = auth()->user()->configFactura->first();

        }

        if ($invoice->fe && !existsCertFile($config)) {
            $errors = [
                'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que el medico lo tenga configurado en su perfil']
            ];

            return response()->json(['errors' => $errors], 422, []);
        }

        $notaCredito = $this->invoiceRepo->notaCreditoDebito(request()->all(), $invoice_id);

        return $notaCredito;
    }

   
}
