<?php

namespace App\Repositories;


use App\User;
use App\FacturaElectronica\Factura;
use App\Balance;
use Illuminate\Support\Facades\Storage;
use App\DocumentoReferencia;
use App\FacturaElectronica\NotaCredito;
use App\FacturaElectronica\NotaDebito;
use App\Office;
use App\Factura as Fac;
use App\FacturaDetalle;

class FacturaRepository extends DbRepository
{
    /**
     * Construct
     * @param User $model
     */
    public function __construct(Fac $model, UserRepository $userRepo)
    {
        $this->model = $model;
        $this->limit = 10;
        $this->userRepo = $userRepo;
        $this->feRepo = new FacturaElectronicaRepository(env('FE_ENV'));
    }

    public function crearConsecutivo($obligadoTributario, $tipo_documento)
    {
        $consecutivo_inicio = 1;

       
        if ($tipo_documento == '01') {
            $consecutivo_inicio = $obligadoTributario->consecutivo_inicio;
        }

        if ($tipo_documento == '02') {
            $consecutivo_inicio = $obligadoTributario->consecutivo_inicio_ND;
        }

        if ($tipo_documento == '03') {
            $consecutivo_inicio = $obligadoTributario->consecutivo_inicio_NC;
        }

        $consecutivo = Fac::where('obligado_tributario_id', $obligadoTributario->id)->where('tipo_documento', $tipo_documento)->max('consecutivo');
        

        return ($consecutivo) ? $consecutivo += 1 : $consecutivo_inicio;
    }

    // public function crearConsecutivoIndependiente($user, $tipo_documento)
    // {
    //     $consecutivo_inicio = 1;

    //     if ($user->fe) {
    //         $config = $user->configFactura->first();

    //         if ($tipo_documento == '01') {
    //             $consecutivo_inicio = $config->consecutivo_inicio;
    //         }

    //         if ($tipo_documento == '02') {
    //             $consecutivo_inicio = $config->consecutivo_inicio_ND;
    //         }

    //         if ($tipo_documento == '03') {
    //             $consecutivo_inicio = $config->consecutivo_inicio_NC;
    //         }
    //     }

    //     $consecutivo = Factura::whereHas('clinic', function ($q) {
    //         $q->where('offices.type', '<>', 'Clínica Privada');
    //     })->where('user_id', $user->id)->where('tipo_documento', $tipo_documento)->where('fe', $user->fe)->max('consecutivo');

    //     return ($consecutivo) ? $consecutivo += 1 : $consecutivo_inicio;
    // }

    // public function crearConsecutivoClinicaPrivada($office, $tipo_documento)
    // {
    //     $consecutivo_inicio = 1;

    //     if ($office->fe) {
    //         $config = $office->configFactura->first();

    //         if ($tipo_documento == '01') {
    //             $consecutivo_inicio = $config->consecutivo_inicio;
    //         }

    //         if ($tipo_documento == '02') {
    //             $consecutivo_inicio = $config->consecutivo_inicio_ND;
    //         }

    //         if ($tipo_documento == '03') {
    //             $consecutivo_inicio = $config->consecutivo_inicio_NC;
    //         }
    //     }

    //     $consecutivo = Factura::where('office_id', $office->id)->where('tipo_documento', $tipo_documento)->where('fe', $office->fe)->max('consecutivo');

    //     return ($consecutivo) ? $consecutivo += 1 : $consecutivo_inicio;
    // }

    /**
     * save a appointment
     * @param $data
     */
    public function store($data, $obligadoTributario = null)
    {
        $office = isset($data['office_id']) ? Office::find($data['office_id']) : null;
        $user = auth()->user();

        $factura = $this->model;
        $factura->consecutivo = $this->crearConsecutivo($obligadoTributario, '01');

        $factura->obligado_tributario_id = $obligadoTributario->id;

        // if ($office && str_slug($office->type, '-') == 'clinica-privada') {
        //     $factura->consecutivo = $this->crearConsecutivoClinicaPrivada($office, '01');
        //     $factura->fe = $office->fe;
        // } else {
        //     $factura->consecutivo = $this->crearConsecutivoIndependiente($user, '01');
        //     $factura->fe = $user->fe;
        // }

        //$factura->bill_to = ($user->fe) ? 'M' : $data['bill_to'];

        //$factura->office_type = $data['office_type'];

      
        if (isset($data['office_id'])) {
            $factura->office_id = $data['office_id'];
        
        } else {
            $factura->office_id = session('office_id');
        }

    
        if (isset($data['pay_with'])) {
            $factura->pay_with = $data['pay_with'];
        }
        if (isset($data['change'])) {
            $factura->change = $data['change'];
        }

        $factura->status = $data['status'];

        $factura = $user->facturas()->save($factura);

        $totalFactura = 0;
        foreach ($data['services'] as $service) {
            $line = new FacturaDetalle;
            $line->name = $service['name'];
            $line->amount = $service['amount'];
            $line->quantity = 1;
            $line->total_line = $line->quantity * $line->amount;

            $totalFactura += $line->total_line;

            $factura->lines()->save($line);
        }

        $factura->subtotal = $totalFactura;
        $factura->total = $totalFactura;
        $factura->client_name = $data['client_name'];
        $factura->client_email = $data['client_email'];
        $factura->medio_pago = $data['medio_pago'];
        $factura->condicion_venta = $data['condicion_venta'];

        if (!$data['send_to_assistant']) {
            $result = $this->sendToHacienda($factura);

            if (!$result) {
                $factura->sent_to_hacienda = 1;
            }
        }

        $factura->save();

        return $factura->load('clinic');
    }

