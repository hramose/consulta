<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\Repositories\InvoiceRepository;
use Illuminate\Http\Request;
use App\Repositories\FacturaElectronicaRepository;

class NotaDebitoController extends Controller
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
        $typeDocument = '02';

        return view('medic.invoices.nota-credito-debito', compact('invoice', 'typeDocument'));
    }

    /**
     * Guardar consulta(cita)
     */
    public function store($invoice_id)
    {
        if (auth()->user()->fe && !existsCertFile(auth()->user())) {
            $errors = [
                'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que el medico lo tenga configurado en su perfil']
            ];

            return response()->json(['errors' => $errors], 422, []);
        }

        $notaDebito = $this->invoiceRepo->notaCreditoDebito(request()->all(), $invoice_id);

        return $notaDebito;
    }
}
