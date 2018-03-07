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

class NotaDebito
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
    public $pos;
    public $consecutivoHacienda;

    public function __construct($emisorId, $receptorId = null, $numeroConsecutivo, $fechaEmision = '')
    {
        $this->emisor = Common::validarId($emisorId);
        $this->receptor = ($receptorId) ? Common::validarId($receptorId) : null;
        $this->numeroConsecutivo = $numeroConsecutivo;
        $this->fechaEmision = ($fechaEmision == '') ? date('dmy') : $fechaEmision;
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
    public function getClave($situacionComprobante = '1', $establecimiento = '1', $pos = '1')
    {
        $this->establecimiento = $establecimiento;
        $this->pos = $pos;
        $this->clave = Common::generarClave($this->fechaEmision, $this->emisor, Common::NOTA_DEBITO, $this->numeroConsecutivo, $situacionComprobante, getUniqueNumber(8), $this->establecimiento, $this->pos);
        $this->consecutivoHacienda = Common::generarConsecutivo(Common::NOTA_DEBITO, $this->numeroConsecutivo, $this->establecimiento, $this->pos);

        return $this;
    }

    public function generateXML($configFactura, $invoice)
    {
        $facuraBase = Storage::get('facturaelectronica/nota_debito.xml');

        $facturaXML = new \SimpleXMLElement($facuraBase);
        $facturaXML->Clave = $this->clave;
        $facturaXML->NumeroConsecutivo = $this->consecutivoHacienda; //$this->numeroConsecutivo;
        $facturaXML->FechaEmision = Carbon::createFromFormat('dmy', $this->fechaEmision)->toAtomString();

        $facturaXML->Emisor->Nombre = $configFactura->nombre;
        $facturaXML->Emisor->Identificacion->Tipo = $configFactura->tipo_identificacion;
        $facturaXML->Emisor->Identificacion->Numero = $configFactura->identificacion;//'205530597';
        $facturaXML->Emisor->NombreComercial = $configFactura->nombre_comercial;
        $facturaXML->Emisor->Ubicacion->Provincia = $configFactura->provincia;
        $facturaXML->Emisor->Ubicacion->Canton = $configFactura->canton; //bagaces
        $facturaXML->Emisor->Ubicacion->Distrito = $configFactura->distrito;//'03'; //guayabo mogote
        //$facturaXML->Emisor->Ubicacion->Barrio = '';
        $facturaXML->Emisor->Ubicacion->OtrasSenas = $configFactura->otras_senas;

        if ($configFactura->codigo_pais_tel && $configFactura->telefono) {
            $facturaXML->Emisor->Telefono->CodigoPais = $configFactura->codigo_pais_tel;
            $facturaXML->Emisor->Telefono->NumTelefono = $configFactura->telefono;
        } else {
            unset($facturaXML->Emisor->Telefono);
        }
        if ($configFactura->codigo_pais_fax && $configFactura->fax) {
            $facturaXML->Emisor->Fax->CodigoPais = $configFactura->codigo_pais_fax;
            $facturaXML->Emisor->Fax->NumTelefono = $configFactura->fax;
        } else {
            unset($facturaXML->Emisor->Fax);
        }

        $facturaXML->Emisor->CorreoElectronico = $configFactura->email;

        if ($invoice->client_name) {
            $facturaXML->Receptor->Nombre = $invoice->client_name;

            if ($invoice->client_email) {
                $facturaXML->Receptor->CorreoElectronico = $invoice->client_email;
            } else {
                unset($facturaXML->Receptor->CorreoElectronico);
            }
        } else {
            unset($facturaXML->Receptor);
        }

        $facturaXML->CondicionVenta = $invoice->condicion_venta; //'01' //contado 02 credito

        $facturaXML->MedioPago = $invoice->medio_pago; //01 efectivo 02 tarjeta

        //$facturaXML->DetalleServicio;

        foreach ($invoice->lines as $key => $detail) {
            $detalle = $facturaXML->DetalleServicio->addChild('LineaDetalle');
            $detalle->addChild('NumeroLinea', $key + 1);
            $codigo = $detalle->addChild('Codigo');
            $codigo->addChild('Tipo', '04');
            $codigo->addChild('Codigo', $detail->service_id);
            $detalle->addChild('Cantidad', numberFE(1, $decimals = 3));
            $detalle->addChild('UnidadMedida', 'Unid');
            $detalle->addChild('UnidadMedidaComercial', '');
            $detalle->addChild('Detalle', $detail->name);
            $detalle->addChild('PrecioUnitario', numberFE($detail->amount, $decimals = 5));
            $detalle->addChild('MontoTotal', numberFE($detail->total_line, $decimals = 5));
            //$detalle->addChild('NaturalezaDescuento', '');
            $detalle->addChild('SubTotal', numberFE($detail->total_line, $decimals = 5));
            $detalle->addChild('MontoTotalLinea', numberFE($detail->total_line, $decimals = 5));
        }

        $facturaXML->ResumenFactura->CodigoMoneda = 'CRC';
        $facturaXML->ResumenFactura->TipoCambio = '1.00000';
        $facturaXML->ResumenFactura->TotalServGravados = '0.00000';
        $facturaXML->ResumenFactura->TotalServExentos = numberFE($invoice->subtotal, $decimals = 5);
        $facturaXML->ResumenFactura->TotalGravado = '0.00000';
        $facturaXML->ResumenFactura->TotalExento = numberFE($invoice->subtotal, $decimals = 5);
        $facturaXML->ResumenFactura->TotalVenta = numberFE($invoice->subtotal, $decimals = 5);
        $facturaXML->ResumenFactura->TotalDescuentos = '0.00000';
        $facturaXML->ResumenFactura->TotalVentaNeta = numberFE($invoice->total, $decimals = 5);
        $facturaXML->ResumenFactura->TotalImpuesto = '0.00000';
        $facturaXML->ResumenFactura->TotalComprobante = numberFE($invoice->total, $decimals = 5);

        foreach ($invoice->documentosReferencia as $key => $doc) {
            $InformacionReferencia = $facturaXML->InformacionReferencia;
            $InformacionReferencia->addChild('TipoDoc', $doc->tipo_documento);
            $InformacionReferencia->addChild('Numero', $doc->numero_documento);
            $InformacionReferencia->addChild('FechaEmision', Carbon::parse($doc->fecha_emision)->toAtomString());
            $InformacionReferencia->addChild('Codigo', $doc->codigo_referencia);
            $InformacionReferencia->addChild('Razon', $doc->razon);
        }

        $facturaXML->Normativa->NumeroResolucion = 'DGT-R-48-2016';
        $facturaXML->Normativa->FechaResolucion = Carbon::now()->format('d-m-Y h:i:s');//->toDateTimeString();
        $facturaXML->Otros->OtroTexto = 'Id y Consecutivo Sistema Interno: ' . $invoice->id . '-' . $invoice->consecutivo;

        Storage::put('facturaelectronica/' . $configFactura->id . '/gpsm_' . $invoice->clave_fe . '.xml', $facturaXML->asXML());

        if (Storage::disk('local')->exists('facturaelectronica/' . $configFactura->id . '/gpsm_' . $invoice->clave_fe . '.xml')) {
            return Storage::get('facturaelectronica/' . $configFactura->id . '/gpsm_' . $invoice->clave_fe . '.xml');
        } else {
            dd('Error al generar el xml de la factura. Ponte en contacto con el proveedor');
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
