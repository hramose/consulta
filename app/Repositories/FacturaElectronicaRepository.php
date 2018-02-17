<?php

namespace App\Repositories;

use App\User;
use GuzzleHttp\Client;
use Carbon\Carbon;

class FacturaElectronicaRepository extends DbRepository
{
    /**
     * Construct
     * @param User $model
     */
    public function __construct($type = 'prod')
    {
        $this->limit = 10;
        //$this->client = $client;
        switch ($type) {
            case 'prod':
                $this->baseUrl = 'https://api.comprobanteselectronicos.go.cr/recepcion/v1/';
                $this->authUrl = 'https://idp.comprobanteselectronicos.go.cr/auth/realms/rut/protocol/openid-connect/token';
                $this->clientId = 'api-prod';
                break;
            case 'test':
                $this->baseUrl = 'https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1/';
                $this->authUrl = 'https://idp.comprobanteselectronicos.go.cr/auth/realms/rut-stag/protocol/openid-connect/token';
                $this->clientId = 'api-stag';
                break;
            }
        $this->accessToken = null;
        $this->refreshToken = null;

        $this->client = new Client([
            'timeout' => 60,
        ]);
    }

    public function get_token($username, $password)
    {
        $client_secret = '';
        $scope = '';
        $grant_type = 'password';
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $body = [
            'client_id' => $this->clientId,
            'username' => $username,
            'password' => $password,
            'client_secret' => $client_secret,
            'scope' => $scope,
            'grant_type' => $grant_type,
        ];
        try {
            $response = $this->client->request('POST', $this->authUrl, ['form_params' => $body]);
            $body = $response->getBody();
            $content = $body->getContents();
            $result = json_decode($content);

            if (!empty($result->access_token) && !empty($result->refresh_token)) {
                // $this->setAccessToken($result->access_token);
                // $this->setRefreshToken($result->refresh_token);
                return $result;
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return \GuzzleHttp\Psr7\str($e->getResponse());
        }

        /*$url = 'https://idp.comprobanteselectronicos.go.cr/auth/realms/rut-stag/protocol/openid-connect/token';//access token url
        $data = [
            'client_id' => $client_id, //Test: 'api-stag' Production: 'api-prod'
            'client_secret' => '', //always empty
            'grant_type' => 'password', //always 'password'
                      //go to https://www.hacienda.go.cr/ATV/login.aspx to generate a username and password credentials
            'username' => $username, // 'cpf-02-0553-0597@stag.comprobanteselectronicos.go.cr',
            'password' => $password, //':w:Kc.}(Og@7w}}y!c]Q',
            'scope' => ''
        ];//always empty
        // use key 'http' even if you send the request to https://...
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context = stream_context_create($options);

        $result = file_get_contents($url, false, $context);

        if ($result === false) {
            echo $result;
        }

        $token = json_decode($result); //get a token object
        return $token; //return a json object whith token and refresh token*/
    }

    public function sendHacienda($user, $signedinvoiceXML, $encabezadoFactura)
    {
        $invoice64String = $this->parseBase64($signedinvoiceXML);

        $authToken = $this->get_token($user->configFactura->atv_user, $user->configFactura->atv_password);

        $body = [
            'clave' => $encabezadoFactura->clave,
            'fecha' => Carbon::createFromFormat('dmy', $encabezadoFactura->fechaEmision)->toAtomString(),
            'emisor' => [
                'tipoIdentificacion' => $user->configFactura->tipo_identificacion,
                'numeroIdentificacion' => $encabezadoFactura->emisor
            ],
            // 'receptor' => [
            //     'tipoIdentificacion' => $encabezadoFactura->tipo_identificacion_receptor,
            //     'numeroIdentificacion' => $encabezadoFactura->receptor
            // ],
            'callbackUrl' => env('APP_URL') . '/factura/response',
            'comprobanteXml' => $invoice64String
        ];

        $headers = [
            'authorization' => 'Bearer ' . $authToken->access_token,
            'content-type' => 'application/json'
        ];

        try {
            $response = $this->client->request('POST', $this->baseUrl . 'recepcion', ['headers' => $headers, 'json' => $body]);
            $body = $response->getBody();
            $content = $body->getContents();
            $result = json_decode($content);

            return $result;
            /*if (!$result) {
                $headers = [
                            'authorization' => 'Bearer ' . $authToken->access_token,
                            'content-type' => 'application/json'
                        ];

                $response = $this->client->request('GET', $this->baseUrl . 'recepcion/' . $encabezadoFactura->clave, ['headers' => $headers]);
                $body = $response->getBody();
                $content = $body->getContents();
                $result = json_decode($content);

                return json_encode($result);
            } else {
                return 'Ha ocurrido un error al enviar la factura a hacienda';
            }*/
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return \GuzzleHttp\Psr7\str($e->getResponse());
        }
    }

    public function recepcion($user, $clave) //ver el estado de la factura enviada a hacienda
    {
        $authToken = $this->get_token($user->configFactura->atv_user, $user->configFactura->atv_password);//$this->get_token();

        $headers = [
            'authorization' => 'Bearer ' . $authToken->access_token,
            'content-type' => 'application/json'
        ];

        try {
            $response = $this->client->request('GET', $this->baseUrl . 'recepcion/' . $clave, ['headers' => $headers]);

            $body = $response->getBody();
            $content = $body->getContents();
            $result = json_decode($content);

            return $result;//json_encode($this->decodeRespuestaXML($result->{'respuesta-xml'}));
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return \GuzzleHttp\Psr7\str($e->getResponse());
        }
    }

    public function comprobante($user, $clave)
    {
        $authToken = $this->get_token($user->configFactura->atv_user, $user->configFactura->atv_password);

        $headers = [
            'authorization' => 'Bearer ' . $authToken->access_token,
            'content-type' => 'application/json'
        ];

        try {
            $response = $this->client->request('GET', $this->baseUrl . 'comprobantes/' . $clave, ['headers' => $headers]);

            $body = $response->getBody();
            $content = $body->getContents();
            $result = json_decode($content);

            return json_encode($result);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return \GuzzleHttp\Psr7\str($e->getResponse());
        }
    }

    public function decodeRespuestaXML($respuestaBase64)
    {
        $facturaXML = new \SimpleXMLElement(base64_decode($respuestaBase64));

        return $facturaXML;
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
}
