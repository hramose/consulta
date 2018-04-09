<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceService;
use App\Repositories\InvoiceRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\FacturaRepository;
use PDF;
class InvoiceController extends Controller
{
    public function __construct(InvoiceRepository $invoiceRepo)
    {
        $this->middleware('auth');
        $this->invoiceRepo = $invoiceRepo;
       
    }

    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function index()
    {
        $search['date'] = request('date') ? request('date') : Carbon::now()->toDateString();
        $search['clinic'] = request('clinic');
        $search['q'] = request('q');
        $medic = auth()->user();

        $invoices = $medic->invoices()->whereDate('created_at', $search['date']);

        if ($search['q']) {
            $invoices = $invoices->where('client_name', 'like', '%' . $search['q'] . '%');
        }

        if (is_blank($search['clinic'])) {
            $invoices = $invoices->orderBy('created_at', 'DESC')->paginate(20);
            $totalInvoicesAmount = $invoices->sum('total');
        } else {
            $invoices = $invoices->where('office_id', $search['clinic'])->orderBy('created_at', 'DESC')->paginate(20);
            $totalInvoicesAmount = $invoices->where('office_id', $search['clinic'])->sum('total');
        }


        return view('medic.invoices.index', compact('medic', 'invoices', 'totalInvoicesAmount', 'search'));
    }

    public function noInvoices()
    {
        $searchDate = Carbon::now()->toDateString();

        if (request('q')) {
            $searchDate = request('q');
        }

        $medic = auth()->user();

        $offices = auth()->user()->offices()->pluck('offices.id');

        $noInvoices = $medic->appointments()->whereIn('office_id', $offices)->where('status', 1)->where('finished', 1)->where('billed', 0)->whereDate('date', $searchDate)->orderBy('created_at', 'DESC')->paginate(20);

        return view('medic.invoices.no-invoices', compact('medic', 'noInvoices', 'searchDate'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole('medico')) {
            return redirect('/medic/invoices');
        }
        $patient = request('p');

        return view('medic.invoices.create', compact('patient'));
    }

    /**
     * Guardar consulta(cita)
     */
    public function store()
    {
        $this->validate(request(), [
            'office_id' => 'required',
            'client_name' => 'required',
        ]);

        if(auth()->user()->fe){

            $config = auth()->user()->configFactura->first();
            
            if (!existsCertFile($config)) {
          
                $errors = [
                    'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que el médico lo tenga configurado en su perfil']
                ];
          

                return response()->json(['errors' => $errors], 422, []);
            }

            $invoice = $this->invoiceRepo->store(request()->all(), $config);

            return $invoice;
        }

        $invoice = $this->invoiceRepo->store(request()->all());

        return $invoice;
    }

    /**
    * update consulta(cita)
    */
    public function update($id)
    {
        $this->validate(request(), [
            'office_id' => 'required',
            'client_name' => 'required',
        ]);

        

        if(auth()->user()->fe){

            $invoice = $this->invoiceRepo->findById($id);
            $config = $invoice->obligadoTributario;

            if (!existsCertFile($config)) {
            
                $errors = [
                    'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que el médico lo tenga configurado en su perfil']
                ];
          

                return response()->json(['errors' => $errors], 422, []);
            }

            $invoice = $this->invoiceRepo->update($id, request()->all(), $config);

            return $invoice;

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
        $invoice->load('user');
        $invoice->load('documentosReferencia');

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

        return view('medic.invoices.print', compact('invoice'));
    }

    /**
     * imprime resumen de la consulta
     */
    public function ticket($id)
    {
      
        $invoice = $this->invoiceRepo->print($id);

        return view('medic.invoices.ticket', compact('invoice'));
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
}
