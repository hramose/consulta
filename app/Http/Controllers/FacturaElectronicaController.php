<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Storage;
use App\FacturaElectronica\Factura;
use GuzzleHttp\Client;

class FacturaElectronicaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->middleware('auth');
        $this->client = $client;

    }

    public function test()
    {
        $fechaEmision = '';
        $numeroCedulaEmisor = '2-553-597';
        $numeroCedulaReceptor = '5-360-224';
        //$numeroCedulaResidente = '172400110315';
        $miNumeroConsecutivo = 1;

        $factura1 = new Factura($numeroCedulaEmisor, $numeroCedulaReceptor, $miNumeroConsecutivo, $fechaEmision);
       // $factura2 = new Factura($numeroCedulaResidente, $numeroCedulaReceptor, $miNumeroConsecutivo);
     
        $authToken = $this->get_token();//get OAuth2.0 token
       // dd($authToken);

        $fac = $factura1->getClave($fechaEmision);
        $invoiceXML = $factura1->generateXML();
        $invoice64String = $this->parseBase64($invoiceXML);
       // dd($invoiceXML);
        $data = "{\n\t\"clave\": \"$fac->clave\","
            . "\n\t\"fecha\": \"2018-01-26T00:00:00-0600\","
            . "\n\t\"emisor\": {\n\t\t\"tipoIdentificacion\": \"02\",\n\t\t\"numeroIdentificacion\": \"$fac->emisor\"\n\t},"
            . "\n\t\"receptor\": {\n\t\t\"tipoIdentificacion\": \"02\",\n\t\t\"numeroIdentificacion\": \"$fac->receptor\"\n\t},"
            . "\n\t\"callbackUrl\": \"https://example.com/invoiceView\","
            . "\n\t\"comprobanteXml\": \"$invoice64String\"\n}";

       
        //dd($fac->clave);
        $body = [
            'clave' => $fac->clave,
            'fecha' => '2018-01-26T00:00:00-0600',
            'emisor' => [
                'tipoIdentificacion' => '01',
                'numeroIdentificacion' => $fac->emisor
            ],
            'receptor' => [
                'tipoIdentificacion' => '01',
                'numeroIdentificacion' => $fac->receptor
            ],
            'comprobanteXml' => $invoice64String
        ];

        $headers = [
            'authorization' => 'Bearer '. $authToken->access_token,
            'content-type' => 'application/json'
            
        ];

        $response = $this->client->request('POST', 'https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion', ['headers' => $headers,'form_params' => $body]);
        $body = $response->getBody();
        $content = $body->getContents();
        $result = json_decode($content);
        dd($result);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/recepcion",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            // CURLOPT_POSTFIELDS => "{\n\t\"clave\": \"$fac->clave\","
            //     . "\n\t\"fecha\": \"2016-01-01T00:00:00-0600\","
            //     . "\n\t\"emisor\": {\n\t\t\"tipoIdentificacion\": \"01\",\n\t\t\"numeroIdentificacion\": \"$fac->emisor\"\n\t},"
            //     . "\n\t\"receptor\": {\n\t\t\"tipoIdentificacion\": \"01\",\n\t\t\"numeroIdentificacion\": \"$fac->receptor\"\n\t},"
            //     . "\n\t\"callbackUrl\": \"http://consulta.test/factura/response\","
            //     . "\n\t\"comprobanteXml\": \"$invoice64String\"\n}",
            //CURLOPT_COOKIE => "__cfduid=d73675273d6c68621736ad9329b7eff011507562303",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $authToken->access_token,
                "content-type: application/json"
            ),
        ));
       
        $response = curl_exec($curl);
     
        $err = curl_error($curl);
        curl_close($curl);
       // dd($response);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response);
        }

        dd($fac->clave.'----'. $authToken->access_token);
        //return $response;
        //dd($response);
       // try {
            
           // $clave = $factura1->getClave($fechaEmision);
          //  $invoiceXML = $factura1->generateXML();
                //->imprimir();
          //  dd($invoiceXML);

           // $factura2->getClave()
              //  ->imprimir();

       // } catch (\Exception $e) {
        //    echo "Error ", $e->message(), "\n";
       // }

        // $facuraURL = Storage::get('facturaelectronica/factura.xml');
  
        // $factura = new \SimpleXMLElement($facuraURL);
        // $factura->Clave = 1;
   
        // Storage::put('facturaelectronica/file.xml', $factura->asXML());
    
        // $salida = exec('java -jar ' . storage_path('app/facturaelectronica/xadessignercr.jar') . ' sign ' . storage_path('app/facturaelectronica/cert.p12') . ' 5678 ' . storage_path('app/facturaelectronica/file.xml') . ' ' . storage_path('app/facturaelectronica/out.xml'));

        // dd($salida);
    }

    public function get_token()
    {
        $url = 'https://idp.comprobanteselectronicos.go.cr/auth/realms/rut-stag/protocol/openid-connect/token';//access token url
        $data = array(
            'client_id' => 'api-stag',//Test: 'api-stag' Production: 'api-prod'
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
        $invoiceUTF8 = '';
        $len = strlen($invoice);
        for ($i = 0; $i < $len; $i++) {
            $invoiceUTF8 .= sprintf("%08b", ord($invoice {
                $i}));
        }
	    //parse byte_array to base64
        $base64 = base64_encode($invoiceUTF8);
        return $base64;
    }

    public function haciendaResponse()
    {
        dd(reques()->all());
    }

   
}
