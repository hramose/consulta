<?php

namespace App\Repositories;

use App\Invoice;
use App\User;
use App\InvoiceLine;
use App\FacturaElectronica\Factura;
use App\Balance;
use Illuminate\Support\Facades\Storage;
use App\DocumentoReferencia;
use App\FacturaElectronica\NotaCredito;
use App\FacturaElectronica\NotaDebito;
use App\Office;

class InvoiceRepository extends DbRepository
{
    /**
     * Construct
     * @param User $model
     */
    public function __construct(Invoice $model, UserRepository $userRepo)
    {
        $this->model = $model;
        $this->limit = 10;
        $this->userRepo = $userRepo;
        $this->feRepo = new FacturaElectronicaRepository(env('FE_ENV'));
    }

    public function crearConsecutivoIndependiente($user, $tipo_documento)
    {
        $consecutivo_inicio = 1;

        if ($user->fe) {
            $config = $user->configFactura->first();

            if ($tipo_documento == '01') {
                $consecutivo_inicio = $config->consecutivo_inicio;
            }

            if ($tipo_documento == '02') {
                $consecutivo_inicio = $config->consecutivo_inicio_ND;
            }

            if ($tipo_documento == '03') {
                $consecutivo_inicio = $config->consecutivo_inicio_NC;
            }

           
        }
        
        $consecutivo = Invoice::whereHas('clinic', function ($q) {
            $q->where('offices.type', '<>', 'Clínica Privada');
        })->where('user_id', $user->id)->where('tipo_documento', $tipo_documento)->where('fe', $user->fe)->max('consecutivo');
       
        
        return ($consecutivo) ? $consecutivo += 1 : $consecutivo_inicio;
    }

    public function crearConsecutivoClinicaPrivada($office, $tipo_documento)
    {
        $consecutivo_inicio = 1;

        if ($office->fe) {
            $config = $office->configFactura->first();

            if ($tipo_documento == '01') {
                $consecutivo_inicio = $config->consecutivo_inicio;
            }

            if ($tipo_documento == '02') {
                $consecutivo_inicio = $config->consecutivo_inicio_ND;
            }

            if ($tipo_documento == '03') {
                $consecutivo_inicio = $config->consecutivo_inicio_NC;
            }
        }

        $consecutivo = Invoice::where('office_id', $office->id)->where('tipo_documento', $tipo_documento)->where('fe', $office->fe)->max('consecutivo');

        return ($consecutivo) ? $consecutivo += 1 : $consecutivo_inicio;
    }

    /**
     * save a appointment
     * @param $data
     */
    public function store($data, $userInvoice = null)
    {
        $office = isset($data['office_id']) ? Office::find($data['office_id']) : null;
        $user = ($userInvoice) ? $userInvoice : auth()->user();

        $invoice = $this->model;

        if ($office && str_slug($office->type, '-') == 'clinica-privada') {
            $invoice->consecutivo = $this->crearConsecutivoClinicaPrivada($office, '01');
            $invoice->fe = $office->fe;
        } else {
            $invoice->consecutivo = $this->crearConsecutivoIndependiente($user, '01');
            $invoice->fe = $user->fe;
        }

        //$invoice->bill_to = ($user->fe) ? 'M' : $data['bill_to'];

        //$invoice->office_type = $data['office_type'];

        if (isset($data['appointment_id'])) {
            $invoice->appointment_id = $data['appointment_id'];
        }
        if (isset($data['office_id'])) {
            $invoice->office_id = $data['office_id'];
        }
        if (isset($data['patient_id'])) {
            $invoice->patient_id = $data['patient_id'];
        }

        if (isset($data['pay_with'])) {
            $invoice->pay_with = $data['pay_with'];
        }
        if (isset($data['change'])) {
            $invoice->change = $data['change'];
        }

        $invoice->status = $data['status'];

        $invoice = $user->invoices()->save($invoice);

        $totalInvoice = 0;
        foreach ($data['services'] as $service) {
            $line = new InvoiceLine;
            $line->name = $service['name'];
            $line->amount = $service['amount'];
            $line->quantity = 1;
            $line->total_line = $line->quantity * $line->amount;

            $totalInvoice += $line->total_line;

            $invoice->lines()->save($line);
        }

        $invoice->subtotal = $totalInvoice;
        $invoice->total = $totalInvoice;
        $invoice->client_name = $data['client_name'];
        $invoice->client_email = $data['client_email'];
        $invoice->medio_pago = $data['medio_pago'];
        $invoice->condicion_venta = $data['condicion_venta'];

        if ($invoice->fe && !$data['send_to_assistant']) {
            $result = $this->sendToHacienda($invoice);

            if (!$result) {
                $invoice->sent_to_hacienda = 1;
            }
        }

        $invoice->save();

        return $invoice->load('clinic');
    }

