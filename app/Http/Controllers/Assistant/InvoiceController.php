<?php

namespace App\Http\Controllers\Assistant;

use App\Balance;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceService;
use App\Repositories\InvoiceRepository;
use App\Repositories\MedicRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    
    function __construct(InvoiceRepository $invoiceRepo, MedicRepository $medicRepo)
    {
    	
        $this->middleware('auth');
    	$this->medicRepo = $medicRepo;
        $this->invoiceRepo = $invoiceRepo;
       

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
        $office =  auth()->user()->clinicsAssistants->first();


        $medics = $this->medicRepo->findAllByOffice($office->id);

        if(request('medic'))
            $medic = $this->medicRepo->findById(request('medic'));
        else
            $medic = null;

        $invoices = Invoice::where('office_id', $office->id)->orderBy('created_at','DESC')->limit(10)->get();
    
        
    	
    	return view('assistant.invoices.index',compact('medics','medic','search','invoices'));

    }
     /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function show($medic_id)
    {
        $medic = $this->medicRepo->findById($medic_id);

        /*$assistants_users = \DB::table('assistants_users')->where('assistant_id',auth()->id())->first();
        
        if(auth()->user()->isMedicAssistant($assistants_users->user_id))
            $offices = User::find($assistants_users->user_id)->offices()->where('type','Consultorio Independiente')->pluck('offices.id');//first();
        if(auth()->user()->isClinicAssistant($assistants_users->user_id))
            $offices = User::find($assistants_users->user_id)->offices()->where('type','Clínica Privada')->pluck('offices.id');*/

        $office =  auth()->user()->clinicsAssistants->first();

        $invoices = $medic->invoices()->where('office_id', $office->id)->orderBy('created_at','DESC')->paginate(10);

      
        //$invoices =$this->invoiceRepo->findAllByDoctor(auth()->id(), $search);

        return view('assistant.invoices.show',compact('medic','invoices'));

    }

    /**
     * Guardar consulta(cita)
     */
    public function store()
    {

       // dd(request()->all());

        $invoice = $this->invoiceRepo->store(request()->all());
        


        return $invoice;

    }

    
     /**
     * update consulta(cita)
     */
    public function update($id)
    {
       
        $invoice = Invoice::find($id);

        $invoice->status = 1;
        
        if(request('client_name'))
            $invoice->client_name = request('client_name');

        $invoice->save();
        


        return $invoice;

    }

   

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getServices()
    {

        $services = InvoiceService::where('name', 'like', '%'. request('q').'%')->get();

        return $services;
        
    }
    public function saveService()
    {
        $service = InvoiceService::create(request()->all());

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

        $invoice = Invoice::find($id);
        $invoice->load('lines');
        $invoice->load('medic');
        $invoice->load('clinic');
        $invoice->load('appointment.patient');

        
        return view('assistant.invoices.print',compact('invoice'));
        
    }

    /**
     * imprime resumen de la consulta
     */
    public function ticket($id)
    {

        $invoice = Invoice::find($id);
        $invoice->load('lines');
        $invoice->load('medic');
        $invoice->load('clinic');
        $invoice->load('appointment.patient');

        
        return view('assistant.invoices.ticket',compact('invoice'));
        
    }
     /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function balance($medic_id)
    {
        
        $bala = Balance::where('user_id', $medic_id)->whereDate('created_at',Carbon::now()->toDateString())->count();


        if($bala)
        {
            flash('Cierre ya fue ejecutado el dia de hoy','error');
            return Redirect()->back();
        }

        $invoices = Invoice::where('user_id', $medic_id)->where('status', 1)->whereDate('created_at',Carbon::now()->toDateString());
        $totalInvoices =  $invoices->sum('total');
        $countInvoices =  $invoices->count();
       

        $balance = Balance::create([
            'user_id' => $medic_id,
            'invoices' => $countInvoices,
            'total' => $totalInvoices
            ]);

       
        flash('Se ha ejecutado el cierre correctamente','success');

        return Redirect()->back();
        
    }

   

}
