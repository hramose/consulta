<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Factura;
use App\InvoiceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\FacturaElectronicaRepository;
use PDF;
use App\Office;
use App\Repositories\FacturaRepository;

class FacturaController extends Controller
{
    public function __construct(FacturaRepository $facturaRepo)
    {
        $this->middleware('auth');
        $this->facturaRepo = $facturaRepo;
        $this->feRepo = new FacturaElectronicaRepository('test');
    }

    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function index()
    {
        $fe = 0;
        $search['date'] = request('date') ? request('date') : Carbon::now()->toDateString();
        $search['clinic'] = request('clinic');
        $search['q'] = request('q');
        $medic = auth()->user();

        $facturas = $medic->facturas()->whereDate('created_at', $search['date']);

        if ($search['q']) {
            $facturas = $facturas->where('client_name', 'like', '%' . $search['q'] . '%');
        }

        if (is_blank($search['clinic'])) {
            $facturas = $facturas->orderBy('created_at', 'DESC')->paginate(20);
            $totalFacturasAmount = $facturas->sum('total');
        } else {
            $facturas = $facturas->where('office_id', $search['clinic'])->orderBy('created_at', 'DESC')->paginate(20);
            $totalFacturasAmount = $facturas->where('office_id', $search['clinic'])->sum('total');
        }

        return view('medic.facturas.index', compact('medic', 'facturas', 'totalFacturasAmount', 'search'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole('medico')) {
            return redirect('/medic/facturas');
        }

        $patient = request('p');

        return view('medic.facturas.create', compact('patient'));
    }

    /**
     * Guardar consulta(cita)
     */
    public function store()
    {
        $office = Office::find(request('office_id'));

        // if ($office && str_slug($office->type, '-') == 'clinica-privada') {
        //     $config = $office->configFactura->first();
        //     $fe = $office->fe;
        //     $fe_clinica = 1;
        // } else {
        $config = auth()->user()->configFactura->first();

        //}

        if (!existsCertFile($config)) {
            /*if ($fe_clinica) {
                $errors = [
                    'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que la clínica prívada lo tenga configurado en su perfil']
                ];
            } else {*/
            $errors = [
                    'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que el médico lo tenga configurado en su perfil']
                ];
            // }

            return response()->json(['errors' => $errors], 422, []);
        }

        $factura = $this->facturaRepo->store(request()->all(), $config);

        return $factura;
    }

    /**
    * update consulta(cita)
    */
    public function update($id)
    {
        $factura = $this->facturaRepo->findById($id);
        $office = $factura->clinic;

        // if ($office && str_slug($office->type, '-') == 'clinica-privada') {
        //     $config = $office->configFactura->first();
        //     $fe_clinica = 1;
        // } else {
        $config = $factura->obligadoTributario;
        //}

        if (!existsCertFile($config)) {
            // if ($fe_clinica) {
            //     $errors = [
            //         'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que la clínica prívada lo tenga configurado en su perfil']
            //     ];
            // } else {
            $errors = [
                    'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que el médico lo tenga configurado en su perfil']
                ];
            // }

            return response()->json(['errors' => $errors], 422, []);
        }

        $factura = $this->facturaRepo->update($id, request()->all(), $config);

        return $factura;
    }

    /**
    * Lista de todas las citas de un doctor sin paginar
    */
    public function getDetails($id)
    {
        $factura = Factura::find($id);
        $factura->load('lines');
        $factura->load('user');
        $factura->load('clinic');

        return $factura;
    }

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getServices()
    {
        $services = InvoiceService::where('user_id', auth()->id())->where('office_id', request('office_id'))->where('name', 'like', '%' . request('q') . '%')->get();

        return $services;
    }

    public function saveService()
    {
        $this->validate(request(), [
                'name' => 'required',
                'amount' => 'required|numeric',
            ]);
        $data = request()->all();
        $data['user_id'] = auth()->id();
        $data['office_id'] = request('office_id') ? request('office_id') : 0;

        $service = InvoiceService::create($data);

        return $service;
    }

    public function updateService($id)
    {
        $this->validate(request(), [
                'name' => 'required',
                'amount' => 'required|numeric',
            ]);

        $service = InvoiceService::find($id);
        $service->name = request('name');
        $service->amount = request('amount');
        $service->save();

        return $service;
    }

    public function deleteService($id)
    {
        $service = InvoiceService::find($id);
        $service->delete();

        return 'ok';
    }

    /**
     * imprime resumen de la consulta
     */
    public function print($id)
    {
        $factura = $this->facturaRepo->print($id);
        $office = $factura->clinic;

        // if ($office && str_slug($office->type, '-') == 'clinica-privada') {
        //     $configFactura = $office->configFactura->first();
        // } else {
        $configFactura = $factura->obligadoTributario;
        //}

        /* if (!$factura->appointment) {
             return view('medic.factura.print-general', compact('factura', 'configFactura'));
         }*/

        return view('medic.facturas.print', compact('factura', 'configFactura'));
    }

    /**
     * imprime resumen de la consulta
     */
    public function ticket($id)
    {
        $factura = $this->facturaRepo->print($id);

        $office = $factura->clinic;

        // if ($office && str_slug($office->type, '-') == 'clinica-privada') {
        //     $configFactura = $office->configFactura->first();
        // } else {
        $configFactura = $factura->obligadoTributario;
        //}

        return view('medic.facturas.ticket', compact('factura', 'configFactura'));
    }

    public function downloadXml($id)
    {
        return $this->facturaRepo->xml($id);
    }

    public function downloadPdf($id)
    {
        //return $this->invoiceRepo->pdf($id);
        $invoice = $this->facturaRepo->findById($id);

        return view('medic.facturas.pdf', compact('factura'));
    }

    /**
     * imprime resumen de la consulta
     */
    public function pdf($id)
    {
        $factura = $this->facturaRepo->findById($id);

        $html = request('htmltopdf');
        $pdf = new PDF($orientation = 'L', $unit = 'in', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false);

        $pdf::SetFont('helvetica', '', 9);

        $pdf::SetTitle('Expediente Clínico');
        $pdf::AddPage('L', 'A4');
        $pdf::writeHTML($html, true, false, true, false, '');

        $pdf::Output('gpsm_' . $factura->clave_fe . '.pdf');
    }
}
