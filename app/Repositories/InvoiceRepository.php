<?php

namespace App\Repositories;

use App\Invoice;
use App\User;
use App\InvoiceLine;
use App\FacturaElectronica\Factura;

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
        $this->feRepo = new FacturaElectronicaRepository('test');
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
        $invoice->bill_to = $data['bill_to'];
        $invoice->office_type = $data['office_type'];

        if ($data['office_type'] == 'Consultorio Independiente') {
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
        }

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

        $invoice->save();

        if ($user->fe) {
            $this->sendToHacienda($invoice);
        }

        return $invoice->load('clinic');
    }

    public function sendToHacienda($invoice)
    {
        $user = $invoice->medic;//$this->userRepo->findById($invoice->user_id);

        $emisor = $user->configFactura;

        $numeroCedulaEmisor = $user->configFactura->identificacion;

        $miNumeroConsecutivo = $invoice->consecutivo;

        $factura = new Factura($numeroCedulaEmisor, null, $miNumeroConsecutivo);

        $fac = $factura->getClave();

        $invoice->clave_fe = $fac->clave;
        $invoice->save();

        $invoiceXML = $factura->generateXML($user, $invoice);

        $signedinvoiceXML = $factura->signXML($user, true); //true por que es de prueba

        \Log::info('results of invoiceXML: ' . json_encode($signedinvoiceXML));

        return $this->feRepo->sendHacienda($user, $signedinvoiceXML, $fac);
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
