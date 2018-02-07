<?php namespace App\Repositories;




use App\User;

use Carbon\Carbon;

class FacturaElectronicaRepository extends DbRepository{


    /**
     * Construct
     * @param User $model
     */
    function __construct()
    {
        $this->limit = 10;
    }

    public function get_token($username, $password, $test = null)
    {
        $client_id = ($test) ? 'api-stag' : 'api-prod';
        $url = 'https://idp.comprobanteselectronicos.go.cr/auth/realms/rut-stag/protocol/openid-connect/token';//access token url
        $data = array(
            'client_id' => $client_id,//Test: 'api-stag' Production: 'api-prod'
            'client_secret' => '',//always empty
            'grant_type' => 'password', //always 'password'
                      //go to https://www.hacienda.go.cr/ATV/login.aspx to generate a username and password credentials
            'username' => $username,// 'cpf-02-0553-0597@stag.comprobanteselectronicos.go.cr',
            'password' => $password,//':w:Kc.}(Og@7w}}y!c]Q',
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


}