<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Mail\NewOffice;
use App\Mail\NewRequestOffice;
use App\Office;
use App\Repositories\OfficeRepository;
use App\User;
use App\RequestOffice;
use Illuminate\Http\Request;
use Validator;
use App\Events\MedicRequest;

class OfficeController extends Controller
{
    public function __construct(OfficeRepository $officeRepo)
    {
        $this->middleware('auth');
        $this->officeRepo = $officeRepo;

        $this->administrators = User::whereHas('roles', function ($query) {
            $query->where('name', 'administrador');
        })->get();
    }

    /**
     * Mostrar vista de editar consultorio de doctor
     */
    public function edit()
    {
        $user = auth()->user();

        return view('users.edit', compact('user'));
    }

    /**
     * guardar datos de consultorio
     */
    public function store()
    {
        $data = request()->all();

        $v = Validator::make($data, [
                'type' => 'required',
                'name' => 'required',
                'address' => 'required',
                'province' => 'required',
                'canton' => 'required',
                'district' => 'required',
                'phone' => 'required',
                'file' => 'mimes:jpeg,bmp,png',
        ]);

        $v->sometimes('ide', 'required', function ($input) {
            return $input->bill_to == 'C';
        });
        $v->sometimes('ide_name', 'required', function ($input) {
            return $input->bill_to == 'C';
        });

        $v->validate();

        //$data['facturar'] =  ($data['facturar'] == 'M') ? 1 : 0;

        if (isset($data['notification_date']) && $data['notification_date'] != '0000-00-00 00:00:00' && $data['notification_date'] != '') {
            $data['notification'] = 1;
        }

        if (isset($data['id']) && $data['id']) { // update
            $office = $this->officeRepo->update($data['id'], $data);

            $mimes = ['jpg', 'jpeg', 'bmp', 'png'];
            $fileUploaded = 'error';

            if (request()->file('file')) {
                $file = request()->file('file');

                $ext = $file->guessClientExtension();

                if (in_array($ext, $mimes)) {
                    $fileUploaded = $file->storeAs('offices/' . $office->id, 'photo.jpg', 'public');
                }
            }

            return $office;
        }

        if ($data['type'] == 'Consultorio Independiente') {
            $data['active'] = 1;
        }

        $office = $this->officeRepo->store($data);

        $mimes = ['jpg', 'jpeg', 'bmp', 'png'];
        $fileUploaded = 'error';

        if (request()->file('file')) {
            $file = request()->file('file');

            $ext = $file->guessClientExtension();

            if (in_array($ext, $mimes)) {
                $fileUploaded = $file->storeAs('offices/' . $office->id, 'photo.jpg', 'public');
            }
        }

        //return $fileUploaded;

        if ($office->type == 'Consultorio Independiente') {
            if (!auth()->user()->verifyOffice($office->id)) {
                $office = auth()->user()->verifiedOffices()->save($office);
            }

            if (!session()->has('office_id') || session('office_id') == '') {
                session(['office_id' => $office->id]);
            }

            return $office;
        }

        $medic = auth()->user();

        try {
            \Mail::to($this->administrators)->send(new NewOffice($office, $medic));
        } catch (\Swift_TransportException $e) {  //Swift_RfcComplianceException
            \Log::error($e->getMessage());
        }

        return $office;
    }

    /**
     * guardar datos de consultorio
     */
    public function requestOffice()
    {
        $this->validate(request(), [
                 'name' => 'required',
         ]);

        $requestOffice = auth()->user()->requestOffices()->create(request()->all());

        try {
            \Mail::to($this->administrators)->send(new NewRequestOffice($requestOffice));
        } catch (\Swift_TransportException $e) {  //Swift_RfcComplianceException
            \Log::error($e->getMessage());
        }

        return $requestOffice;
    }

    public function assignOffice($id)
    {
        $office = Office::findOrFail($id);

        if (auth()->user()->hasOffice($office->id)) {
            return '';
        }

        $office = auth()->user()->offices()->save($office);
        $medic = auth()->user();

        event(new MedicRequest($medic, $office->administrators()));

        try {
            \Mail::to($office->administrators())->send(new NewOffice($office, $medic));
        } catch (\Swift_TransportException $e) {  //Swift_RfcComplianceException
            \Log::error($e->getMessage());
        }

        return $office;
    }

    /**
     * Actualizar datos de consultorio
     */
    public function update($id)
    {
        $data = request()->all();

        $v = Validator::make($data, [
                'type' => 'required',
                'name' => 'required',
                'address' => 'required',
                'province' => 'required',
                'canton' => 'required',
                'district' => 'required',
                'phone' => 'required',
                'file' => 'mimes:jpeg,bmp,png',
        ]);

        $v->sometimes('ide', 'required', function ($input) {
            return $input->bill_to == 'C';
        });
        $v->sometimes('ide_name', 'required', function ($input) {
            return $input->bill_to == 'C';
        });

        $v->validate();

        //$data['facturar'] =  ($data['facturar'] == 'true') ? 1 : 0;
        /*if($data['notification_date'])
        {
            $data['notification'] = 1;
        }else{

            $data['notification'] = 0;
        }*/

        $office = $this->officeRepo->update($id, $data);

        $mimes = ['jpg', 'jpeg', 'bmp', 'png'];
        $fileUploaded = 'error';

        if (request()->file('file')) {
            $file = request()->file('file');

            $ext = $file->guessClientExtension();

            if (in_array($ext, $mimes)) {
                $fileUploaded = $file->storeAs('offices/' . $office->id, 'photo.jpg', 'public');
            }
        }

        //flash('Consultorio Actualizado','success');

        return $office;
    }

    public function updateOfficeNotification($id)
    {
        /*$this->validate(request(),[
                'notification_date' => 'required',
        ]);*/

        $data = request()->all();
        $data['notification_date'] = '0000-00-00 00:00:00';

        $office = $this->officeRepo->update($id, $data);

        return $office;
        //return redirect()->back();
    }

    /**
     * Actualizar datos de consultorio
     */
    public function destroy($id)
    {
        //$office = $this->officeRepo->delete($id);

        $office = Office::findOrFail($id);

        $res = auth()->user()->offices()->detach($office->id);

        if ($office->type == 'Consultorio Independiente') {
            $this->officeRepo->delete($id);
        }

        return '';
    }

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getOffices()
    {
        $search = request()->all();
        $search['active'] = 1;
        $search['dir'] = 'ASC';
        $offices = $this->officeRepo->findAllByDoctorWithoutPagination(auth()->user(), $search);

        return $offices;
    }

    public function getAllOffices()
    {
        $search = request()->all();
        $search['active'] = 1;

        $offices = $this->officeRepo->findAllWithoutPagination(auth()->id(), $search);

        return $offices;
    }
}
