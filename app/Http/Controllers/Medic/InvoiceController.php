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
        /*$search['q'] = request('q');

    	$invoices =$this->invoiceRepo->findAllByDoctor(auth()->id(), $search);

        return view('medic.invoices.index',compact('invoices','search'));*/
        $searchDate = Carbon::now()->toDateString();

        if (request('q')) {
            $searchDate = request('q');
        }

        $medic = auth()->user();

        //$offices = auth()->user()->offices()->where('type','Consultorio Independiente')->pluck('offices.id');//first();
        $offices = auth()->user()->offices()->pluck('offices.id');//first();

        $invoices = $medic->invoices()->whereIn('office_id', $offices)->whereDate('created_at', $searchDate)->orderBy('created_at', 'DESC')->paginate(20);
        $totalInvoicesAmount = $medic->invoices()->whereIn('office_id', $offices)->whereDate('created_at', $searchDate)->sum('total');
        //$noInvoices = $medic->appointments()->whereIn('office_id', $offices)->where('status', 1)->where('finished', 1)->whereDate('date', $searchDate)->doesntHave('invoices')->orderBy('created_at', 'DESC')->paginate(20);

        return view('medic.invoices.index', compact('medic', 'invoices', 'totalInvoicesAmount', 'searchDate'));
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

    /**
     * Guardar consulta(cita)
     */
    public function store()
    {
        if (!existsCertFile(auth()->user())) {
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
        if (!existsCertFile(auth()->user())) {
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
        $services = InvoiceService::where('name', 'like', '%' . request('q') . '%')->get();

        return $services;
    }

    public function saveService()
    {
        $this->validate(request(), [
                'name' => 'required',
                'amount' => 'required|numeric',
            ]);

        $service = InvoiceService::create(request()->all());

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

    /*
     public function balance()
     {
         $medic_id = auth()->id();

         $this->invoiceRepo->balance($medic_id);


         flash('Se ha ejecutado el cierre correctamente', 'success');

         return Redirect()->back();
     }*/
}
