<?php namespace App\Repositories;



use App\Invoice;
use App\InvoiceLine;
use Carbon\Carbon;

class InvoiceRepository extends DbRepository{


    /**
     * Construct
     * @param User $model
     */
    function __construct(Invoice $model)
    {
        $this->model = $model;
        $this->limit = 10;
    }

    /**
     * save a appointment
     * @param $data
     */
    public function store($data, $user_id = null)
    {
       
        
        $invoice = $this->model;
        $invoice->appointment_id = 1;

        $invoice = auth()->user()->invoices()->save($invoice);
        



        $totalInvoice = 0;
        foreach ($data as $service) {

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
         $invoice->save();

        return $invoice;
        
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
       
        if (! count($search) > 0) return $invoices->with('user','appointment')->orderBy('invoices.'.$order , $dir)->paginate($limit);

        if (isset($search['q']) && trim($search['q'] != ""))
        {
            
            $invoices = $invoices->Search($search['q']);
        }

        if (isset($search['date']) && $search['date'] != "")
        {

            $appointments = $invoices->whereDate('created_at', $search['date']);
        }


        if (isset($search['order']) && $search['order'] != "")
        {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != "")
        {
            $dir = $search['dir'];
        }


        return $appointments->with('user','appointment')->orderBy('invoices.'.$order , $dir)->paginate($limit);

    }
    

    

    private function prepareData($data)
    {
       

        return $data;
    }


}