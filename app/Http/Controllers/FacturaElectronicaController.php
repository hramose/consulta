<?php

namespace App\Http\Controllers;

use App\FacturaElectronica\Factura;
use GuzzleHttp\Client;
use App\Repositories\FacturaElectronicaRepository;
use App\Repositories\UserRepository;
use App\Repositories\InvoiceRepository;
use App\Invoice;
use App\Events\HaciendaResponse;
use Carbon\Carbon;
use App\HaciendaNotification;

class FacturaElectronicaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Client $client, UserRepository $userRepo, InvoiceRepository $invoiceRepo)
    {
        $this->middleware('auth')->except('haciendaResponse');
        $this->client = $client;
        $this->userRepo = $userRepo;
        $this->invoiceRepo = $invoiceRepo;
        $this->feRepo = new FacturaElectronicaRepository('test');
    }

    public function authToken($user_id)
    {
        $user = $this->userRepo->findById($user_id);

        return json_encode($this->feRepo->get_token($user->configFactura->atv_user, $user->configFactura->atv_password));
    }

    public function generateFacturaTest($user_id, $consecutivo)
    {
        $user = $this->userRepo->findById($user_id);

        $emisor = $user->configFactura;

        $fechaEmision = '';
        $numeroCedulaEmisor = $user->configFactura->identificacion;
        //$numeroCedulaReceptor = '5-360-224';
        // $tipoIdentificacionReceptor = '01';
        //$numeroCedulaResidente = '172400110315';
        $miNumeroConsecutivo = $consecutivo;

        $factura1 = new Factura($numeroCedulaEmisor, null, $miNumeroConsecutivo, $fechaEmision);
        // $factura2 = new Factura($numeroCedulaResidente, $numeroCedulaReceptor, $miNumeroConsecutivo);

        $fac = $factura1->getClave($fechaEmision);
        $invoiceGPS = Invoice::find(1);
        $invoiceXML = $factura1->generateXML($user, $invoiceGPS);
        $signedinvoiceXML = $factura1->signXML($user, true);

        return $this->feRepo->sendHacienda($user, $signedinvoiceXML, $fac);
    }

    public function recepcion($user_id, $clave)
    {
        $user = ($user_id) ? $this->userRepo->findById($user_id) : auth()->user();

        $result = $this->feRepo->recepcion($user, $clave);

        return $result;
    }

    public function comprobante($user_id, $clave)
    {
        $user = ($user_id) ? $this->userRepo->findById($user_id) : auth()->id();

        $result = $this->feRepo->comprobante($user, $clave);

        return $result;
    }

    public function haciendaResponse()
    {
       
        $resp = request()->all();
        
        $invoice = Invoice::where('clave_fe', $resp['clave'])->first();

        if(!$invoice) return false;
        
        // $data = [
        //     "clave" => $resp['clave'],
        //     "fecha" => $resp['fecha'],
        //     "estado" => $resp['ind-estado'],
        //     "invoice_id" => $invoice->id,
        //     "medic_id" => $invoice->user_id,
        //     "office_id" => $invoice->office_id,
        //     "title" => 'Factura con estado '. $resp['ind-estado'],
        //     "body" => ($resp['ind-estado'] == 'aceptada') ? 'Factura Aceptada' : 'La Factura '. $resp['clave'] .' tiene estado de ' . $resp['ind-estado'] .' .Verfica por que situación ocurrio entrando en facturacion y verficando el estado',
        //     "created_at" => Carbon::now()->toDateString()

        // ];

       $notification = HaciendaNotification::create([
            "title" => 'Factura con estado ' . $resp['ind-estado'],
            "body" => ($resp['ind-estado'] == 'aceptada') ? 'Factura Aceptada' : 'La Factura ' . $resp['clave'] . ' tiene estado de ' . $resp['ind-estado'] . ' .Verfica por que situación ocurrio entrando en facturacion y verficando el estado',
            "medic_id" => $invoice->user_id,
            "office_id" => $invoice->office_id,
        ]);

        event(new HaciendaResponse($notification));

        \Log::info('results of Hacienda: ' . json_encode($notification));
      
    }

     public function recepcionInvoice($id)
    {
        $invoice = $this->invoiceRepo->recepcionHacienda($id);

        return $invoice;
    }
}
