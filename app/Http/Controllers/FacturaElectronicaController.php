<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Storage;
use App\FacturaElectronica\Factura;

class FacturaElectronicaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
      

    }

    public function test()
    {
        $fechaEmision = '';
        $numeroCedulaEmisor = '3-101-570764';
        $numeroCedulaReceptor = '4-167-661';
        $numeroCedulaResidente = '172400110315';
        $miNumeroConsecutivo = 8912;

        $factura1 = new Factura($numeroCedulaEmisor, $numeroCedulaReceptor, $miNumeroConsecutivo, $fechaEmision);
        $factura2 = new Factura($numeroCedulaResidente, $numeroCedulaReceptor, $miNumeroConsecutivo);
     
        //$authToken = $this->get_token();//get OAuth2.0 token
       // try {
            
            $clave = $factura1->getClave($fechaEmision);
            $invoiceXML = $factura1->generateXML();
                //->imprimir();
            dd($invoiceXML);

           // $factura2->getClave()
              //  ->imprimir();

       // } catch (\Exception $e) {
        //    echo "Error ", $e->message(), "\n";
       // }

//         $facuraURL = Storage::get('facturaelectronica/factura.xml');
//    //dd($facuraURL);
//         $factura = new \SimpleXMLElement($facuraURL);
//         $factura->Clave = 1;
//     //header('Content-Type: text/xml');
//     //echo $factura->asXML();
//         Storage::put('facturaelectronica/file.xml', $factura->asXML());
//     //dd('java -jar ' . Storage::url('facturaelectronica/xadessignercr.jar') . ' sign ' . Storage::url('facturaelectronica/cert.p12') . ' 5678 ' . Storage::url('facturaelectronica/file.xml') . ' ' . Storage::url('facturaelectronica/out.xml'));
//         $salida = exec('java -jar ' . Storage::url('facturaelectronica/xadessignercr.jar') . ' sign ' . Storage::url('facturaelectronica/cert.p12') . ' 5678 ' . Storage::url('facturaelectronica/file.xml') . ' ' . Storage::url('facturaelectronica/out.xml'));
//         dd($salida);
    }

   
}
