<?php

namespace App\Repositories;

use App\Invoice;
use App\User;
use App\InvoiceLine;
use App\Balance;
use Illuminate\Support\Facades\Storage;
use App\Office;
use App\FacturaElectronica\Factura;
use App\FacturaElectronica\NotaDebito;
use App\FacturaElectronica\NotaCredito;


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

    public function crearConsecutivo($userId, $officeId, $tipoDocumento)
    {
        $consecutivo_inicio = 1;

        
        $consecutivo = Invoice::where('user_id', $userId)->where('office_id', $officeId)->where('tipo_documento', $tipoDocumento)->max('consecutivo');
       

        return ($consecutivo) ? $consecutivo += 1 : $consecutivo_inicio;
    }

    public function crearConsecutivoHacienda($obligadoTributario, $tipo_documento)
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

        $consecutivo = Invoice::where('obligado_tributario_id', $obligadoTributario->id)->where('tipo_documento', $tipo_documento)->max('consecutivo');


        return ($consecutivo) ? $consecutivo += 1 : $consecutivo_inicio;
    }

  
    /**
     * save a appointment
     * @param $data
     */
    public function store($data, $obligadoTributario = null)
    {
       
        $data = $this->prepareData($data, $obligadoTributario);

        $invoice = $this->model->create($data);

        $invoice = $invoice->saveDetails($data['services']);


        if ($obligadoTributario && !$data['send_to_assistant']) {
            $result = $this->sendToHacienda($invoice);

            if (!$result) {
                $invoice->sent_to_hacienda = 1;
                $invoice->save();
            }
        }
       
    
        return $invoice->load('clinic');
    }
    

    public function update($id, $data, $obligadoTributario)
    {
        $invoice = $this->findById($id);
        $invoice->fill($data);
        $invoice->status = 1;
        $invoice->save();
        
        if($obligadoTributario){
            $result = $this->sendToHacienda($invoice);

            if (!$result) {
                $invoice->sent_to_hacienda = 1;
                $invoice->save();
            }
        }

       

        return $invoice;
    }

    public function notaCreditoDebito($data, $invoiceId)
    {
        $originalInvoice = $this->findById($invoiceId);
        $obligadoTributario = $originalInvoice->obligadoTributario;
        $tipoDocumento = $data['type'];

        $notaDC = $this->model->create([
            "user_id" => $originalInvoice->user_id,
            "consecutivo" => $obligadoTributario ? $this->crearConsecutivoHacienda($obligadoTributario, $tipoDocumento) : $this->crearConsecutivo(auth()->id(), $originalInvoice->office_id, $tipoDocumento),
            "office_id" => $originalInvoice->office_id,
            "obligado_tributario_id" => $obligadoTributario ? $obligadoTributario->id : 0,
            "fe" => $originalInvoice->fe,
            "client_name" => $originalInvoice->client_name,
            "client_email" => $originalInvoice->client_email,
            "medio_pago" => $originalInvoice->medio_pago,
            "condicion_venta" => $originalInvoice->condicion_venta,
            "tipo_documento" => $tipoDocumento,
            "status" => 1,

        ]);

        $notaDC = $notaDC->saveDetails($data['services']);
        $notaDC = $notaDC->saveReferencias($data['referencias']);


        if ($obligadoTributario) {
            $result = $this->sendToHacienda($notaDC);

            if (!$result) {
                $notaDC->sent_to_hacienda = 1;
                $notaDC->save();
            }
        }
       

        return $notaDC->load('clinic');
    }

    public function sendToHacienda($invoice)
    {
        $invoice = $this->findById($invoice->id);
        $obligadoTributario = $invoice->obligadoTributario;


        if ($invoice->created_xml && Storage::disk('local')->exists('facturaelectronica/' . $obligadoTributario->id . '/gpsm_' . $invoice->clave_fe . '_signed.xml')) {
            $invoiceXML = Storage::get('facturaelectronica/' . $obligadoTributario->id . '/gpsm_' . $invoice->clave_fe . '.xml');
            $invoiceXML = new \SimpleXMLElement($invoiceXML);

            $situacionComprobante = ($invoice->sent_to_hacienda) ? '1' : '3';

            $invoiceXML->Clave = substr_replace((string)$invoiceXML->Clave, $situacionComprobante, 41, 1); // cambiar la situacion del comprobante 1- normal 2- contigencia 3 -no internet
            Storage::put('facturaelectronica/' . $obligadoTributario->id . '/gpsm_' . $invoice->clave_fe . '.xml', $invoiceXML->asXML());

            $signedinvoiceXML = $this->feRepo->signXML($obligadoTributario, $invoice);

            $invoice->clave_fe = (string)$invoiceXML->Clave;
            $invoice->save();

        } else {
            $signedinvoiceXML = $this->createFacturaXML($obligadoTributario, $invoice);

            if ($signedinvoiceXML) {
                $invoice->created_xml = 1;

                $invoice->save();
            }
        }

        return $this->feRepo->sendHacienda($obligadoTributario, $signedinvoiceXML);
    }

    public function createFacturaXML($obligadoTributario, $invoice)
    {


        $numeroCedulaEmisor = $obligadoTributario->identificacion;

        $miNumeroConsecutivo = $invoice->consecutivo;

        if ($invoice->tipo_documento == '01') {
            $fe = new Factura($numeroCedulaEmisor, null, $miNumeroConsecutivo);
        }
        if ($invoice->tipo_documento == '02') {
            $fe = new NotaDebito($numeroCedulaEmisor, null, $miNumeroConsecutivo);
        }
        if ($invoice->tipo_documento == '03') {
            $fe = new NotaCredito($numeroCedulaEmisor, null, $miNumeroConsecutivo);
        }

        $objFac = $fe->getClave();

        $invoice->clave_fe = $objFac->clave;
        $invoice->consecutivo_hacienda = $objFac->consecutivoHacienda;

        $invoice->save();

        $invoiceXML = $fe->generateXML($obligadoTributario, $invoice);

        $signedinvoiceXML = $this->feRepo->signXML($obligadoTributario, $invoice); //true por que es de prueba

        \Log::info('results of invoiceXML: ' . json_encode($signedinvoiceXML));

        return $signedinvoiceXML;
    }

    public function print($id)
    {
        $invoice = $this->findById($id);
        $invoice->load('lines');
        $invoice->load('user');
        $invoice->load('clinic');


        $config = $invoice->obligadoTributario;


        $respHacienda = null;

        if ($config && !$invoice->status_fe) {
            $respHacienda = $this->feRepo->recepcion($config, $invoice->clave_fe);

            if (isset($respHacienda->{'ind-estado'})) {
                $invoice->status_fe = $respHacienda->{'ind-estado'};

                // if (isset($respHacienda->{'respuesta-xml'})) {
                //     $factura->resp_hacienda = json_encode($this->feRepo->decodeRespuestaXML($respHacienda->{'respuesta-xml'}));
                // }

                $invoice->save();
            }
        }

        return $invoice;
    }

    public function xml($id)
    {
        $invoice = $this->findById($id);

        $config = $invoice->obligadoTributario;


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

        $config = $invoice->obligadoTributario;

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

    private function prepareData($data, $obligadoTributario = null)
    {
        $data['user_id'] = auth()->id();
        $data['consecutivo'] = $obligadoTributario ? $this->crearConsecutivoHacienda($obligadoTributario, '01') : $this->crearConsecutivo(auth()->id(), $data['office_id'], '01');
       
        if($obligadoTributario){
            $data['obligado_tributario_id'] = $obligadoTributario->id;
            $data['fe'] = 1;
        }
        
        return $data;
    }
    
    
}
