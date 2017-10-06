<?php

namespace App\Http\Controllers\Medic;

use App\Balance;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceService;
use App\Repositories\InvoiceRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    
    function __construct(InvoiceRepository $invoiceRepo)
    {
    	
        $this->middleware('auth');
    	$this->invoiceRepo = $invoiceRepo;
       

    }

    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function index()
    {
        /*$search['q'] = request('q');
      
    	$invoices =$this->invoiceRepo->findAllByDoctor(auth()->id(), $search);

        return view('medic.invoices.index',compact('invoices','search'));*/
        $searchDate = Carbon::now();
      
        
        if(request('q')){

            $searchDate = request('q');
            $searchDate = Carbon::parse($searchDate);
        }
        
        $medic = auth()->user();

       

        //$offices = auth()->user()->offices()->where('type','Consultorio Independiente')->pluck('offices.id');//first();
        $offices = auth()->user()->offices()->pluck('offices.id');//first();

      

        $invoices = $medic->invoices()->whereIn('office_id', $offices)->where([['created_at', '>=', $searchDate->startOfDay()],
        ['created_at', '<=', $searchDate->endOfDay()]])->orderBy('created_at','DESC')->paginate(20);
        $totalInvoicesAmount =  $medic->invoices()->whereIn('office_id', $offices)->where([['created_at', '>=', $searchDate->startOfDay()],
        ['created_at', '<=', $searchDate->endOfDay()]])->sum('total');
        $noInvoices = $medic->appointments()->whereIn('office_id', $offices)->where('status', 1)->where('finished', 1)->where([['date', '>=', $searchDate->startOfDay()],
        ['date', '<=', $searchDate->endOfDay()]])->doesntHave('invoices')->orderBy('created_at','DESC')->paginate(20);

        $searchDate = $searchDate->endOfDay()->endOfDay();
      

        return view('medic.invoices.index',compact('medic','invoices', 'noInvoices','totalInvoicesAmount','searchDate'));

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
       
        $invoice = Invoice::find($id);

        $invoice->status = 1;
        
        if(request('client_name'))
            $invoice->client_name = request('client_name');
        if(request('pay_with'))
            $invoice->pay_with = request('pay_with');
        if(request('change'))
            $invoice->change = request('change');

        $invoice->save();
        


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

        $services = InvoiceService::where('name', 'like', '%'. request('q').'%')->get();

        return $services;
        
    }
    public function saveService()
    {
        $this->validate(request(),[
                'name' => 'required',
                'amount' => 'required|numeric',
                
            ]);

        $service = InvoiceService::create(request()->all());

         return $service;
    }
    public function updateService($id)
    {
         $this->validate(request(),[
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

        $invoice = Invoice::find($id);
        $invoice->load('lines');
        $invoice->load('medic');
        $invoice->load('clinic');
        $invoice->load('appointment.patient');

        
        return view('medic.invoices.print',compact('invoice'));
        
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

        
        return view('medic.invoices.ticket',compact('invoice'));
        
    }

      /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function balance()
    {
        $medic_id = auth()->id();

       // $bala = Balance::where('user_id', $medic_id)->whereDate('created_at',Carbon::now()->toDateString())->count();


        // if($bala)
        // {
        //     flash('Cierre ya fue ejecutado el dia de hoy','error');
        //     return Redirect()->back();
        // }

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
        
    }

   

}
