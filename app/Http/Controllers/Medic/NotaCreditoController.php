<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\FacturaElectronicaRepository;
use App\Repositories\FacturaRepository;
use App\Repositories\InvoiceRepository;

class NotaCreditoController extends Controller
{
    public function __construct(InvoiceRepository $invoiceRepo)
    {
        $this->middleware('auth');
        $this->invoiceRepo = $invoiceRepo;
        $this->feRepo = new FacturaElectronicaRepository(env('FE_ENV'));
    }

    public function create($invoiceId)
    {

        $invoice = $this->invoiceRepo->findById($invoiceId);

        if ($invoice->user_id != auth()->id()) {
            return redirect('/');
        } //verifica que la factura es del medico q la solicita

        $typeDocument = '03';

        return view('medic.invoices.nota-credito-debito', compact('invoice', 'typeDocument'));
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

        $notaCredito = $this->invoiceRepo->notaCreditoDebito(request()->all(), $invoiceId);

        return $notaCredito;


    }

   
}
