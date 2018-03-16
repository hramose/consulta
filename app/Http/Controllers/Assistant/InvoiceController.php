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
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\FacturaElectronicaRepository;
use PDF;

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
        $fe = 0;
        $search['date'] = request('date') ? request('date') : Carbon::now()->toDateString();
        $search['medic'] = request('medic') ? request('medic') : '';

        $office = auth()->user()->clinicsAssistants->first();
        $medics = $this->medicRepo->findAllByOffice($office->id);


        if ($office->fe) {
            $fe = 1;
        }

        $invoices = Invoice::where('office_id', $office->id)->whereDate('created_at', $search['date']);

        if ($search['medic'] && $search['medic'] !== '') {
            $invoices = $invoices->where('user_id', $search['medic'])->orderBy('created_at', 'DESC')->paginate(20);
            $totalInvoicesAmount = $invoices->where('user_id', $search['medic'])->sum('total');
        
        } else {
            $invoices = $invoices->orderBy('created_at', 'DESC')->paginate(20);
            $totalInvoicesAmount = $invoices->sum('total');
        }



        return view('assistant.invoices.index', compact('medics','office', 'invoices', 'totalInvoicesAmount', 'search', 'fe'));
    }

    // /**
    //  * Mostrar vista de todas las consulta(citas) de un doctor
    //  */
    // public function index()
    // {
    //     $search['q'] = request('q');

    //     /* $assistants_users = \DB::table('assistants_users')->where('assistant_id',auth()->id())->first();

    //      if(auth()->user()->isMedicAssistant($assistants_users->user_id))
    //          $offices = User::find($assistants_users->user_id)->offices()->where('type','Consultorio Independiente')->pluck('offices.id');//first();
    //      if(auth()->user()->isClinicAssistant($assistants_users->user_id))
    //          $offices = User::find($assistants_users->user_id)->offices()->where('type','Clínica Privada')->pluck('offices.id');*/

    //     //$assistant = User::find(auth()->id())->with('clinicsAssistants');
    //     //    dd( $assistant->all());
    //     $office = auth()->user()->clinicsAssistants->first();

    //     $medics = $this->medicRepo->findAllByOffice($office->id);

    //     if (request('medic')) {
    //         $medic = $this->medicRepo->findById(request('medic'));
    //     } else {
    //         $medic = null;
    //     }

    //     $invoices = Invoice::where('office_id', $office->id)->where('status', 0)->orderBy('created_at', 'DESC')->limit(10)->get();

    //     return view('assistant.invoices.index', compact('medics', 'medic', 'search', 'invoices'));
    // }

    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
   
    public function create()
    {
        // if (!auth()->user()->hasRole('medico') || !auth()->user()->offices()->where('type', 'Clínica Privada')->count())
        //     return redirect('/assistant/invoices');

        $office = auth()->user()->clinicsAssistants->first();

        if ($office->fe) {
            $fe = 1;
        }

        return view('assistant.invoices.create', compact('office', 'fe'));
    }

    /**
    * Mostrar vista de todas las consulta(citas) de un doctor
    */
    public function show($medic_id)
    {
        $medic = $this->medicRepo->findById($medic_id);
        $search['date'] = request('date') ? request('date') : Carbon::now()->toDateString();

        $office = auth()->user()->clinicsAssistants->first();

        if ($medic->fe || $office->fe) {
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

        $noInvoices = $medic->appointments()->where('office_id', $office->id)->where('status', 1)->where('finished', 1)->whereDate('date', $searchDate)->doesntHave('invoices')->orderBy('created_at', 'DESC')->paginate(20);

        return view('assistant.invoices.no-invoices', compact('medic', 'noInvoices', 'searchDate'));
    }

    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function patientInvoices($patient_id)
    {
        $patient = $this->patientRepo->findById($patient_id);
        $searchDate = Carbon::now()->toDateString();

        if (request('q')) {
            $searchDate = request('q');
        }

        $office = auth()->user()->clinicsAssistants->first();

        $invoices = $patient->invoices()->where('office_id', $office->id)->whereDate('created_at', $searchDate)->orderBy('created_at', 'DESC')->paginate(20);
        $totalInvoicesAmount = $patient->invoices()->where('office_id', $office->id)->whereDate('created_at', $searchDate)->sum('total');

        return view('assistant.patients.invoices', compact('patient', 'invoices', 'totalInvoicesAmount', 'searchDate'));
    }

    /**
     * Guardar consulta(cita)
     */
    public function store()
    {
        $office = Office::find(request('office_id'));
        $fe = 0;

        if ($office && str_slug($office->type, '-') == 'clinica-privada') {
            $config = $office->configFactura->first();
            $fe = $office->fe;
        } else {
            $config = auth()->user()->configFactura->first();
            $fe = auth()->user()->fe;
        }

        if ($fe && !existsCertFile($config)) {
            $errors = [
                'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que el medico lo tenga configurado en su perfil']
            ];

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

        if ($office && str_slug($office->type, '-') == 'clinica-privada') {
            $config = $office->configFactura->first();
        } else {
            $config = $invoice->medic->configFactura->first();
        }

        if ($invoice->fe && !existsCertFile($config)) {
            $errors = [
                        'certificate' => ['Parece que no tienes el certificado de hacienda ATV instalado. Para poder continuar verfica que el medico lo tenga configurado en su perfil']
                    ];

            return response()->json(['errors' => $errors], 422, []);
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
        $data['office_id'] = request('office_id');

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
        $invoice->load('medic');
        $invoice->load('appointment.patient');

        return $invoice;
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

        // if (!$invoice->appointment) {
        //     return view('medic.invoices.print-general', compact('invoice', 'configFactura'));
        // }


        return view('assistant.invoices.print', compact('invoice', 'configFactura'));
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

        return view('assistant.invoices.ticket', compact('invoice', 'configFactura'));
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

    /**
    * Lista de todas las citas de un doctor sin paginar
    */
    /*public function balance($medic_id)
    {


        $invoices = Invoice::where('user_id', $medic_id)->where('status', 1)->whereDate('created_at',Carbon::now()->toDateString());
        $totalInvoices =  $invoices->sum('total');
        $countInvoices =  $invoices->count();

        if($countInvoices == 0)
        {
            flash('No hay Facturas nuevas para ejecutar un cierre','error');

            return Redirect()->back();
        }

        $balance = Balance::create([
            'user_id' => $medic_id,
            'invoices' => $countInvoices,
            'total' => $totalInvoices
            ]);


        flash('Se ha ejecutado el cierre correctamente','success');

        return Redirect()->back();


    }*/

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
