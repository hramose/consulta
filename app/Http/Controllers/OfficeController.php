<?php

namespace App\Http\Controllers;

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
     * Actualizar datos de consultorio
     */
    public function update($id)
    {
       
    	$this->validate(request(),[
                'name' => 'required',   
        ]);

    	$user = $this->officeRepo->update($id, request()->all());

        flash('Consultorio Actualizado','success');

    	return Redirect('/account/edit');

    }

}
