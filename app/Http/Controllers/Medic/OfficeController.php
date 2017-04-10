<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Mail\NewOffice;
use App\Office;
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
                'type' => 'required',
                'name' => 'required',
                'address' => 'required',  
                'province' => 'required',
                'canton' => 'required', 
                'district' => 'required', 
                'phone' => 'required',      
        ]);

        $data = request()->all();

         /*if($data['notification_date'])
         {
             $data['notification'] = 1;
         }*/
        

        $office = $this->officeRepo->store($data);
        $medic = auth()->user();

        \Mail::to('alonso@avotz.com')->send(new NewOffice($office,$medic));

       // flash('Consultorio Guardado','success');

        return $office;

    }
    public function assignOffice($id)
    {
        $office = Office::findOrFail($id);

        if(auth()->user()->hasOffice($office->id))
        {
            return '';
        }


        $office = auth()->user()->offices()->save($office);
        $medic = auth()->user();
        
        \Mail::to('alonso@avotz.com')->send(new NewOffice($office,$medic));

        return $office;

       

    }
    /**
     * Actualizar datos de consultorio
     */
    public function update($id)
    {
       
    	 $this->validate(request(),[
                'type' => 'required',
                'name' => 'required',
                'address' => 'required',  
                'province' => 'required',  
                'canton' => 'required', 
                'district' => 'required',  
                'phone' => 'required',      
        ]);
         
         $data = request()->all();

         /*if($data['notification_date'])
         {
             $data['notification'] = 1;
         }else{
            
             $data['notification'] = 0;
         }*/

    	$office = $this->officeRepo->update($id, $data);

        //flash('Consultorio Actualizado','success');

    	return $office;

    }
    public function updateOfficeNotification($id)
    {
        /*$this->validate(request(),[
                'notification_date' => 'required',      
        ]);*/

        $data = request()->all();
        //$data['notification'] = 1;

        $office = $this->officeRepo->update($id,  $data);

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

        $office = auth()->user()->offices()->detach($office->id);


        return '';

    }

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getOffices()
    {
        
        $offices = $this->officeRepo->findAllByDoctorWithoutPagination(auth()->user(),request()->all());

        return $offices;
        
    }
    public function getAllOffices()
    {
        
        $offices = $this->officeRepo->findAllWithoutPagination(auth()->id(),request()->all());

        return $offices;
        
    }
   

}
