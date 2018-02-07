<?php

namespace App\Http\Controllers\Admin;

use App\Configuration;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Role;
use App\Plan;
use App\Subscription;
use Carbon\Carbon;
use App\Mail\UserActive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Repositories\FacturaElectronicaRepository;

class UserController extends Controller
{
    public function __construct(UserRepository $userRepo, FacturaElectronicaRepository $feRepo)
    {
        $this->middleware('auth');
        $this->userRepo = $userRepo;
        $this->feRepo = $feRepo;
    }

    public function index()
    {
        $search['q'] = request('q');
        $search['role'] = request('role');
        $roles = Role::all();

        $users = $this->userRepo->findAll($search);

        return view('admin.users.index', compact('users', 'search', 'roles'));
    }

    /**
     * Mostrar vista crear paciente
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Guardar paciente
     */
    public function store()
    {
        //validamos que en users no hay email que va a registrase como paciente
        $this->validate(request(), [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6',
            ]);

        $data = request()->all();
        $data['active'] = 0; // los medicos estan inactivos por defecto para revision
        $data['provider'] = 'email';
        $data['provider_id'] = $data['email'];
        $data['api_token'] = str_random(50);

        $user = $this->userRepo->store($data);

        flash('Usuario Creado', 'success');

        return Redirect('/admin/users');
    }

    /**
     * Mostrar vista editar paciente
     */
    public function edit($id)
    {
        $user = $this->userRepo->findById($id);
        $plans = Plan::all();
        $subscription = $user->subscription;

        return view('admin.users.edit', compact('user', 'plans', 'subscription'));
    }

    /**
     * Actualizar Paciente
     */
    public function update($id)
    {
        $this->validate(request(), [
                'name' => 'required|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($id)]//'required|email|max:255|unique:patients',
        ]);

        $user = $this->userRepo->update($id, request()->all());

        flash('Usuario Actualizado', 'success');

        return Redirect('/admin/users');
    }

    public function medicRequests()
    {
        $search['q'] = request('q');
        $search['role'] = 'medico';
        $search['active'] = '0';

        $users = $this->userRepo->findAll($search);

        return view('admin.users.medic-requests', compact('users', 'search'));
    }

    public function adminClinicRequests()
    {
        $search['q'] = request('q');
        $search['role'] = 'clinica';
        $search['active'] = '0';

        $users = $this->userRepo->findAll($search);

        return view('admin.users.adminclinic-requests', compact('users', 'search'));
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
     * Guardar paciente
     */
    public function addSubscription($id)
    {
        //validamos que en users no hay email que va a registrase como paciente
        $this->validate(request(), [
                 'plan_id' => 'required',
            ]);

        $user = $this->userRepo->findById($id);
        $newPlan = Plan::find(request('plan_id'));

        $user->subscription()->create([
            'plan_id' => $newPlan->id,
            'cost' => $newPlan->cost,
            'quantity' => $newPlan->quantity,
            'ends_at' => Carbon::now()->addMonths($newPlan->quantity)
        ]);

        flash('Subscripción Creada', 'success');

        return Redirect('/admin/users/' . $id . '/edit');
    }

    /**
     * Actualizar Paciente
     */
    public function updateSubscription($id)
    {
        $this->validate(request(), [
                'plan_id' => 'required',
        ]);

        $user = $this->userRepo->findById($id);
        $newPlan = Plan::find(request('plan_id'));

        $currentSubscription = $user->subscription;
        $currentSubscription->plan_id = $newPlan->id;
        $currentSubscription->save();

        flash('Subscripción Actualizada', 'success');

        return Redirect('/admin/users/' . $id . '/edit');
    }

    /**
    * Eliminar consulta(cita)
    */
    public function deleteSubscription($id)
    {
        $user = $this->userRepo->findById($id);

        $user->subscription->delete();

        flash('Subscripción Eliminado', 'success');

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
        $user = $this->userRepo->update_active($id, 1);

        try {
            \Mail::to($user)->send(new UserActive($user));
        } catch (\Swift_TransportException $e) {  //Swift_RfcComplianceException
            \Log::error($e->getMessage());
        }

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
        $user = $this->userRepo->update_active($id, 0);

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

        flash('Usuario Eliminado', 'success');

        return back();
    }

    /**
     * Guardar paciente
     */
    public function addConfigFactura($user_id)
    {
        //validamos que en users no hay email que va a registrase como paciente
        $this->validate(request(), [
            'nombre' => 'required',
            'tipo_identificacion' => 'required',
            'identificacion' => 'required',
            'sucursal' => 'required|numeric',
            'pos' => 'required|numeric',
            'provincia' => 'required',
            'canton' => 'required',
            'distrito' => 'required',
            'otras_senas' => 'required',
            'email' => 'required|email',
            'atv_user' => 'required',
            'atv_password' => 'required',
            'pin_certificado' => 'required',
            //'certificado' => 'required',
        ]);

        $user = $this->userRepo->findById($user_id);

        $user->configFactura()->create(request()->all());

        $mimes = ['p12'];
        $fileUploaded = 'error';

        if (request()->file('certificado')) {
            $file = request()->file('certificado');

            $ext = $file->guessClientExtension();

            if (in_array($ext, $mimes)) {
                $fileUploaded = $file->storeAs('facturaelectronica/' . $user->id, 'cert.' . $ext, 'local');
            }
        }

        flash('Configuracion de factura electronica Creada', 'success');

        return Redirect('/admin/users/' . $user_id . '/edit');
    }

    /**
     * Actualizar Paciente
     */
    public function updateConfigFactura($user_id)
    {
        $this->validate(request(), [
            'nombre' => 'required',
            'tipo_identificacion' => 'required',
            'identificacion' => 'required',
            'sucursal' => 'required|numeric',
            'pos' => 'required|numeric',
            'provincia' => 'required',
            'canton' => 'required',
            'distrito' => 'required',
            'otras_senas' => 'required',
            'email' => 'required|email',
            'atv_user' => 'required',
            'atv_password' => 'required',
            'pin_certificado' => 'required',
        ]);

        $user = $this->userRepo->findById($user_id);
        $config = $user->configFactura;
        $config->fill(request()->all());
        $config->save();

        $mimes = ['p12'];
        $fileUploaded = 'error';

        if (request()->file('certificado')) {
            $file = request()->file('certificado');

            $ext = $file->guessClientExtension();

            if (in_array($ext, $mimes)) {
                $fileUploaded = $file->storeAs('facturaelectronica/' . $user->id, 'cert.' . $ext, 'local');
            }
        }

        flash('Configuracion de factura electronica Actualizada', 'success');

        return Redirect('/admin/users/' . $user_id . '/edit');
    }

    /**
     * Eliminar consulta(cita)
     */
    public function deleteConfigFactura($user_id)
    {
        $user = $this->userRepo->findById($user_id);

        $user->configFactura->delete();

        if (Storage::disk('local')->exists('facturaelectronica/' . $user_id . '/cert.p12')) {
            Storage::deleteDirectory('facturaelectronica/' . $user_id . '/');
        }

        flash('Configuracion Eliminada', 'success');

        return back();
    }

    
}