    public function update($id, $data)
    {
        $invoice = $this->findById($id);
        $invoice->fill($data);
        $invoice->status = 1;

        if ($invoice->fe) {
            $result = $this->sendToHacienda($invoice);

            if (!$result) {
                $invoice->sent_to_hacienda = 1;
            }
        }

        $invoice->save();

        return $invoice;
    }

    public function sendToHacienda($invoice)
    {
        $invoice = $this->findById($invoice->id);
        $office = $invoice->clinic;

        if ($office && str_slug($office->type, '-') == 'clinica-privada') {
            $configFactura = $office->configFactura->first();
        } else {
            $configFactura = $invoice->medic->configFactura->first();
        }

        if ($invoice->created_xml && Storage::disk('local')->exists('facturaelectronica/' . $configFactura->id . '/gpsm_' . $invoice->clave_fe . '_signed.xml')) {
            $invoiceXML = Storage::get('facturaelectronica/' . $configFactura->id . '/gpsm_' . $invoice->clave_fe . '.xml');
            $facturaXML = new \SimpleXMLElement($invoiceXML);

            $situacionComprobante = ($invoice->sent_to_hacienda) ? '1' : '3';

            $facturaXML->Clave = substr_replace((string) $facturaXML->Clave, $situacionComprobante, 41, 1); // cambiar la situacion del comprobante 1- normal 2- contigencia 3 -no internet
            Storage::put('facturaelectronica/' . $configFactura->id . '/gpsm_' . $invoice->clave_fe . '.xml', $facturaXML->asXML());

            $signedinvoiceXML = $this->feRepo->signXML($configFactura, $invoice);

            $invoice->clave_fe = (string) $facturaXML->Clave;
            $invoice->save();
        } else {
            $signedinvoiceXML = $this->createFacturaXML($configFactura, $invoice);

            if ($signedinvoiceXML) {
                $invoice->created_xml = 1;

                $invoice->save();
            }
        }

        return $this->feRepo->sendHacienda($configFactura, $signedinvoiceXML);
    }

    public function createFacturaXML($configFactura, $invoice)
    {
        //$user = $invoice->medic;//$this->userRepo->findById($invoice->user_id);

        $numeroCedulaEmisor = $configFactura->identificacion;

        $miNumeroConsecutivo = $invoice->consecutivo;

        if ($invoice->tipo_documento == '01') {
            $factura = new Factura($numeroCedulaEmisor, null, $miNumeroConsecutivo);
        }
        if ($invoice->tipo_documento == '02') {
            $factura = new NotaDebito($numeroCedulaEmisor, null, $miNumeroConsecutivo);
        }
        if ($invoice->tipo_documento == '03') {
            $factura = new NotaCredito($numeroCedulaEmisor, null, $miNumeroConsecutivo);
        }

        $fac = $factura->getClave();

        $invoice->clave_fe = $fac->clave;
        $invoice->consecutivo_hacienda = $fac->consecutivoHacienda;

        $invoice->save();

        $invoiceXML = $factura->generateXML($configFactura, $invoice);

        $signedinvoiceXML = $this->feRepo->signXML($configFactura, $invoice); //true por que es de prueba

        \Log::info('results of invoiceXML: ' . json_encode($signedinvoiceXML));

        return $signedinvoiceXML;
    }

    public function print($id)
    {
        $invoice = $this->findById($id);
        $invoice->load('lines');
        $invoice->load('medic');
        $invoice->load('clinic');
        $invoice->load('appointment.patient');
        $office = $invoice->clinic;

        if ($office && str_slug($office->type, '-') == 'clinica-privada') {
            $config = $office->configFactura->first();

        } else {
            $config = $invoice->medic->configFactura->first();

        }
        
        $respHacienda = null;

        if ($invoice->fe && $config && !$invoice->status_fe) {
            $respHacienda = $this->feRepo->recepcion($config, $invoice->clave_fe);

            if (isset($respHacienda->{'ind-estado'})) {
                $invoice->status_fe = $respHacienda->{'ind-estado'};

                // if (isset($respHacienda->{'respuesta-xml'})) {
                //     $invoice->resp_hacienda = json_encode($this->feRepo->decodeRespuestaXML($respHacienda->{'respuesta-xml'}));
                // }

                $invoice->save();
            }
        }

        return $invoice;
    }

    public function xml($id)
    {
        $invoice = $this->findById($id);
        $office = $invoice->clinic;

        if ($office && str_slug($office->type, '-') == 'clinica-privada') {
            $config = $office->configFactura->first();
        } else {
            $config = $invoice->medic->configFactura->first();
        }

        if (!Storage::disk('local')->exists('facturaelectronica/' . $config->id . '/gpsm_' . $invoice->clave_fe . '_signed.xml')) {
            flash('Archivo no encontrado', 'danger');

            return back();
        }

        $pathToFile = storage_path('app/facturaelectronica/' . $config->id . '/gpsm_' . $invoice->clave_fe . '_signed.xml');

        return response()->download($pathToFile);
    }