    public function update($id, $data)
    {
        $factura = $this->findById($id);
        $factura->fill($data);
        $factura->status = 1;

        
        $result = $this->sendToHacienda($factura);

        if (!$result) {
            $factura->sent_to_hacienda = 1;
        }
      

        $factura->save();

        return $factura;
    }

    public function sendToHacienda($factura)
    {
        $factura = $this->findById($factura->id);
        $obligadoTributario = $factura->obligadoTributario;

        //$office = $factura->clinic;

        // if ($office && str_slug($office->type, '-') == 'clinica-privada') {
        //     $obligadoTributario = $office->configFactura->first();
        // } else {
        //     $obligadoTributario = $factura->medic->configFactura->first();
        // }

        if ($factura->created_xml && Storage::disk('local')->exists('facturaelectronica/' . $obligadoTributario->id . '/gpsm_' . $factura->clave_fe . '_signed.xml')) {
            $facturaXML = Storage::get('facturaelectronica/' . $obligadoTributario->id . '/gpsm_' . $factura->clave_fe . '.xml');
            $facturaXML = new \SimpleXMLElement($facturaXML);

            $situacionComprobante = ($factura->sent_to_hacienda) ? '1' : '3';

            $facturaXML->Clave = substr_replace((string) $facturaXML->Clave, $situacionComprobante, 41, 1); // cambiar la situacion del comprobante 1- normal 2- contigencia 3 -no internet
            Storage::put('facturaelectronica/' . $obligadoTributario->id . '/gpsm_' . $factura->clave_fe . '.xml', $facturaXML->asXML());

            $signedinvoiceXML = $this->feRepo->signXML($obligadoTributario, $factura);

            $factura->clave_fe = (string) $facturaXML->Clave;
            $factura->save();
        } else {
            $signedinvoiceXML = $this->createFacturaXML($obligadoTributario, $factura);

            if ($signedinvoiceXML) {
                $factura->created_xml = 1;

                $factura->save();
            }
        }

        return $this->feRepo->sendHacienda($obligadoTributario, $signedinvoiceXML);
    }

    public function createFacturaXML($obligadoTributario, $factura)
    {
      

        $numeroCedulaEmisor = $obligadoTributario->identificacion;

        $miNumeroConsecutivo = $factura->consecutivo;

        if ($factura->tipo_documento == '01') {
            $fe = new Factura($numeroCedulaEmisor, null, $miNumeroConsecutivo);
        }
        if ($factura->tipo_documento == '02') {
            $fe = new NotaDebito($numeroCedulaEmisor, null, $miNumeroConsecutivo);
        }
        if ($factura->tipo_documento == '03') {
            $fe = new NotaCredito($numeroCedulaEmisor, null, $miNumeroConsecutivo);
        }

        $objFac = $fe->getClave();
      
        $factura->clave_fe = $objFac->clave;
        $factura->consecutivo_hacienda = $objFac->consecutivoHacienda;

        $factura->save();

        $facturaXML = $fe->generateXML($obligadoTributario, $factura);

        $signedinvoiceXML = $this->feRepo->signXML($obligadoTributario, $factura); //true por que es de prueba

        \Log::info('results of invoiceXML: ' . json_encode($signedinvoiceXML));

        return $signedinvoiceXML;
    }

    public function print($id)
    {
        $factura = $this->findById($id);
        $factura->load('lines');
        $factura->load('user');
        $factura->load('clinic');
        $office = $factura->clinic;

        // if ($office && str_slug($office->type, '-') == 'clinica-privada') {
        //     $config = $office->configFactura->first();
        // } else {
        //     $config = $factura->medic->configFactura->first();
        // }
        $config = $factura->obligadoTributario;


        $respHacienda = null;

        if ($config && !$factura->status_fe) {
            $respHacienda = $this->feRepo->recepcion($config, $factura->clave_fe);

            if (isset($respHacienda->{'ind-estado'})) {
                $factura->status_fe = $respHacienda->{'ind-estado'};

                // if (isset($respHacienda->{'respuesta-xml'})) {
                //     $factura->resp_hacienda = json_encode($this->feRepo->decodeRespuestaXML($respHacienda->{'respuesta-xml'}));
                // }

                $factura->save();
            }
        }

        return $factura;
    }

