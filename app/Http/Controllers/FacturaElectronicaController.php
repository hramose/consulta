<?php

namespace App\Http\Controllers;

use App\FacturaElectronica\Factura;
use GuzzleHttp\Client;
use App\Repositories\FacturaElectronicaRepository;
use App\Repositories\UserRepository;
use App\Repositories\InvoiceRepository;
use App\Invoice;

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

        \Log::info('results of Hacienda: ' . json_encode($resp));
       
        flash('No hay Facturas nuevas para ejecutar un cierre', 'error');
        //actualizar el status_fe del invoice
        //mostrar mensaje de recibido por parte de hacieda
        return $resp;
    }

     public function recepcionInvoice($id)
    {
        $invoice = $this->invoiceRepo->recepcionHacienda($id);

        return $invoice;
    }
}