    public function recepcionHacienda($id)
    {
        $invoice = $this->findById($id);
        $office = $invoice->clinic;

        if ($office && str_slug($office->type, '-') == 'clinica-privada') {
            $config = $office->configFactura->first();

        } else {
            $config = $invoice->medic->configFactura->first();

        }
      
        $respHacienda = $this->feRepo->recepcion($config, $invoice->clave_fe);

        if (isset($respHacienda->{'ind-estado'})) {
            $invoice->status_fe = $respHacienda->{'ind-estado'};

            if (isset($respHacienda->{'respuesta-xml'})) {
                $invoice->resp_hacienda = json_encode($this->feRepo->decodeRespuestaXML($respHacienda->{'respuesta-xml'}));
            }

            $invoice->save();
        }

        return $invoice;
    }

    public function balance($medic_id)
    {
        $invoices = $this->model->where('user_id', $medic_id)->where('status', 1)->whereDate('created_at', Carbon::now()->toDateString());
        $totalInvoices = $invoices->sum('total');
        $countInvoices = $invoices->count();

        if ($countInvoices == 0) {
            flash('No hay Facturas nuevas para ejecutar un cierre', 'error');

            return Redirect()->back();
        }

        $balance = Balance::create([
            'user_id' => $medic_id,
            'invoices' => $countInvoices,
            'total' => $totalInvoices
            ]);
    }

    /**
     * save a appointment
     * @param $data
     */
    public function notaCreditoDebito($data, $invoice_id)
    {
        $invoice = $this->findById($invoice_id);
        $office = $invoice->clinic;
        $user = $invoice->medic;

        $notaDC = $this->model;

        if ($office && str_slug($office->type, '-') == 'clinica-privada') {
            $notaDC->consecutivo = $this->crearConsecutivoClinicaPrivada($office, $data['type']);
            $notaDC->fe = $office->fe;
        } else {
            $notaDC->consecutivo = $this->crearConsecutivoIndependiente($user, $data['type']);
            $notaDC->fe = $user->fe;
        }

        $notaDC->appointment_id = $invoice->appointment_id;
        $notaDC->office_id = $invoice->office_id;
        $notaDC->patient_id = $invoice->patient_id;
        $notaDC->bill_to = $invoice->bill_to;
        $notaDC->office_type = $invoice->office_type;
        $notaDC->tipo_documento = $data['type'];

        $notaDC->status = 1;

        $notaDC = $user->invoices()->save($notaDC);

        $totalInvoice = 0;
        foreach ($data['services'] as $service) {
            $line = new InvoiceLine;
            $line->name = $service['name'];
            $line->amount = $service['amount'];
            $line->quantity = 1;
            $line->total_line = $line->quantity * $line->amount;

            $totalInvoice += $line->total_line;

            $notaDC->lines()->save($line);
        }

        $notaDC->subtotal = $totalInvoice;
        $notaDC->total = $totalInvoice;
        $notaDC->client_name = $invoice->client_name;
        $notaDC->client_email = $invoice->client_email;
        $notaDC->medio_pago = $invoice->medio_pago;
        $notaDC->condicion_venta = $invoice->condicion_venta;

        foreach ($data['referencias'] as $ref) {
            $documento_ref = new DocumentoReferencia;
            $documento_ref->tipo_documento = $ref['tipo_documento'];
            $documento_ref->numero_documento = $ref['numero_documento'];
            $documento_ref->fecha_emision = $ref['fecha_emision'];
            $documento_ref->codigo_referencia = $ref['codigo_referencia'];
            $documento_ref->razon = $ref['razon'];

            $notaDC->documentosReferencia()->save($documento_ref);
        }

        if ($invoice->fe) {
            $result = $this->sendToHacienda($notaDC);

            if (!$result) {
                $notaDC->sent_to_hacienda = 1;
            }
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

        $invoices = $this->model->where('user_id', $id);

        if (!$search) {
            return $invoices->with('user', 'appointment')->orderBy('invoices.' . $order, $dir)->paginate($limit);
        }

        if (isset($search['q']) && trim($search['q'] != '')) {
            $invoices = $invoices->Search($search['q']);
        }

        if (isset($search['date']) && $search['date'] != '') {
            $appointments = $invoices->whereDate('created_at', $search['date']);
        }

        if (isset($search['order']) && $search['order'] != '') {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != '') {
            $dir = $search['dir'];
        }

        return $appointments->with('user', 'appointment')->orderBy('invoices.' . $order, $dir)->paginate($limit);
    }

    private function prepareData($data)
    {
        return $data;
    }
}
