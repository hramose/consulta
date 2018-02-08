<?php

namespace App\FacturaElectronica;

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
    public $clave;

    /**
     * Numeración consecutiva del comprobante
     *
     * @var string $numeroConsecutivo
     */
    public $numeroConsecutivo;

    /**
     * Undocumented variable
     *
     * @var string $fechaEmision
     */
    public $fechaEmision;

    /**
     * Undocumented variable
     *
     * @var string $emisor
     */
    public $emisor;

    /**
     * Undocumented variable
     *
     * @var string $receptor
     */
    public $receptor;

    /**
     * Undocumented variable
     *
     * @var string $condicionVenta
     */
    public $condicionVenta;

    /**
     * Undocumented variable
     *
     * @var string $plazoCredito
     */
    public $plazoCredito;

    /**
     * Undocumented variable
     *
     * @var string $medioPago
     */
    public $medioPago;

    /**
     * Undocumented variable
     *
     * @var string $detalleServicio
     */
    public $detalleServicio;

    /**
     * Undocumented variable
     *
     * @var string $resumenFactura
     */
    public $resumenFactura;

    /**
     * Undocumented variable
     *
     * @var string $normativa
     */
    public $normativa;

    /**
     * Undocumented variable
     *
     * @var string $otros
     */
    public $otros;

    /**
     * Undocumented variable
     *
     * @var string $signature
     */
    public $signature;

    /**
     * Undocumented variable
     *
     * @var string $establecimiento
     */
    public $establecimiento;

    public function __construct($emisorId, $receptorId, $numeroConsecutivo, $tipoIdentificacionReceptor, $fechaEmision = '')
    {
        $this->emisor = Common::validarId($emisorId);
        $this->receptor = Common::validarId($receptorId);
        $this->numeroConsecutivo = $numeroConsecutivo;
        $this->fechaEmision = ($fechaEmision == '') ? date('dmy') : $fechaEmision;
        $this->tipo_identificacion_receptor = $tipoIdentificacionReceptor;
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
        $this->clave = Common::generarClave($this->fechaEmision, $this->emisor, Common::FACTURA, $this->numeroConsecutivo, '1', getUniqueNumber(8));
        return $this;
    }

    public function generateXML($user, $invoiceGPS = null)
    {
        $facuraBase = Storage::get('facturaelectronica/factura.xml');

        $facturaXML = new \SimpleXMLElement($facuraBase);
        $facturaXML->Clave = $this->clave;
        $facturaXML->NumeroConsecutivo = Common::generarConsecutivo(Common::FACTURA, $this->numeroConsecutivo); //$this->numeroConsecutivo;
        $facturaXML->FechaEmision = Carbon::createFromFormat('dmy', $this->fechaEmision)->toAtomString();

        $facturaXML->Emisor->Nombre = $user->configFactura->nombre;
        $facturaXML->Emisor->Identificacion->Tipo = $user->configFactura->tipo_identificacion;
        $facturaXML->Emisor->Identificacion->Numero = $user->configFactura->identificacion;//'205530597';
        $facturaXML->Emisor->NombreComercial = $user->configFactura->nombre_comercial;
        $facturaXML->Emisor->Ubicacion->Provincia = $user->configFactura->provincia;
        $facturaXML->Emisor->Ubicacion->Canton = $user->configFactura->canton; //bagaces
        $facturaXML->Emisor->Ubicacion->Distrito = $user->configFactura->distrito;//'03'; //guayabo mogote
        //$facturaXML->Emisor->Ubicacion->Barrio = '';
        $facturaXML->Emisor->Ubicacion->OtrasSenas = $user->configFactura->otras_senas;
        $facturaXML->Emisor->Telefono->CodigoPais = $user->configFactura->codigo_pais_tel;
        $facturaXML->Emisor->Telefono->NumTelefono = $user->configFactura->telefono;
        $facturaXML->Emisor->CorreoElectronico = $user->configFactura->email;

        $facturaXML->Receptor->Nombre = 'Alonso';
        $facturaXML->Receptor->Identificacion->Tipo = '01';
        $facturaXML->Receptor->Identificacion->Numero = '503600224';
        $facturaXML->Receptor->NombreComercial = '';
        $facturaXML->Receptor->Ubicacion->Provincia = '5';
        $facturaXML->Receptor->Ubicacion->Canton = '01';
        $facturaXML->Receptor->Ubicacion->Distrito = '01';
        //$facturaXML->Receptor->Ubicacion->Barrio = '';
        $facturaXML->Receptor->Ubicacion->OtrasSenas = 'test';
        $facturaXML->Receptor->Telefono->CodigoPais = 506;
        $facturaXML->Receptor->Telefono->NumTelefono = 89679098;
        $facturaXML->Receptor->CorreoElectronico = 'alonso@avotz.com';

        $facturaXML->CondicionVenta = '01';
        $facturaXML->PlazoCredito = '';
        $facturaXML->MedioPago = '01';

        //$facturaXML->DetalleServicio;
        //foreach ($facturaXML->DetalleServicio->LineaDetalle as $detalle) {
        $detalle = $facturaXML->DetalleServicio->addChild('LineaDetalle');
        $detalle->addChild('NumeroLinea', '1');
        $codigo = $detalle->addChild('Codigo');
        $codigo->addChild('Tipo', '04');
        $codigo->addChild('Codigo', '1');
        $detalle->addChild('Cantidad', '1.000');
        $detalle->addChild('UnidadMedida', 'Unid');
        $detalle->addChild('UnidadMedidaComercial', '');
        $detalle->addChild('Detalle', 'test');
        $detalle->addChild('PrecioUnitario', '1000.00000');
        $detalle->addChild('MontoTotal', '1000.00000');
        $detalle->addChild('NaturalezaDescuento', '');
        $detalle->addChild('SubTotal', '1000.00000');
        $detalle->addChild('MontoTotalLinea', '1000.00000');

        // }
        $facturaXML->ResumenFactura->CodigoMoneda = 'CRC';
        $facturaXML->ResumenFactura->TipoCambio = '1.00000';
        $facturaXML->ResumenFactura->TotalServGravados = '0.00000';
        $facturaXML->ResumenFactura->TotalServExentos = '1000.00000';
        $facturaXML->ResumenFactura->TotalMercanciasGravadas = '0.00000';
        $facturaXML->ResumenFactura->TotalMercanciasExentas = '0.00000';
        $facturaXML->ResumenFactura->TotalGravado = '0.00000';
        $facturaXML->ResumenFactura->TotalExento = '1000.00000';
        $facturaXML->ResumenFactura->TotalVenta = '1000.00000';
        $facturaXML->ResumenFactura->TotalDescuentos = '0.00000';
        $facturaXML->ResumenFactura->TotalVentaNeta = '1000.00000';
        $facturaXML->ResumenFactura->TotalImpuesto = '0.00000';
        $facturaXML->ResumenFactura->TotalComprobante = '1000.00000';

        $facturaXML->Normativa->NumeroResolucion = 'DGT-R-48-2016';
        $facturaXML->Normativa->FechaResolucion = '26-01-2018 13:22:22';
        $facturaXML->Otros->OtroTexto = '';

        Storage::put('facturaelectronica/' . $user->id . '/file.xml', $facturaXML->asXML());

        if (Storage::disk('local')->exists('facturaelectronica/' . $user->id . '/file.xml')) {
            return Storage::get('facturaelectronica/' . $user->id . '/file.xml');
        } else {
            dd('Error al generar el xml de la factura. Ponte en contacto con el proveedor');
        }
    }

    public function signXML($user, $test = false)
    {
        $cert = ($test) ? 'test' : 'cert';
        $pin = ($test) ? $user->configFactura->pin_certificado_test : $user->configFactura->pin_certificado;

        $salida = exec('java -jar ' . storage_path('app/facturaelectronica/xadessignercr.jar') . ' sign ' . storage_path('app/facturaelectronica/' . $user->id . '/' . $cert . '.p12') . ' ' . $pin . ' ' . storage_path('app/facturaelectronica/' . $user->id . '/file.xml') . ' ' . storage_path('app/facturaelectronica/' . $user->id . '/out.xml'));

        \Log::info('results of xadessignercr: ' . json_encode($salida));

        /*$salida2 = exec('java -jar ' . storage_path('app/facturaelectronica/xadessignercr.jar') . ' send https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1 '. storage_path('app/facturaelectronica/out.xml') . ' cpf-02-0553-0597@stag.comprobanteselectronicos.go.cr ":w:Kc.}(Og@7w}}y!c]Q" ');

        $salida3 = exec('java -jar ' . storage_path('app/facturaelectronica/xadessignercr.jar') . ' query https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1 ' . storage_path('app/facturaelectronica/out.xml') . ' cpf-02-0553-0597@stag.comprobanteselectronicos.go.cr ":w:Kc.}(Og@7w}}y!c]Q" ');*/

        if (Storage::disk('local')->exists('facturaelectronica/' . $user->id . '/out.xml')) {
            return Storage::get('facturaelectronica/' . $user->id . '/out.xml');
        } else {
            dd('Error al firmar el xml de la factura. Ponte en contacto con el proveedor');
        }
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
