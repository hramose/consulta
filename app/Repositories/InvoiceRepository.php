<?php

namespace App\Repositories;

use App\Invoice;
use App\User;
use App\InvoiceLine;
use App\Balance;
use Illuminate\Support\Facades\Storage;
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
      
    }

    public function crearConsecutivo($user, $office, $tipo_documento)
    {
        $consecutivo_inicio = 1;

        
        $consecutivo = Invoice::where('user_id', $user->id)->where('office_id', $office->id)->where('tipo_documento', $tipo_documento)->max('consecutivo');
       

        return ($consecutivo) ? $consecutivo += 1 : $consecutivo_inicio;
    }

  
    /**
     * save a appointment
     * @param $data
     */
    public function store($data)
    {
        if (!isset($data['office_id'])) {
            $data['office_id'] = session('office_id');
        }
        
 
        $office = isset($data['office_id']) ? Office::find($data['office_id']) : null;
        $user = auth()->user();

        $invoice = $this->model;

        $invoice->consecutivo = $this->crearConsecutivo($user, $office,'01');


       

        if (isset($data['pay_with'])) {
            $invoice->pay_with = $data['pay_with'];
        }
        if (isset($data['change'])) {
            $invoice->change = $data['change'];
        }
        $invoice->office_id = $data['office_id'];

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

        $invoice->save();

        return $invoice->load('clinic');
    }

    public function update($id, $data)
    {
        $invoice = $this->findById($id);
        $invoice->fill($data);
        $invoice->status = 1;


        $invoice->save();

        return $invoice;
    }


    public function print($id)
    {
        $invoice = $this->findById($id);
        $invoice->load('lines');
        $invoice->load('user');
        $invoice->load('clinic');

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

    private function prepareData($data)
    {
        return $data;
    }
}
