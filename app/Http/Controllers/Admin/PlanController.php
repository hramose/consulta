<?php namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;
use App\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth');
    	
    }

    

    public function index()
    {
        $search['q'] = request('q');
       

      
        $plans = Plan::search($search['q'])->paginate(10);
        


        return view('admin.plans.index',compact('plans','search'));
    }

    /**
     * Mostrar vista crear paciente
     */
    public function create()
    {
        
        return view('admin.plans.create');

    }

    /**
     * Guardar paciente
     */
    public function store()
    {
        //validamos que en users no hay email que va a registrase como paciente
        $this->validate(request(),[
                'title' => 'required|max:255',
                'cost' => 'required',
                'quantity' => 'required|numeric',
                
            ]);


        $data = request()->all();
       
        
        $plan = Plan::create($data);

        flash('Plan Creado','success');

        return Redirect('/admin/plans');

    }
    /**
     * Mostrar vista editar paciente
     */
    public function edit($id)
    {
       

        $plan = Plan::find($id);

       
        
        return view('admin.plans.edit', compact('plan'));

    }

    /**
     * Actualizar Paciente
     */
    public function update($id)
    {
        $this->validate(request(),[
                'title' => 'required|max:255',
                'cost' => 'required',
                'quantity' => 'required|numeric',
        ]);
        $plan = Plan::find($id);
        $plan->fill(request()->all());
        $plan->save();   
        
        flash('Plan Actualizado','success');

        return Redirect('/admin/plans');

    }

   
     /**
     * Eliminar consulta(cita)
     */
    public function destroy($id)
    {
        $plan = Plan::find($id);

        $plan->delete();

        flash('Plan Eliminado','success');

        return back();

    }
    
}
