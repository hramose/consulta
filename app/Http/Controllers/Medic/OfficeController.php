<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Repositories\OfficeRepository;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    
    function __construct(OfficeRepository $officeRepo)
    {
    	
        $this->middleware('auth');
    	$this->officeRepo = $officeRepo;

    }

    /**
     * Mostrar vista de editar consultorio de doctor
     */
    public function edit()
    {
    	
        $user = auth()->user();

    	return view('users.edit',compact('user'));

    }

    /**
     * guardar datos de consultorio
     */
    public function store()
    {
       
        $this->validate(request(),[
                'name' => 'required',
                'address' => 'required',  
                'province' => 'required',  
                'city' => 'required', 
                'phone' => 'required',      
        ]);

        $data = request()->all();
        
        $data['user_id'] = auth()->id();

        $office = $this->officeRepo->store($data);

       // flash('Consultorio Guardado','success');

        return $office;

    }
    /**
     * Actualizar datos de consultorio
     */
    public function update($id)
    {
       
    	 $this->validate(request(),[
                'name' => 'required',
                'address' => 'required',  
                'province' => 'required',  
                'city' => 'required', 
                'phone' => 'required',      
        ]);

    	$office = $this->officeRepo->update($id, request()->all());

        //flash('Consultorio Actualizado','success');

    	return $office;

    }
    /**
     * Actualizar datos de consultorio
     */
    public function destroy($id)
    {
       

        $office = $this->officeRepo->delete($id);

        //flash('Consultorio Eliminado','success');

        return '';

    }

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getOffices()
    {

        $offices = $this->officeRepo->findAllByDoctorWithoutPagination(auth()->id());

        return $offices;
        
    }

}
