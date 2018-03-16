<?php

namespace App\Http\Controllers\Medic;

use App\Balance;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceService;
use App\Repositories\InvoiceRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\FacturaElectronicaRepository;
use PDF;
use App\Office;

class InvoiceController extends Controller
{
    public function __construct(InvoiceRepository $invoiceRepo)
    {
        $this->middleware('auth');
        $this->invoiceRepo = $invoiceRepo;
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
        $medic = auth()->user();

        if ($medic->fe || $medic->offices()->where('fe', 1)->count()) {
            $fe = 1;
        }
        //dd(empty($search['clinic']));
        $invoices = $medic->invoices()->whereDate('created_at', $search['date']);
        if ($search['clinic'] == 0) {
            $invoices = $invoices->where('office_id', $search['clinic'])->orderBy('created_at', 'DESC')->paginate(20);
            $totalInvoicesAmount = $invoices->sum('total');
        }else{
            $invoices = $invoices->orderBy('created_at', 'DESC')->paginate(20);
            $totalInvoicesAmount = $invoices->sum('total');
        }

        // $invoices = $medic->invoices()->whereDate('created_at', $search['date']);
        // if(empty($search['clinic'])){
        //     $invoices = $invoices->orderBy('created_at', 'DESC')->paginate(20);
        //     $totalInvoicesAmount = $invoices->sum('total');
        // }else{


        // }
        // if ($search['clinic'] && $search['clinic'] != '') {
        //     $invoices = $invoices->where('office_id', $search['clinic'])->orderBy('created_at', 'DESC')->paginate(20);
        //     $totalInvoicesAmount = $invoices->where('office_id', $search['clinic'])->sum('total');
        // } elseif ($search['clinic'] == 0) {
        //     $invoices = $invoices->where('office_id', $search['clinic'])->orderBy('created_at', 'DESC')->paginate(20);
        //     $totalInvoicesAmount = $invoices->sum('total');
        // } else {
        //     $invoices = $invoices->orderBy('created_at', 'DESC')->paginate(20);
        //     $totalInvoicesAmount = $invoices->sum('total');
        // }

        //$noInvoices = $medic->appointments()->whereIn('office_id', $offices)->where('status', 1)->where('finished', 1)->whereDate('date', $searchDate)->doesntHave('invoices')->orderBy('created_at', 'DESC')->paginate(20);

        return view('medic.invoices.index', compact('medic', 'invoices', 'totalInvoicesAmount', 'search', 'fe'));
    }

    public function noInvoices()
    {
        $searchDate = Carbon::now()->toDateString();

        if (request('q')) {
            $searchDate = request('q');
        }

        $medic = auth()->user();

        $offices = auth()->user()->offices()->pluck('offices.id');

        $noInvoices = $medic->appointments()->whereIn('office_id', $offices)->where('status', 1)->where('finished', 1)->whereDate('date', $searchDate)->doesntHave('invoices')->orderBy('created_at', 'DESC')->paginate(20);

        return view('medic.invoices.no-invoices', compact('medic', 'noInvoices', 'searchDate'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole('medico') || !auth()->user()->offices()->where('type', 'Clínica Privada')->count()) {
            return redirect('/medic/invoices');
        }

        return view('medic.invoices.create');
    }

    /**
     * Guardar consulta(cita)
     */
    public function store()
    {
        $office = Office::find(request('office_id'));
        $fe = 0;
        $fe_clinica = 0;

        if ($office && str_slug($office->type, '-') == 'clinica-privada') {
            $config = $office->configFactura->first();
            $fe = $office->fe;
            $fe_clinica = 1;
        } else {
            $config = auth()->user()->configFactura->first();
            $fe = auth()->user()->fe;
        }

        if ($fe && !existsCertFile($config)) {
            if($fe_clinica){
                $errors = [
                    'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que la clínica prívada lo tenga configurado en su perfil']
                ];
            }else{
                $errors = [
                    'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que el médico lo tenga configurado en su perfil']
                ];
            }

            return response()->json(['errors' => $errors], 422, []);
        }

        $invoice = $this->invoiceRepo->store(request()->all());

        return $invoice;
    }

    /**
    * update consulta(cita)
    */
    public function update($id)
    {
        $invoice = $this->invoiceRepo->findById($id);
        $office = $invoice->clinic;
        $fe_clinica = 0;

        if ($office && str_slug($office->type, '-') == 'clinica-privada') {
            $config = $office->configFactura->first();
            $fe_clinica = 1;
        } else {
            $config = $invoice->medic->configFactura->first();
        }

        if ($invoice->fe && !existsCertFile($config)) {
            if ($fe_clinica) {
                $errors = [
                    'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que la clínica prívada lo tenga configurado en su perfil']
                ];
            } else {
                $errors = [
                    'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que el médico lo tenga configurado en su perfil']
                ];
            }

            return response()->json(['errors' => $errors], 422, []);
        }

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
        $invoice = $this->invoiceRepo->print($id);
        $office = $invoice->clinic;

        if ($office && str_slug($office->type, '-') == 'clinica-privada') {
            $configFactura = $office->configFactura->first();
        } else {
            $configFactura = $invoice->medic->configFactura->first();
        }

        if (!$invoice->appointment) {
            return view('medic.invoices.print-general', compact('invoice', 'configFactura'));
        }

        return view('medic.invoices.print', compact('invoice', 'configFactura'));
    }

    /**
     * imprime resumen de la consulta
     */
    public function ticket($id)
    {
        $invoice = $this->invoiceRepo->print($id);

        $office = $invoice->clinic;

        if ($office && str_slug($office->type, '-') == 'clinica-privada') {
            $configFactura = $office->configFactura->first();
        } else {
            $configFactura = $invoice->medic->configFactura->first();
        }

        return view('medic.invoices.ticket', compact('invoice', 'configFactura'));
    }

    public function downloadXml($id)
    {
        return $this->invoiceRepo->xml($id);
    }

    public function downloadPdf($id)
    {
        //return $this->invoiceRepo->pdf($id);
        $invoice = $this->invoiceRepo->findById($id);

        return view('medic.invoices.pdf', compact('invoice'));
    }

    /**
     * imprime resumen de la consulta
     */
    public function pdf($id)
    {
        $invoice = $this->invoiceRepo->findById($id);

        $html = request('htmltopdf');
        $pdf = new PDF($orientation = 'L', $unit = 'in', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false);

        $pdf::SetFont('helvetica', '', 9);

        $pdf::SetTitle('Expediente Clínico');
        $pdf::AddPage('L', 'A4');
        $pdf::writeHTML($html, true, false, true, false, '');

        $pdf::Output('gpsm_' . $invoice->clave_fe . '.pdf');
    }

    /*
     public function balance()
     {
         $medic_id = auth()->id();

         $this->invoiceRepo->balance($medic_id);


         flash('Se ha ejecutado el cierre correctamente', 'success');

         return Redirect()->back();
     }*/
}
