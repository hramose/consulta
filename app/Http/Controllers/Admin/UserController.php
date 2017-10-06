<?php namespace App\Http\Controllers\Admin;



use App\Configuration;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    function __construct(UserRepository $userRepo)
    {
    	$this->middleware('auth');
    	$this->userRepo = $userRepo;
    }

    

    public function index()
    {
        $search['q'] = request('q');
        $search['role'] = request('role');
        $roles = Role::all();
      
        $users = $this->userRepo->findAll($search);
        


        return view('admin.users.index',compact('users','search', 'roles'));
    }

    public function medicRequests()
    {
        $search['q'] = request('q');
        $search['role'] = 'medico';
        $search['active'] = '0';
      
       
        $users = $this->userRepo->findAll($search);
        


        return view('admin.users.medic-requests',compact('users','search'));
    }
    public function adminClinicRequests()
    {
        $search['q'] = request('q');
        $search['role'] = 'clinica';
        $search['active'] = '0';
      
        $users = $this->userRepo->findAll($search);
        


        return view('admin.users.adminclinic-requests',compact('users','search'));
    }
    
    /**
     * Actualizar informacion basica del medico
     */
    public function updateConfig()
    {  
        $data = request()->all();
        $config = Configuration::first();
        $config->fill($data);
        $config->save();

        Session::put('amount_specialist', $config->amount_specialist);
        Session::put('amount_general', $config->amount_general);
    	
        
    	return back();

    }

     /**
     * Active a user.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function active($id)
    {
        $this->userRepo->update_active($id, 1);

        return back();
    }

    /**
     * Inactive a user.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function inactive($id)
    {
        $this->userRepo->update_active($id, 0);

        return back();
    }

    /**
     * Trial a user.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function trial($id)
    {
        $this->userRepo->update_trial($id, 1);

        return back();
    }

    /**
     * No Trial a user.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function notrial($id)
    {
        $this->userRepo->update_trial($id, 0);

        return back();
    }

     /**
     * Eliminar consulta(cita)
     */
    public function destroy($id)
    {

        $user = $this->userRepo->delete($id);

        flash('Usuario Eliminado','success');

        return back();

    }
    
}
