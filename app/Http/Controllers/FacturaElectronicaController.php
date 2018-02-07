<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Storage;
use App\FacturaElectronica\Factura;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use App\Repositories\FacturaElectronicaRepository;
use App\Repositories\UserRepository;

class FacturaElectronicaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Client $client, FacturaElectronicaRepository $feRepo, UserRepository $userRepo)
    {
        $this->middleware('auth')->except('haciendaResponse');
        $this->client = $client;
        $this->userRepo = $userRepo;
        $this->feRepo = $feRepo;

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
            'callbackUrl' => env('APP_URL').'/factura/response',
            'comprobanteXml' => $invoice64String
        ];

        $headers = [
            'authorization' => 'Bearer '. $authToken->access_token,
            'content-type' => 'application/json'
            
        ];
      
        $response = $this->client->request('POST', 'https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion', ['headers' => $headers, 'json' => $body]);
        $body = $response->getBody();
        $content = $body->getContents();
        $result = json_decode($content);
        //return $result;
       // dd(json_encode($result) . '-' . json_encode($body) . '----' . $fac->clave . '----' . $authToken->access_token);
        if(!$result){
            $headers = [
                'authorization' => 'Bearer ' . $authToken->access_token,
                'content-type' => 'application/json'

            ];
            $response = $this->client->request('GET', 'https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion/'. $fac->clave , ['headers' => $headers]);
            $body = $response->getBody();
            $content = $body->getContents();
            $result = json_decode($content);

            return json_encode($result);
           // dd(json_encode($result) .'-'. json_encode($body) . '----'.$fac->clave . '----' . $authToken->access_token);
        }else{
            dd('ss');
        }

    }

    public function get_token()
    {
        $client_id = 'api-stag';
        $url = 'https://idp.comprobanteselectronicos.go.cr/auth/realms/rut-stag/protocol/openid-connect/token';//access token url
        $data = array(
            'client_id' => $client_id,//Test: 'api-stag' Production: 'api-prod'
            'client_secret' => '',//always empty
            'grant_type' => 'password', //always 'password'
                      //go to https://www.hacienda.go.cr/ATV/login.aspx to generate a username and password credentials
            'username' => 'cpf-02-0553-0597@stag.comprobanteselectronicos.go.cr',
            'password' => ':w:Kc.}(Og@7w}}y!c]Q',
            'scope' => ''
        );//always empty
        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === false) {
            echo $result;
        }
        $token = json_decode($result); //get a token object
        return $token; //return a json object whith token and refresh token
    }

    public function parseBase64($invoice)
    {
		//set $data to UTF-8 format
        /*$invoiceUTF8 = '';
        $len = strlen($invoice);
        for ($i = 0; $i < $len; $i++) {
            $invoiceUTF8 .= sprintf("%08b", ord($invoice {
                $i}));
        }*/
	    //parse byte_array to base64
        $base64 = base64_encode($invoice);
        return $base64;
    }
    private function decodeRespuestaXML($respuestaBase64)
    {
        $facturaXML = new \SimpleXMLElement(base64_decode($respuestaBase64));
        return $facturaXML;
    }
    
    public function authToken($user_id)
    {
        
        $user = $this->userRepo->findById($user_id);

        return json_encode($this->feRepo->get_token($user->configFactura->atv_user, $user->configFactura->atv_password, true));

       
    }
    public function generateFacturaTest($user_id, $consecutivo)
    {
        $user = $this->userRepo->findById($user_id);
        $fechaEmision = '';
        $numeroCedulaEmisor = '2-553-597';
        $numeroCedulaReceptor = '5-360-224';
        //$numeroCedulaResidente = '172400110315';
        $miNumeroConsecutivo = $consecutivo;

        $factura1 = new Factura($numeroCedulaEmisor, $numeroCedulaReceptor, $miNumeroConsecutivo, $fechaEmision);
       // $factura2 = new Factura($numeroCedulaResidente, $numeroCedulaReceptor, $miNumeroConsecutivo);

        $authToken = $this->feRepo->get_token($user->configFactura->atv_user, $user->configFactura->atv_password, true);//$this->get_token();//get OAuth2.0 token
        //dd($authToken);

        $fac = $factura1->getClave($fechaEmision);
        $invoiceXML = $factura1->generateXML();
        $invoice64String = $this->parseBase64($invoiceXML);



        $body = [
            'clave' => $fac->clave,
            'fecha' => '2018-02-07T00:00:00-0600',
            'emisor' => [
                'tipoIdentificacion' => $user->configFactura->tipo_identificacion,
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
    public function recepcion($user_id, $clave)
    {
        
        $user = ($user_id) ? $this->userRepo->findById($user_id) : auth()->id();
    
        $authToken = $this->feRepo->get_token($user->configFactura->atv_user, $user->configFactura->atv_password, true);//$this->get_token();

        $headers = [
            'authorization' => 'Bearer ' . $authToken->access_token,
            'content-type' => 'application/json'

        ];
      

        try {
            $response = $this->client->request('GET', 'https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion/' . $clave, ['headers' => $headers]);
            
            $body = $response->getBody();
            $content = $body->getContents();
            $result = json_decode($content);
            
            return json_encode($this->decodeRespuestaXML($result->{'respuesta-xml'}));

        } catch (\GuzzleHttp\Exception\ClientException $e) {

            return Psr7\str($e->getResponse());
           
        }
        
       
    }
    public function comprobante($user_id, $clave)
    {
       
        $user = ($user_id) ? $this->userRepo->findById($user_id) : auth()->id();

        $authToken = $this->feRepo->get_token($user->configFactura->atv_user, $user->configFactura->atv_password, true);//$this->get_token();

        $headers = [
            'authorization' => 'Bearer ' . $authToken->access_token,
            'content-type' => 'application/json'

        ];


        try {
            $response = $this->client->request('GET', 'https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/comprobantes/' . $clave, ['headers' => $headers]);

            $body = $response->getBody();
            $content = $body->getContents();
            $result = json_decode($content);

            return json_encode($result);

        } catch (\GuzzleHttp\Exception\ClientException $e) {

            return Psr7\str($e->getResponse());

        }


    }
    public function haciendaResponse()
    {
        \Log::info('results of Hacienda: ' . json_encode(request()->all()));
      
    }

    
    

   
}
