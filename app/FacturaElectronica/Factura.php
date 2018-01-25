<?php namespace App\FacturaElectronica;

use App\FacturaElectronica\Common;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
/**
 * Clase para generar Facturas Electrónicas 
 * 
 * @package clase
 * @author Open Source Costa Rica <opensource.lat@email.com>
 * @version 1.0.0
 * @license MIT
 * @copyright 2017 (c) Open Source Costa Rica
 * 
 */

class Factura 
{
    /* ATRIBUTOS */

    /**
     * Corresponde a la clave del comprobante. Es un campo de 50 posiciones y se tiene que utilizar para la consulta 
     * del código QR. Ver nota 1 y 4.1
     * 
     * @var string $clave
     */
    private $clave;
    
    /**
     * Numeración consecutiva del comprobante
     *
     * @var string $numeroConsecutivo
     */
    private $numeroConsecutivo;

    /**
     * Undocumented variable
     *
     * @var string $fechaEmision
     */
    private $fechaEmision;

    /**
     * Undocumented variable
     *
     * @var string $emisor
     */
    private $emisor;

    /**
     * Undocumented variable
     *
     * @var string $receptor
     */
    private $receptor;

    /**
     * Undocumented variable
     *
     * @var string $condicionVenta
     */
    private $condicionVenta;

    /**
     * Undocumented variable
     *
     * @var string $plazoCredito
     */
    private $plazoCredito;

    /**
     * Undocumented variable
     *
     * @var string $medioPago
     */
    private $medioPago;

    /**
     * Undocumented variable
     *
     * @var string $detalleServicio
     */
    private $detalleServicio;

    /**
     * Undocumented variable
     *
     * @var string $resumenFactura
     */
    private $resumenFactura;

    /**
     * Undocumented variable
     *
     * @var string $normativa
     */
    private $normativa;

    /**
     * Undocumented variable
     *
     * @var string $otros
     */
    private $otros;

    /**
     * Undocumented variable
     *
     * @var string $signature
     */
    private $signature;

    /**
     * Undocumented variable
     *
     * @var string $establecimiento
     */
    private $establecimiento;


    public function __construct($emisorId, $receptorId, $numeroConsecutivo, $fechaEmision = '') 
    {

        $this->emisor = Common::validarId($emisorId);
        $this->receptor = Common::validarId($receptorId);
        $this->numeroConsecutivo = $numeroConsecutivo;
        $this->fechaEmision = ($fechaEmision == '') ? date('dmY') : $fechaEmision;
    }


    /* METODOS PUBLICOS */

