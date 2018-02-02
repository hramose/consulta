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

    private $signTime = null;
    private $signPolicy = null;
    private $publicKey = null;
    private $privateKey = null;
    private $version = "3.2.1";

    const SIGN_POLICY_3_1 = array(
        "name" => "Política de Firma FacturaE v3.1",
        "url" => "https ://tribunet.hacienda.go.cr/docs/esquemas/2016/v4.1/Resolucion_Comprobantes_Electronicos_DGT-R-48-2016.pdf",
        "digest" => "Ohixl6upD6av8N7pEvDABhEL6hM="
    );

    

    public function __construct($emisorId, $receptorId, $numeroConsecutivo, $fechaEmision = '')
    {
        $this->emisor = Common::validarId($emisorId);
        $this->receptor = Common::validarId($receptorId);
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
    public function getClave()
    {
        $this->clave = Common::generarClave($this->fechaEmision, $this->emisor, Common::FACTURA, $this->numeroConsecutivo,'1', getUniqueNumber(8));
        return $this;
    }

    public function generateXML($invoiceGPS = null)
    {
        $facuraBase = Storage::get('facturaelectronica/factura.xml');

        $facturaXML = new \SimpleXMLElement($facuraBase);
        $facturaXML->Clave = $this->clave;
        $facturaXML->NumeroConsecutivo = Common::generarConsecutivo(Common::FACTURA, $this->numeroConsecutivo); //$this->numeroConsecutivo;
        $facturaXML->FechaEmision = Carbon::createFromFormat('dmy', $this->fechaEmision)->toAtomString();

        $facturaXML->Emisor->Nombre = 'Julio Quesada';
        $facturaXML->Emisor->Identificacion->Tipo = '01';
        $facturaXML->Emisor->Identificacion->Numero = '205530597';
        $facturaXML->Emisor->NombreComercial = 'Julio Quesada';
        $facturaXML->Emisor->Ubicacion->Provincia = '5';
        $facturaXML->Emisor->Ubicacion->Canton = '04'; //bagaces
        $facturaXML->Emisor->Ubicacion->Distrito = '03'; //guayabo mogote
        //$facturaXML->Emisor->Ubicacion->Barrio = '';
        $facturaXML->Emisor->Ubicacion->OtrasSenas = 'test';
        $facturaXML->Emisor->Telefono->CodigoPais = 506;
        $facturaXML->Emisor->Telefono->NumTelefono = 89679098;
        $facturaXML->Emisor->CorreoElectronico = 'oporto@avotz.com';

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
       
        //$this->sign(storage_path('app/facturaelectronica/020553059711.p12'), null, '1234');

        //$facturaFirmadaString = $this->injectSignature($facturaXML->asXML());
        //dd($facturaFirmadaString);
       // Storage::put('facturaelectronica/out_php.xml', $facturaFirmadaString);
        Storage::put('facturaelectronica/file.xml', $facturaXML->asXML());

        
       $salida = exec('java -jar ' . storage_path('app/facturaelectronica/xadessignercr.jar') . ' sign ' . storage_path('app/facturaelectronica/julio.p12') . ' 1234 ' . storage_path('app/facturaelectronica/file.xml') . ' ' . storage_path('app/facturaelectronica/out.xml'));

        /*$salida2 = exec('java -jar ' . storage_path('app/facturaelectronica/xadessignercr.jar') . ' send https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1 '. storage_path('app/facturaelectronica/out.xml') . ' cpf-02-0553-0597@stag.comprobanteselectronicos.go.cr ":w:Kc.}(Og@7w}}y!c]Q" ');

        $salida3 = exec('java -jar ' . storage_path('app/facturaelectronica/xadessignercr.jar') . ' query https://api.comprobanteselectronicos.go.cr/recepcion-sandbox/v1 ' . storage_path('app/facturaelectronica/out.xml') . ' cpf-02-0553-0597@stag.comprobanteselectronicos.go.cr ":w:Kc.}(Og@7w}}y!c]Q" ');

        dd($salida2);*/
        if(Storage::disk('local')->exists('facturaelectronica/out.xml'))
            return Storage::get('facturaelectronica/out.xml');
        else 
            dd('Error al firmar. ponte en contacto con el proveedor');
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

    /**
     * Generate random ID
     *
     * This method is used for generating random IDs required when signing the
     * document.
     *
     * @return int Random number
     */
    private function random()
    {
        if (function_exists('random_int')) {
            return random_int(0x10000000, 0x7FFFFFFF);
        } else {
            return rand(100000, 999999);
        }
    }

    /**
     * Set sign time
     *
     * @param int|string $time Time of the signature
     */
    public function setSignTime($time)
    {
        $this->signTime = is_string($time) ? strtotime($time) : $time;
    }

    /**
     * Load a PKCS#12 Certificate Store
     *
     * @param  string  $pkcs12File  The certificate store file name
     * @param  string  $pkcs12Pass  Encryption password for unlocking the PKCS#12 file
     * @return boolean              Success
     */
    private function loadPkcs12($pkcs12File, $pkcs12Pass = '*xXwr.6v&_]dA*+_P[_Z')
    {
  
        if (!is_file($pkcs12File)) {
            
            return false;
        }
       // dd(file_get_contents($pkcs12File));
        // Extract public and private keys from store
        if (openssl_pkcs12_read(file_get_contents($pkcs12File), $certs, $pkcs12Pass)) {
            //dd('ssaa');
            $this->publicKey = openssl_x509_read($certs['cert']);
            $this->privateKey = openssl_pkey_get_private($certs['pkey']);
        }
        //dd($this->publicKey);
        return (!empty($this->publicKey) && !empty($this->privateKey));
    }

    /**
     * Load a X.509 certificate and PEM encoded private key
     *
     * @param  string $publicPath  Path to public key PEM file
     * @param  string $privatePath Path to private key PEM file
     * @param  string $passphrase  Private key passphrase
     * @return bool                Success
     */
    private function loadX509($publicPath, $privatePath, $passphrase = '')
    {
        if (is_file($publicPath) && is_file($privatePath)) {
            $this->publicKey = openssl_x509_read(file_get_contents($publicPath));
            $this->privateKey = openssl_pkey_get_private(
                file_get_contents($privatePath),
                $passphrase
            );
        }
        return (!empty($this->publicKey) && !empty($this->privateKey));
    }

    /**
     * Sign
     *
     * @param  string  $publicPath  Path to public key PEM file or PKCS#12 certificate store
     * @param  string  $privatePath Path to private key PEM file (should be NULL in case of PKCS#12)
     * @param  string  $passphrase  Private key passphrase
     * @param  array   $policy      Facturae sign policy
     * @return boolean              Success
     */
    public function sign($publicPath, $privatePath = null, $passphrase = '', $policy = self::SIGN_POLICY_3_1)
    {
        $this->publicKey = null;
        $this->privateKey = null;
        $this->signPolicy = $policy;
        // Generate random IDs
        $this->signatureID = $this->random();
        $this->signedInfoID = $this->random();
        $this->signedPropertiesID = $this->random();
        $this->signatureValueID = $this->random();
        $this->certificateID = $this->random();
        $this->referenceID = $this->random();
        $this->signatureSignedPropertiesID = $this->random();
        $this->signatureObjectID = $this->random();
        // Load public and private keys
        if (empty($privatePath)) {
            return $this->loadPkcs12($publicPath, $passphrase);
        } else {
            return $this->loadX509($publicPath, $privatePath, $passphrase);
        }
    }

    /**
     * Inject signature
     *
     * @param  string Unsigned XML document
     * @return string Signed XML document
     */
    private function injectSignature($xml)
    {
        // Make sure we have all we need to sign the document
        if (empty($this->publicKey) || empty($this->privateKey)) {
            return $xml;
        }
        // Normalize document
        $xml = str_replace("\r", '', $xml);
        // Define namespace (NOTE: in alphabetical order)
        $xmlns = [];
        $xmlns[] = 'xmlns:ds="http://www.w3.org/2000/09/xmldsig#"';
        $xmlns[] = 'xmlns:fe="https://tribunet.hacienda.go.cr/docs/esquemas/2017/v4.2/facturaElectronica"';
        $xmlns[] = 'xmlns:xades="http://uri.etsi.org/01903/v1.3.2#"';
        $xmlns = implode(' ', $xmlns);
        // Prepare signed properties
        $signTime = is_null($this->signTime) ? time() : $this->signTime;
        $certData = openssl_x509_parse($this->publicKey);
        $certDigest = openssl_x509_fingerprint($this->publicKey, 'sha256', true);
        $certDigest = base64_encode($certDigest);
        $certIssuer = [];
        foreach ($certData['issuer'] as $item => $value) {
            $certIssuer[] = $item . '=' . $value;
        }
        $certIssuer = implode(',', $certIssuer);
        // Generate signed properties
        $prop = '<xades:SignedProperties Id="Signature' . $this->signatureID .
            '-SignedProperties' . $this->signatureSignedPropertiesID . '">' .
            '<xades:SignedSignatureProperties>' .
            '<xades:SigningTime>' . date('c', $signTime) . '</xades:SigningTime>' .
            '<xades:SigningCertificate>' .
            '<xades:Cert>' .
            '<xades:CertDigest>' .
            '<ds:DigestMethod Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha256"></ds:DigestMethod>' .
            '<ds:DigestValue>' . $certDigest . '</ds:DigestValue>' .
            '</xades:CertDigest>' .
            '<xades:IssuerSerial>' .
            '<ds:X509IssuerName>' . $certIssuer . '</ds:X509IssuerName>' .
            '<ds:X509SerialNumber>' . $certData['serialNumber'] . '</ds:X509SerialNumber>' .
            '</xades:IssuerSerial>' .
            '</xades:Cert>' .
            '</xades:SigningCertificate>' .
            '<xades:SignaturePolicyIdentifier>' .
            '<xades:SignaturePolicyId>' .
            '<xades:SigPolicyId>' .
            '<xades:Identifier>' . $this->signPolicy['url'] . '</xades:Identifier>' .
            '<xades:Description>' . $this->signPolicy['name'] . '</xades:Description>' .
            '</xades:SigPolicyId>' .
            '<xades:SigPolicyHash>' .
            '<ds:DigestMethod Algorithm="http://www.w3.org/2001/04/xmlenc#sha256"></ds:DigestMethod>' .
            '<ds:DigestValue>' . $this->signPolicy['digest'] . '</ds:DigestValue>' .
            '</xades:SigPolicyHash>' .
            '</xades:SignaturePolicyId>' .
            '</xades:SignaturePolicyIdentifier>' .
            '<xades:SignerRole>' .
            '<xades:ClaimedRoles>' .
            '<xades:ClaimedRole>emisor</xades:ClaimedRole>' .
            '</xades:ClaimedRoles>' .
            '</xades:SignerRole>' .
            '</xades:SignedSignatureProperties>' .
            '<xades:SignedDataObjectProperties>' .
            '<xades:DataObjectFormat ObjectReference="#Reference-ID-' . $this->referenceID . '">' .
            '<xades:Description>Factura electrónica</xades:Description>' .
            '<xades:MimeType>text/xml</xades:MimeType>' .
            '</xades:DataObjectFormat>' .
            '</xades:SignedDataObjectProperties>' .
            '</xades:SignedProperties>';
        // Prepare key info
        $publicPEM = '';
        openssl_x509_export($this->publicKey, $publicPEM);
        $publicPEM = str_replace('-----BEGIN CERTIFICATE-----', '', $publicPEM);
        $publicPEM = str_replace('-----END CERTIFICATE-----', '', $publicPEM);
        $publicPEM = str_replace("\n", '', $publicPEM);
        $publicPEM = str_replace("\r", '', chunk_split($publicPEM, 76));
        $privateData = openssl_pkey_get_details($this->privateKey);
        $modulus = chunk_split(base64_encode($privateData['rsa']['n']), 76);
        $modulus = str_replace("\r", '', $modulus);
        $exponent = base64_encode($privateData['rsa']['e']);
        // Generate KeyInfo
        $kInfo = '<ds:KeyInfo Id="Certificate' . $this->certificateID . '">' . "\n" .
            '<ds:X509Data>' . "\n" .
            '<ds:X509Certificate>' . "\n" . $publicPEM . '</ds:X509Certificate>' . "\n" .
            '</ds:X509Data>' . "\n" .
            '<ds:KeyValue>' . "\n" .
            '<ds:RSAKeyValue>' . "\n" .
            '<ds:Modulus>' . "\n" . $modulus . '</ds:Modulus>' . "\n" .
            '<ds:Exponent>' . $exponent . '</ds:Exponent>' . "\n" .
            '</ds:RSAKeyValue>' . "\n" .
            '</ds:KeyValue>' . "\n" .
            '</ds:KeyInfo>';
        // Calculate digests
        $propDigest = base64_encode(sha1(str_replace(
            '<xades:SignedProperties',
            '<xades:SignedProperties ' . $xmlns,
            $prop
        ), true));
        $kInfoDigest = base64_encode(sha1(str_replace(
            '<ds:KeyInfo',
            '<ds:KeyInfo ' . $xmlns,
            $kInfo
        ), true));
        $documentDigest = base64_encode(sha1($xml, true));
        // Generate SignedInfo
        $sInfo = '<ds:SignedInfo Id="Signature-SignedInfo' . $this->signedInfoID . '">' . "\n" .
            '<ds:CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315">' .
            '</ds:CanonicalizationMethod>' . "\n" .
            '<ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1">' .
            '</ds:SignatureMethod>' . "\n" .
            '<ds:Reference Id="SignedPropertiesID' . $this->signedPropertiesID . '" ' .
            'Type="http://uri.etsi.org/01903#SignedProperties" ' .
            'URI="#Signature' . $this->signatureID . '-SignedProperties' .
            $this->signatureSignedPropertiesID . '">' . "\n" .
            '<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">' .
            '</ds:DigestMethod>' . "\n" .
            '<ds:DigestValue>' . $propDigest . '</ds:DigestValue>' . "\n" .
            '</ds:Reference>' . "\n" .
            '<ds:Reference URI="#Certificate' . $this->certificateID . '">' . "\n" .
            '<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">' .
            '</ds:DigestMethod>' . "\n" .
            '<ds:DigestValue>' . $kInfoDigest . '</ds:DigestValue>' . "\n" .
            '</ds:Reference>' . "\n" .
            '<ds:Reference Id="Reference-ID-' . $this->referenceID . '" URI="">' . "\n" .
            '<ds:Transforms>' . "\n" .
            '<ds:Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature">' .
            '</ds:Transform>' . "\n" .
            '</ds:Transforms>' . "\n" .
            '<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">' .
            '</ds:DigestMethod>' . "\n" .
            '<ds:DigestValue>' . $documentDigest . '</ds:DigestValue>' . "\n" .
            '</ds:Reference>' . "\n" .
            '</ds:SignedInfo>';
        // Calculate signature
        $signaturePayload = str_replace('<ds:SignedInfo', '<ds:SignedInfo ' . $xmlns, $sInfo);
        $signatureResult = '';
        openssl_sign($signaturePayload, $signatureResult, $this->privateKey);
        $signatureResult = chunk_split(base64_encode($signatureResult), 76);
        $signatureResult = str_replace("\r", '', $signatureResult);
        // Make signature
        $sig = '<ds:Signature xmlns:xades="http://uri.etsi.org/01903/v1.3.2#" Id="Signature' . $this->signatureID . '">' . "\n" .
            $sInfo . "\n" .
            '<ds:SignatureValue Id="SignatureValue' . $this->signatureValueID . '">' . "\n" .
            $signatureResult .
            '</ds:SignatureValue>' . "\n" .
            $kInfo . "\n" .
            '<ds:Object Id="Signature' . $this->signatureID . '-Object' . $this->signatureObjectID . '">' .
            '<xades:QualifyingProperties Target="#Signature' . $this->signatureID . '">' .
            $prop .
            '</xades:QualifyingProperties>' .
            '</ds:Object>' .
            '</ds:Signature>';
        // Inject signature
        //dd($sig);
        $xml = str_replace('</FacturaElectronica>', $sig . '</FacturaElectronica>', $xml);
        return $xml;
    }
}