    public function xml($id)
    {
        $factura = $this->findById($id);
        
        $config = $factura->obligadoTributario;
        

        if (!Storage::disk('local')->exists('facturaelectronica/' . $config->id . '/gpsm_' . $factura->clave_fe . '_signed.xml')) {
            flash('Archivo no encontrado', 'danger');

            return back();
        }

        $pathToFile = storage_path('app/facturaelectronica/' . $config->id . '/gpsm_' . $factura->clave_fe . '_signed.xml');

        return response()->download($pathToFile);
    }

    public function recepcionHacienda($id)
    {
        $factura = $this->findById($id);
 
        $config = $factura->obligadoTributario;

        // if ($office && str_slug($office->type, '-') == 'clinica-privada') {
        //     $config = $office->configFactura->first();
        // } else {
        //     $config = $factura->medic->configFactura->first();
        // }
         
        $respHacienda = $this->feRepo->recepcion($config, $factura->clave_fe);

        if (isset($respHacienda->{'ind-estado'})) {
            $factura->status_fe = $respHacienda->{'ind-estado'};

            if (isset($respHacienda->{'respuesta-xml'})) {
                $factura->resp_hacienda = json_encode($this->feRepo->decodeRespuestaXML($respHacienda->{'respuesta-xml'}));
            }

            $factura->save();
        }

        return $factura;
    }

    public function balance($medic_id)
    {
        $facturas = $this->model->where('user_id', $medic_id)->where('status', 1)->whereDate('created_at', Carbon::now()->toDateString());
        $totalFacturas = $facturas->sum('total');
        $countFacturas = $facturas->count();

        if ($countInvoices == 0) {
            flash('No hay Facturas nuevas para ejecutar un cierre', 'error');

            return Redirect()->back();
        }

        $balance = Balance::create([
            'user_id' => $medic_id,
            'facturas' => $countFacturas,
            'total' => $totalFacturas
            ]);
    }

    /**
     * save a appointment
     * @param $data
     */
    public function notaCreditoDebito($data, $factura_id)
    {
        $factura = $this->findById($factura_id);
        $office = $factura->clinic;
        $user = $factura->user;
        $obligadoTributario = $factura->obligadoTributario;


        $notaDC = $this->model;

        // if ($office && str_slug($office->type, '-') == 'clinica-privada') {
        //     $notaDC->consecutivo = $this->crearConsecutivoClinicaPrivada($office, $data['type']);
        //     $notaDC->fe = $office->fe;
        // } else {
        //     $notaDC->consecutivo = $this->crearConsecutivoIndependiente($user, $data['type']);
        //     $notaDC->fe = $user->fe;
        // }
        $notaDC->consecutivo = $this->crearConsecutivo($obligadoTributario, $data['type']);

        $notaDC->obligado_tributario_id = $obligadoTributario->id;


        $notaDC->office_id = $factura->office_id;
        $notaDC->tipo_documento = $data['type'];

        $notaDC->status = 1;

        $notaDC = $user->facturas()->save($notaDC);

        $totalFactura = 0;
        foreach ($data['services'] as $service) {
            $line = new FacturaDetalle;
            $line->name = $service['name'];
            $line->amount = $service['amount'];
            $line->quantity = 1;
            $line->total_line = $line->quantity * $line->amount;

            $totalFactura += $line->total_line;

            $notaDC->lines()->save($line);
        }

        $notaDC->subtotal = $totalFactura;
        $notaDC->total = $totalFactura;
        $notaDC->client_name = $factura->client_name;
        $notaDC->client_email = $factura->client_email;
        $notaDC->medio_pago = $factura->medio_pago;
        $notaDC->condicion_venta = $factura->condicion_venta;

        foreach ($data['referencias'] as $ref) {
            $documento_ref = new DocumentoReferencia;
            $documento_ref->tipo_documento = $ref['tipo_documento'];
            $documento_ref->numero_documento = $ref['numero_documento'];
            $documento_ref->fecha_emision = $ref['fecha_emision'];
            $documento_ref->codigo_referencia = $ref['codigo_referencia'];
            $documento_ref->razon = $ref['razon'];

            $notaDC->documentosReferencia()->save($documento_ref);
        }

        
        $result = $this->sendToHacienda($notaDC);

        if (!$result) {
            $notaDC->sent_to_hacienda = 1;
        }
      

        $notaDC->save();

        return $notaDC->load('clinic');
    }

    /**
     * Find all the appointments by Doctor
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAllByDoctor($id, $search = null, $limit = 5)
    {
        $order = 'date';
        $dir = 'desc';

        $facturas = $this->model->where('user_id', $id);

        if (!$search) {
            return $facturas->with('user')->orderBy('facturas.' . $order, $dir)->paginate($limit);
        }

        if (isset($search['q']) && trim($search['q'] != '')) {
            $facturas = $facturas->Search($search['q']);
        }

        if (isset($search['date']) && $search['date'] != '') {
            $appointments = $facturas->whereDate('created_at', $search['date']);
        }

        if (isset($search['order']) && $search['order'] != '') {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != '') {
            $dir = $search['dir'];
        }

        return $appointments->with('user')->orderBy('facturas.' . $order, $dir)->paginate($limit);
    }

    private function prepareData($data)
    {
        return $data;
    }
}
