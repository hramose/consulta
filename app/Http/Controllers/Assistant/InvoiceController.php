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
        $search['q'] = request('q');

        /* $assistants_users = \DB::table('assistants_users')->where('assistant_id',auth()->id())->first();

         if(auth()->user()->isMedicAssistant($assistants_users->user_id))
             $offices = User::find($assistants_users->user_id)->offices()->where('type','Consultorio Independiente')->pluck('offices.id');//first();
         if(auth()->user()->isClinicAssistant($assistants_users->user_id))
             $offices = User::find($assistants_users->user_id)->offices()->where('type','Clínica Privada')->pluck('offices.id');*/

        //$assistant = User::find(auth()->id())->with('clinicsAssistants');
        //    dd( $assistant->all());
        $office = auth()->user()->clinicsAssistants->first();

        $medics = $this->medicRepo->findAllByOffice($office->id);

        if (request('medic')) {
            $medic = $this->medicRepo->findById(request('medic'));
        } else {
            $medic = null;
        }

        $invoices = Invoice::where('office_id', $office->id)->where('status', 0)->orderBy('created_at', 'DESC')->limit(10)->get();

        return view('assistant.invoices.index', compact('medics', 'medic', 'search', 'invoices'));
    }

    /**
    * Mostrar vista de todas las consulta(citas) de un doctor
    */
    public function show($medic_id)
    {
        $medic = $this->medicRepo->findById($medic_id);
        $searchDate = Carbon::now()->toDateString();

        if (request('q')) {
            $searchDate = request('q');
        }

        /*$assistants_users = \DB::table('assistants_users')->where('assistant_id',auth()->id())->first();

        if(auth()->user()->isMedicAssistant($assistants_users->user_id))
            $offices = User::find($assistants_users->user_id)->offices()->where('type','Consultorio Independiente')->pluck('offices.id');//first();
        if(auth()->user()->isClinicAssistant($assistants_users->user_id))
            $offices = User::find($assistants_users->user_id)->offices()->where('type','Clínica Privada')->pluck('offices.id');*/

        $office = auth()->user()->clinicsAssistants->first();

        $invoices = $medic->invoices()->where('office_id', $office->id)->whereDate('created_at', $searchDate)->orderBy('created_at', 'DESC')->paginate(20);
        $totalInvoicesAmount = $medic->invoices()->where('office_id', $office->id)->whereDate('created_at', $searchDate)->sum('total');
        //$noInvoices = $medic->appointments()->where('office_id', $office->id)->where('status', 1)->where('finished', 1)->whereDate('date', $searchDate)->doesntHave('invoices')->orderBy('created_at', 'DESC')->paginate(20);

        //$invoices =$this->invoiceRepo->findAllByDoctor(auth()->id(), $search);

        return view('assistant.invoices.show', compact('medic', 'invoices', 'totalInvoicesAmount', 'searchDate'));
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
        $invoice = $this->invoiceRepo->store(request()->all());

        return $invoice;
    }

    /**
    * update consulta(cita)
    */
    public function update($id)
    {
        $invoice = $this->invoiceRepo->findById($id);

        if ($invoice->medic->fe && !existsCertFile($invoice->medic)) {
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
    /* public function getServices()
     {

         $services = InvoiceService::where('name', 'like', '%'. request('q').'%')->get();

         return $services;

     }
     public function saveService()
     {
         $service = InvoiceService::create(request()->all());

          return $service;
     }*/

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
