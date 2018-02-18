<?php

namespace App\Repositories;

use App\Invoice;
use App\User;
use App\InvoiceLine;
use App\FacturaElectronica\Factura;
use App\Balance;
use Illuminate\Support\Facades\Storage;

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

    /**
     * save a appointment
     * @param $data
     */
    public function store($data, $user_id = null)
    {
        $user = ($user_id) ? User::find($user_id) : auth()->user();

        $invoice = $this->model;
        $invoice->appointment_id = $data['appointment_id'];
        $invoice->office_id = $data['office_id'];
        $invoice->patient_id = $data['patient_id'];
        $invoice->bill_to = ($user->fe) ? 'M' : $data['bill_to'];
        $invoice->office_type = $data['office_type'];

        $consecutivo = Invoice::where('user_id', $user->id)->max('consecutivo');

        /*if ($data['office_type'] == 'Consultorio Independiente') {
            $consecutivo = Invoice::where('user_id', $user->id)->where('office_type', 'Consultorio Independiente')->max('consecutivo');
        } else {
            if ($data['bill_to'] == 'M') {
                $consecutivo = Invoice::where('user_id', $user->id)->where('office_type', 'Consultorio Independiente')->max('consecutivo');
                $last_office = Invoice::where('user_id', $user->id)->where('office_type', 'Consultorio Independiente')->orderBy('id', 'desc')->first();
                $invoice->office_id = $last_office->office_id; // se guarda el id de la office del ultimo registro de consultorios independientes
                $invoice->office_type = $last_office->office_type; // se guarda el tipo de la office del ultimo registro de consultorios independientes
            } else {
                $consecutivo = Invoice::where('user_id', $user->id)->where('office_id', $data['office_id'])->where('office_type', 'Clínica Privada')->where('bill_to', 'C')->max('consecutivo');
            }
        }*/

        if ($user->fe) {
            $consecutivo_inicio = $user->configFactura->consecutivo_inicio;
            $invoice->fe = 1;
        } else {
            $consecutivo_inicio = 1;
        }

        $invoice->consecutivo = ($consecutivo) ? $consecutivo += 1 : $consecutivo_inicio;

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
            $line->service = $service['name'];
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

        if ($user->fe && !$data['send_to_assistant']) {
            $result = $this->sendToHacienda($invoice);
            // dd($result);
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

        if ($invoice->medic->fe) {
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
        $user = $invoice->medic;//$this->userRepo->findById($invoice->user_id);

        if ($invoice->created_xml && Storage::disk('local')->exists('facturaelectronica/' . $user->id . '/invoice-' . $invoice->id . '-signed.xml')) {
            $invoiceXML = Storage::get('facturaelectronica/' . $user->id . '/invoice-' . $invoice->id . '.xml');
            $facturaXML = new \SimpleXMLElement($invoiceXML);

            $situacionComprobante = ($invoice->sent_to_hacienda) ? '1' : '3';

            $facturaXML->Clave = substr_replace((string) $facturaXML->Clave, $situacionComprobante, 41, 1); // cambiar la situacion del comprobante 1- normal 2- contigencia 3 -no internet
            Storage::put('facturaelectronica/' . $user->id . '/invoice-' . $invoice->id . '.xml', $facturaXML->asXML());

            $signedinvoiceXML = $this->feRepo->signXML($user, $invoice->id);

            $invoice->clave_fe = (string) $facturaXML->Clave;
            $invoice->save();
        } else {
            $signedinvoiceXML = $this->createFacturaXML($invoice);

            if ($signedinvoiceXML) {
                $invoice->created_xml = 1;

                $invoice->save();
            }
        }

        return $this->feRepo->sendHacienda($user, $signedinvoiceXML);
    }

    public function createFacturaXML($invoice)
    {
        $user = $invoice->medic;//$this->userRepo->findById($invoice->user_id);

        $numeroCedulaEmisor = $user->configFactura->identificacion;

        $miNumeroConsecutivo = $invoice->consecutivo;

        $factura = new Factura($numeroCedulaEmisor, null, $miNumeroConsecutivo);

        $fac = $factura->getClave();

        $invoice->clave_fe = $fac->clave;
        $invoice->consecutivo_hacienda = $fac->consecutivoHacienda;

        $invoice->save();

        $invoiceXML = $factura->generateXML($user, $invoice);

        $signedinvoiceXML = $this->feRepo->signXML($user, $invoice->id); //true por que es de prueba

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

        $respHacienda = null;

        if ($invoice->fe && !$invoice->status_fe) {
            $respHacienda = $this->feRepo->recepcion($invoice->medic, $invoice->clave_fe);

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

    public function recepcionHacienda($id)
    {
        $invoice = $this->findById($id);

        $respHacienda = $this->feRepo->recepcion($invoice->medic, $invoice->clave_fe);

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

        if (!count($search) > 0) {
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
