<?php

namespace App\Http\Controllers\Assistant;

use App\Balance;
use App\Office;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceService;
use App\Repositories\InvoiceRepository;
use App\Repositories\MedicRepository;
use App\Repositories\PatientRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\FacturaElectronicaRepository;
use PDF;
use App\Factura;

class InvoiceController extends Controller
{
    public function __construct(InvoiceRepository $invoiceRepo, MedicRepository $medicRepo, PatientRepository $patientRepo)
    {
        $this->middleware('authByRole:asistente');
        $this->medicRepo = $medicRepo;
        $this->invoiceRepo = $invoiceRepo;
        $this->patientRepo = $patientRepo;
        $this->feRepo = new FacturaElectronicaRepository('test');
    }

    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function index()
    {
        
        $search['date'] = request('date') ? request('date') : Carbon::now()->toDateString();
        $search['medic'] = request('medic') ? request('medic') : '';
        $search['q'] = request('q');


        $office = auth()->user()->clinicsAssistants->first();
        $medics = $this->medicRepo->findAllByOffice($office->id);

     
        $invoices = Invoice::where('office_id', $office->id)->whereDate('created_at', $search['date']);
        if ($search['q']) {
            $facturas = $invoices->where('client_name', 'like', '%' . $search['q'] . '%');
        }


        if (is_blank($search['medic'])) {
            $invoices = $invoices->orderBy('created_at', 'DESC')->paginate(20);
            $totalInvoicesAmount = $invoices->sum('total');
        } else {
            $invoices = $invoices->where('user_id', $search['medic'])->orderBy('created_at', 'DESC')->paginate(20);
            $totalInvoicesAmount = $invoices->where('user_id', $search['medic'])->sum('total');
        }

        return view('assistant.invoices.index', compact('medics', 'office' , 'invoices', 'totalInvoicesAmount', 'search'));

        
    }

  

    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function create()
    {
        // if (!auth()->user()->hasRole('medico') || !auth()->user()->offices()->where('type', 'Clínica Privada')->count())
        //     return redirect('/assistant/invoices');

        $office = auth()->user()->clinicsAssistants->first();

        

        return view('assistant.invoices.create', compact('office'));
    }

    /**
    * Mostrar vista de todas las consulta(citas) de un doctor
    */
    public function show($medic_id)
    {
        $medic = $this->medicRepo->findById($medic_id);
        $search['date'] = request('date') ? request('date') : Carbon::now()->toDateString();

        $office = auth()->user()->clinicsAssistants->first();

        if ($medic->fe) {
            $fe = 1;
        }

        $invoices = $medic->invoices()->where('office_id', $office->id)->whereDate('created_at', $search['date'])->orderBy('created_at', 'DESC')->paginate(20);
        $totalInvoicesAmount = $medic->invoices()->where('office_id', $office->id)->whereDate('created_at', $search['date'])->sum('total');

        return view('assistant.invoices.show', compact('medic', 'invoices', 'totalInvoicesAmount', 'search', 'office', 'fe'));
    }

    public function noInvoices($medic_id)
    {
        $medic = $this->medicRepo->findById($medic_id);
        $searchDate = Carbon::now()->toDateString();

        if (request('q')) {
            $searchDate = request('q');
        }

        $office = auth()->user()->clinicsAssistants->first();

        $noInvoices = $medic->appointments()->where('office_id', $office->id)->where('status', 1)->where('finished', 1)->whereDate('date', $searchDate)->where('billed', 0)->orderBy('created_at', 'DESC')->paginate(20);

        return view('assistant.invoices.no-invoices', compact('medic', 'noInvoices', 'searchDate'));
    }

    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    // public function patientInvoices($patient_id)
    // {
    //     $patient = $this->patientRepo->findById($patient_id);
    //     $searchDate = Carbon::now()->toDateString();

    //     if (request('q')) {
    //         $searchDate = request('q');
    //     }

    //     $office = auth()->user()->clinicsAssistants->first();

    //     $invoices = $patient->invoices()->where('office_id', $office->id)->whereDate('created_at', $searchDate)->orderBy('created_at', 'DESC')->paginate(20);
    //     $totalInvoicesAmount = $patient->invoices()->where('office_id', $office->id)->whereDate('created_at', $searchDate)->sum('total');

    //     return view('assistant.patients.invoices', compact('patient', 'invoices', 'totalInvoicesAmount', 'searchDate'));
    // }

    /**
     * Guardar consulta(cita)
     */
    public function store()
    {
        $this->validate(request(), [
            'office_id' => 'required',
            'client_name' => 'required',
        ]);

        $office = Office::find(request('office_id'));

        if ($office->fe) {

            $config = $office->configFactura->first();


            if (!existsCertFile($config)) {
                $errors = [
                    'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que el medico lo tenga configurado en su perfil']
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

        $invoice = $this->invoiceRepo->findById($id);
        
        $office = $invoice->clinic;

        if($office->fe){

            $config = $invoice->obligadoTributario;


            if (!existsCertFile($config)) {
                $errors = [
                    'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que el medico lo tenga configurado en su perfil']
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
    public function getServices()
    {
        $services = InvoiceService::where('office_id', request('office_id'))->where('name', 'like', '%' . request('q') . '%')->get();

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
     * imprime resumen de la consulta
     */
    public function print($id)
    {
        $invoice = $this->invoiceRepo->print($id);
       

        return view('assistant.invoices.print', compact('invoice'));
    }

    /**
     * imprime resumen de la consulta
     */
    public function ticket($id)
    {
        $invoice = $this->invoiceRepo->print($id);
       

        return view('assistant.invoices.ticket', compact('invoice'));
    }

    public function downloadXml($id)
    {
        return $this->invoiceRepo->xml($id);
    }

    public function downloadPdf($id)
    {
        //return $this->invoiceRepo->pdf($id);
        $invoice = $this->invoiceRepo->findById($id);

        return view('assistant.invoices.pdf', compact('invoice'));
    }

   

    public function generalBalance()
    {
        $office = auth()->user()->clinicsAssistants->first();

        $medics = Office::find($office->id)->medicsWithInvoices(Carbon::now(), Carbon::now());

        $totalAppointments = 0;
        $totalInvoices = 0;
        $totalCommission = 0;

        foreach ($medics as $medic) {
            $invoicesTotalMedic = $medic->invoices->sum('total');
            $totalAppointments += $medic->invoices->count();
            $totalInvoices += $invoicesTotalMedic;
        }

        $statisticsInvoices = [
            'medics' => $medics,
            'totalAppointments' => $totalAppointments,
            'totalInvoices' => $totalInvoices
        ];

        return view('assistant.invoices.balance', compact('statisticsInvoices'));
    }
}
