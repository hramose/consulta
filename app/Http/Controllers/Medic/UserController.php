<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\PatientRepository;
use App\Role;
use App\Setting;
use App\Speciality;
use App\User;
use App\Mail\NewPatient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\PatientRequest;
use App\Income;

class UserController extends Controller
{
    public function __construct(UserRepository $userRepo, PatientRepository $patientRepo)
    {
        $this->middleware('auth');
        $this->userRepo = $userRepo;
        $this->patientRepo = $patientRepo;
    }

    /**
     * Mostrar vista de editar informacion basica del medico
     */
    public function edit()
    {
        $tab = request('tab');
        $user = auth()->user();
        $assistants = auth()->user()->assistants()->with('clinicsAssistants')->get();
        
        $specialities = Speciality::all();

        $incomes = Income::where('user_id', auth()->id())->where(function ($query) {
            $query->where('type', 'M') // por cita atendida
                ->orWhere('type', 'MS'); // por subscripcion de paquete
        })->where('paid', 1)->get();

        $configFactura = $user->configFactura->first();
       // dd(($configFactura) ? ($configFactura->provincia == '1' ? 'selected' : '') : '');

        return view('medic.account', compact('user', 'specialities', 'assistants', 'tab', 'incomes', 'configFactura'));
    }

    /**
     * Actualizar informacion basica del medico
     */
    public function update()
    {
        $this->validate(request(), [
                'name' => 'required',
                'phone' => ['required', Rule::unique('users')->ignore(auth()->id())],
                'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->id())],
                'medic_code' => 'required'
            ]);

        $user = $this->userRepo->update(auth()->id(), request()->all());

        flash('Cuenta Actualizada', 'success');

        return Redirect('/medic/account/edit');
    }

    public function updateSettings()
    {
        $settings = Setting::where('user_id', auth()->id())->first();
        if ($settings) {
            $settings->fill(request()->all());
            $settings->save();
        } else {
            $settings = auth()->user()->settings()->create(request()->all());
        }

        return $settings;
    }

    /**
     * Actualizar informacion basica del medico
     */
    public function addAssistant()
    {
        $this->validate(request(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'office_id' => 'required',
            ]);

        $data = request()->all();

        $data['role'] = Role::whereName('asistente')->first();
        $data['provider'] = 'email';
        $data['provider_id'] = $data['email'];

        $user = $this->userRepo->store($data);

        /*$dd = \DB::table('assistants_offices')->insert(
                ['assistant_id' => $user->id, 'office_id' => $data['office_id']]
            );*/
        $user->clinicsAssistants()->sync([$data['office_id']]);

        auth()->user()->addAssistant($user);

        //flash('Asistente Registrado','success');

        return $user->load('clinicsAssistants');//Redirect('/medic/account/edit?tab=assistant');
    }

    /**
     * Actualizar informacion basica del medico
     */
    public function updateAssistant($id)
    {
        $this->validate(request(), [
                'name' => 'required',
                'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
                'office_id' => 'required',
            ]);

        $data = request()->all();

        $assistant = $this->userRepo->update($id, $data);

        $assistant = $assistant->clinicsAssistants()->sync([$data['office_id']]);

        return $assistant;
    }

    /**
    * Actualizar informacion basica del medico
    */
    public function deleteAssistant($id)
    {
        $assistant = User::find($id);

        auth()->user()->removeAssistant($assistant);

        $assistant->delete();

        return 'ok';
    }

    /**
     * Actualizar informacion basica del medico
     */
    public function getAssistants()
    {
        return auth()->user()->assistants()->with('clinicsAssistants')->get();
    }

    /**
     * Actualizar informacion basica del medico
     */
    public function getConsultoriosIndependientes()
    {
        return auth()->user()->offices()->where('type', 'Consultorio Independiente')->get();
    }

    /**
     * Guardar avatar del medico
     */
    public function avatars()
    {
        $mimes = ['jpg', 'jpeg', 'bmp', 'png'];
        $fileUploaded = 'error';

        if (request()->file('photo')) {
            $file = request()->file('photo');
            $ext = $file->guessClientExtension();

            if (in_array($ext, $mimes)) {
                $fileUploaded = $file->storeAs('avatars/' . auth()->id(), 'avatar.jpg', 'public');
            }
        }

        return $fileUploaded;
    }

