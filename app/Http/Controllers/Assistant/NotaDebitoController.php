<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Invoice;
use Illuminate\Http\Request;
use App\Repositories\FacturaElectronicaRepository;
use App\Repositories\InvoiceRepository;

class NotaDebitoController extends Controller
{
    public function __construct(invoiceRepository $invoiceRepo)
    {
        $this->middleware('authByRole:asistente');
        $this->invoiceRepo = $invoiceRepo;
        $this->feRepo = new FacturaElectronicaRepository(env('FE_ENV'));
    }

    public function create($invoiceId)
    {
        $invoice = $this->invoiceRepo->findById($invoiceId);
        $office = auth()->user()->clinicsAssistants->first();

        if ($invoice->office_id != $office->id) {
            return redirect('/');
        } //verifica que la factura es del medico q la solicita
        
        $typeDocument = '02';

        return view('assistant.invoices.nota-credito-debito', compact('invoice', 'typeDocument'));
    }

    /**
     * Guardar consulta(cita)
     */
    public function store($invoiceId)
    {

        $invoice = $this->invoiceRepo->findById($invoiceId);

        if ($invoice->fe) {

            $config = $invoice->obligadoTributario;


            if (!existsCertFile($config)) {
                $errors = [
                    'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que el medico lo tenga configurado en su perfil']
                ];

                return response()->json(['errors' => $errors], 422, []);
            }
        }

        $notaDebito = $this->invoiceRepo->notaCreditoDebito(request()->all(), $invoiceId);

        return $notaDebito;
    }
}
