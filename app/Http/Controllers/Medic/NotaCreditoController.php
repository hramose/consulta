<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\FacturaElectronicaRepository;
use App\Repositories\FacturaRepository;

class NotaCreditoController extends Controller
{
    public function __construct(FacturaRepository $facturaRepo)
    {
        $this->middleware('auth');
        $this->facturaRepo = $facturaRepo;
        $this->feRepo = new FacturaElectronicaRepository('test');
    }

    public function create($factura_id)
    {

        $factura = $this->facturaRepo->findById($factura_id);

        if ($factura->user_id != auth()->id()) {
            return redirect('/');
        } //verifica que la factura es del medico q la solicita

        $typeDocument = '03';

        return view('medic.facturas.nota-credito-debito', compact('factura', 'typeDocument'));
    }

    /**
     * Guardar consulta(cita)
     */
    public function store($factura_id)
    {
        $factura = $this->facturaRepo->findById($factura_id);

        $config = $factura->obligadoTributario;


        // if ($office && str_slug($office->type, '-') == 'clinica-privada') {
        //     $config = $office->configFactura->first();

        // } else {
        //     $config = auth()->user()->configFactura->first();

        // }

        if (!existsCertFile($config)) {
            $errors = [
                'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que el medico lo tenga configurado en su perfil']
            ];

            return response()->json(['errors' => $errors], 422, []);
        }

        $notaCredito = $this->facturaRepo->notaCreditoDebito(request()->all(), $factura_id);

        return $notaCredito;
    }

   
}