    public function storePatient(PatientRequest $request)
    {
        $data = $request->all();

        $patient = $this->patientRepo->store($request->all());

        //validamos que en users no hay email que va a registrase como paciente
        if (isset($data['email']) && $data['email']) {
            $this->validate(request(), [
                'phone' => 'required|unique:users',
                'email' => 'required|email|max:255|unique:users'
            ]);

            $data['password'] = (isset($data['password']) && $data['password']) ? $data['password'] : $data['phone'];
            $data['name'] = $data['first_name'];
            $data['provider'] = 'email';
            $data['provider_id'] = $data['email'];
            $data['role'] = Role::whereName('paciente')->first();
            $data['api_token'] = str_random(50);

            $user = $this->userRepo->store($data);
            $user_patient = $user->patients()->save($patient);

            try {
                \Mail::to($user)->send(new NewPatient($user));
            } catch (\Swift_TransportException $e) {  //Swift_RfcComplianceException
                \Log::error($e->getMessage());
            }
        } else {
            $this->validate(request(), [
                'phone' => 'required|unique:users',
            ]);

            $data['password'] = (isset($data['password']) && $data['password']) ? $data['password'] : $data['phone'];
            $data['name'] = $data['first_name'];
            $data['provider'] = 'phone';
            $data['provider_id'] = $data['phone'];
            $data['role'] = Role::whereName('paciente')->first();
            $data['api_token'] = str_random(50);

            $user = $this->userRepo->store($data);
            $user_patient = $user->patients()->save($patient);
        }

        return $patient;
    }

    /**
     * Guardar paciente
     */
    public function addConfigFactura($user_id)
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
            //'certificado' => 'required',
        ]);

        $user = $this->userRepo->findById($user_id);

        $config = $user->configFactura()->create(request()->all());

        $mimes = ['p12'];
        $fileUploaded = 'error';

        if (request()->file('certificado')) {
            $file = request()->file('certificado');

            $ext = $file->guessClientExtension();

            if (in_array($ext, $mimes)) {
                $fileUploaded = $file->storeAs('facturaelectronica/' . $config->id, 'cert.' . $ext, 'local');
            }
        }

        if (request()->file('certificado_test')) {
            $file = request()->file('certificado_test');

            $ext = $file->guessClientExtension();

            if (in_array($ext, $mimes)) {
                $fileUploaded = $file->storeAs('facturaelectronica/' . $config->id, 'test.' . $ext, 'local');
            }
        }

        flash('Configuracion de factura electronica Creada', 'success');

        return Redirect('medic/account/edit?tab=fe');
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
        $config = $user->configFactura->first();
        $config->fill(request()->all());
        $config->save();

        $mimes = ['p12'];
        $fileUploaded = 'error';

        if (request()->file('certificado')) {
            $file = request()->file('certificado');

            $ext = $file->guessClientExtension();

            if (in_array($ext, $mimes)) {
                $fileUploaded = $file->storeAs('facturaelectronica/' . $config->id, 'cert.' . $ext, 'local');
            }
        }

        if (request()->file('certificado_test')) {
            $file = request()->file('certificado_test');

            $ext = $file->guessClientExtension();

            if (in_array($ext, $mimes)) {
                $fileUploaded = $file->storeAs('facturaelectronica/' . $config->id, 'test.' . $ext, 'local');
            }
        }

        flash('Configuracion de factura electronica Actualizada', 'success');

        return Redirect('medic/account/edit?tab=fe');
    }

    /**
     * Eliminar consulta(cita)
     */
    public function deleteConfigFactura($user_id)
    {
        $user = $this->userRepo->findById($user_id);
        $config = $user->configFactura->first();
       

        if (Storage::disk('local')->exists('facturaelectronica/' . $config->id . '/cert.p12')) {
            Storage::deleteDirectory('facturaelectronica/' . $config->id . '/');
        }

        $config->delete();

        flash('Configuracion Eliminada', 'success');

        return back();
    }
}
