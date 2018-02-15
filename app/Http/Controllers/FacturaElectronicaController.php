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

    public function test($consecutivo)
    {
        $fechaEmision = '';
        $numeroCedulaEmisor = '2-553-597';
        $numeroCedulaReceptor = '5-360-224';
        //$numeroCedulaResidente = '172400110315';
        $miNumeroConsecutivo = $consecutivo;

        $factura1 = new Factura($numeroCedulaEmisor, $numeroCedulaReceptor, $miNumeroConsecutivo, $fechaEmision);
        // $factura2 = new Factura($numeroCedulaResidente, $numeroCedulaReceptor, $miNumeroConsecutivo);

        $authToken = $this->get_token();//get OAuth2.0 token
        //dd($authToken);

        $fac = $factura1->getClave($fechaEmision);
        $invoiceXML = $factura1->generateXML();
        $invoice64String = $this->parseBase64($invoiceXML);

        $body = [
            'clave' => $fac->clave,
            'fecha' => '2018-02-07T00:00:00-0600',
            'emisor' => [
                'tipoIdentificacion' => '01',
                'numeroIdentificacion' => $fac->emisor
            ],
            'receptor' => [
                'tipoIdentificacion' => '01',
                'numeroIdentificacion' => $fac->receptor
            ],
            'callbackUrl' => env('APP_URL') . '/factura/response',
            'comprobanteXml' => $invoice64String
        ];

        $headers = [
            'authorization' => 'Bearer ' . $authToken->access_token,
            'content-type' => 'application/json'
        ];

        $response = $this->client->request('POST', 'https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion', ['headers' => $headers, 'json' => $body]);
        $body = $response->getBody();
        $content = $body->getContents();
        $result = json_decode($content);
        //return $result;
        // dd(json_encode($result) . '-' . json_encode($body) . '----' . $fac->clave . '----' . $authToken->access_token);
        if (!$result) {
            $headers = [
                'authorization' => 'Bearer ' . $authToken->access_token,
                'content-type' => 'application/json'
            ];
            $response = $this->client->request('GET', 'https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion/' . $fac->clave, ['headers' => $headers]);
            $body = $response->getBody();
            $content = $body->getContents();
            $result = json_decode($content);

            return json_encode($result);
            // dd(json_encode($result) .'-'. json_encode($body) . '----'.$fac->clave . '----' . $authToken->access_token);
        } else {
            dd('ss');
        }
    }

    private function decodeRespuestaXML($respuestaBase64)
    {
        $facturaXML = new \SimpleXMLElement(base64_decode($respuestaBase64));
        return $facturaXML;
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
        \Log::info('results of Hacienda: ' . json_encode(request()->all()));

        //actualizar el status_fe del invoice
        //mostrar mensaje de recibido por parte de hacieda
    }

     public function recepcionInvoice($id)
    {
        $invoice = $this->invoiceRepo->recepcionHacienda($id);

        return $invoice;
    }
}