    /**
     * Se obtiene la estructura de la clave
     * https://tribunet.hacienda.go.cr/docs/esquemas/2016/v4.1/Resolucion_Comprobantes_Electronicos_DGT-R-48-2016_v4.1.pdf
     * Página 7, Artículo 5° [Clave numérica]
     *
     * @param string $fechaComprobante Fecha en que se generó la factura electrónica (dd-mm-yyyy)
     * @return stting
     */
    public function getClave()
    {   
        $this->clave = Common::generarClave($this->fechaEmision, $this->emisor, Common::FACTURA, $this->numeroConsecutivo); 
        return $this;
    }
    public function generateXML($invoiceGPS = null)
    {
        $facuraBase = Storage::get('facturaelectronica/factura.xml');
        
        $facturaXML = new \SimpleXMLElement($facuraBase);
        $facturaXML->Clave = $this->clave;
        $facturaXML->NumeroConsecutivo = $this->numeroConsecutivo;
        $facturaXML->FechaEmision = Carbon::createFromFormat('dmY', $this->fechaEmision)->toAtomString();
        
        $facturaXML->Emisor->Nombre = 'alo';
        $facturaXML->Emisor->Identificacion->Tipo = '01';
        $facturaXML->Emisor->Identificacion->Numero = 503600224;
        $facturaXML->Emisor->NombreComercial = 'alo';
        $facturaXML->Emisor->Ubicacion->Provincia = '5';
        $facturaXML->Emisor->Ubicacion->Canton = '01';
        $facturaXML->Emisor->Ubicacion->Distrito = '01';
        $facturaXML->Emisor->Ubicacion->Barrio = '';
        $facturaXML->Emisor->Ubicacion->OtrasSenas = '';
        $facturaXML->Emisor->Telefono->CodigoPais = 506;
        $facturaXML->Emisor->Telefono->NumTelefono = 89679098;
        $facturaXML->Emisor->CorreoElectronico = 'alonso@avotz.com';

        $facturaXML->Receptor->Nombre = 'gera';
        $facturaXML->Receptor->Identificacion->Tipo = '01';
        $facturaXML->Receptor->Identificacion->Numero = 503600225;
        $facturaXML->Receptor->NombreComercial = '';
        $facturaXML->Receptor->Ubicacion->Provincia = '5';
        $facturaXML->Receptor->Ubicacion->Canton = '01';
        $facturaXML->Receptor->Ubicacion->Distrito = '01';
        $facturaXML->Receptor->Ubicacion->Barrio = '';
        $facturaXML->Receptor->Ubicacion->OtrasSenas = '';
        $facturaXML->Receptor->Telefono->CodigoPais = 506;
        $facturaXML->Receptor->Telefono->NumTelefono = 89679098;
        $facturaXML->Receptor->CorreoElectronico = 'oporto@avotz.com';
        
        $facturaXML->CodicionVenta = '01';
        $facturaXML->PlazoCredito = '';
        $facturaXML->MedioPago = '04';

        //$facturaXML->DetalleServicio;
        //foreach ($facturaXML->DetalleServicio->LineaDetalle as $detalle) {
            $detalle = $facturaXML->DetalleServicio->addChild('LineaDetalle');
            $detalle->addChild('NumeroLinea', '1');
            $codigo = $detalle->addChild('Codigo');
            $codigo->addChild('Tipo','04');
            $codigo->addChild('Codigo', '04');
            $detalle->addChild('Cantidad', '1000');
            $detalle->addChild('UnidadMedida', 'Unid');
            $detalle->addChild('UnidadMedidaComercial', '');
            $detalle->addChild('Detalle', 'test');
            $detalle->addChild('PrecioUnitario', '1000');
            $detalle->addChild('MontoTotal', '1000');
            $detalle->addChild('NaturalezaDescuento', '');
            $detalle->addChild('SubTotal', '1000');
            $detalle->addChild('MontoTotalLinea', '1000');
        

       // }
            $facturaXML->ResumenFactura->CodigoMoneda = 'CRC';
            $facturaXML->ResumenFactura->CodigoMoneda = '576.74000';
            $facturaXML->ResumenFactura->CodigoMoneda = '0.00000';
            $facturaXML->ResumenFactura->CodigoMoneda = '1175.00000';
            $facturaXML->ResumenFactura->CodigoMoneda = '0.00000';
            $facturaXML->ResumenFactura->CodigoMoneda = '0.00000';
            $facturaXML->ResumenFactura->CodigoMoneda = '0.00000';
            $facturaXML->ResumenFactura->CodigoMoneda = '1175.00000';
            $facturaXML->ResumenFactura->CodigoMoneda = '1175.00000';
            $facturaXML->ResumenFactura->CodigoMoneda = '0.00000';
            $facturaXML->ResumenFactura->CodigoMoneda = '1175.00000';
            $facturaXML->ResumenFactura->CodigoMoneda = '0.00000';
            $facturaXML->ResumenFactura->CodigoMoneda = '1175.00000';

        $facturaXML->Normativa->NumeroResolucion = 'DGT-R-48-2016';
        $facturaXML->Normativa->FechaResolucion = '20-02-2017 13:22:22';
        $facturaXML->Otros->OtroTexto = '';

        $facturaXML->CodicionVenta = '01';


        dd($facturaXML->asXML());
    //header('Content-Type: text/xml');
    //echo $factura->asXML();

      // dd($this->numeroConsecutivo.'---'.$this->clave);
    }

    public function imprimir()
    {
        echo 'Clave......: ', $this->clave, "\n";
        echo 'Emisor.....: ', $this->emisor, "\n";
        echo 'Receptor...: ', $this->receptor, "\n";
        echo 'Consecutivo: ', $this->numeroConsecutivo, "\n";
        echo "------------------------------ \n";
    }

    /* METODOS PRIVATOS */

}