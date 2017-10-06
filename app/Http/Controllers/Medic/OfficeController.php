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

class OfficeController extends Controller
{
    
    function __construct(OfficeRepository $officeRepo)
    {
    	
        $this->middleware('auth');
    	$this->officeRepo = $officeRepo;

        $this->administrators = User::whereHas('roles', function ($query){
                        $query->where('name',  'administrador');
                    })->get();

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
                'file' => 'mimes:jpeg,bmp,png',         
        ]);

        $data = request()->all();
       
         /*if($data['notification_date'])
         {
             $data['notification'] = 1;
         }*/

         if(isset($data['id']) && $data['id']){ // update

            $office = $this->officeRepo->update($data['id'], $data);

            $mimes = ['jpg','jpeg','bmp','png'];
            $fileUploaded = "error";
           
            if(request()->file('file'))
            {
            
                $file = request()->file('file');
    
                $ext = $file->guessClientExtension();
    
                if(in_array($ext, $mimes))
                    $fileUploaded = $file->storeAs("offices/". $office->id, "photo.jpg",'public');
            }

            return $office;
    
         }
        
        if($data['type'] == 'Consultorio Independiente') $data['active'] = 1;

        $office = $this->officeRepo->store($data);

        $mimes = ['jpg','jpeg','bmp','png'];
        $fileUploaded = "error";
       
        if(request()->file('file'))
        {
        
            $file = request()->file('file');

            $ext = $file->guessClientExtension();

            if(in_array($ext, $mimes))
                $fileUploaded = $file->storeAs("offices/". $office->id, "photo.jpg",'public');
        }

        //return $fileUploaded;


        if($office->type == 'Consultorio Independiente') {
         
            if(!auth()->user()->verifyOffice($office->id))
            {
                 $office = auth()->user()->verifiedOffices()->save($office);
            }

            return $office;
        }

        $medic = auth()->user();

        try {
                        
            \Mail::to($this->administrators)->send(new NewOffice($office,$medic));
            
        }catch (\Swift_TransportException $e)  //Swift_RfcComplianceException
        {
            \Log::error($e->getMessage());
        }

        

       

        return $office;

    }

    /**
     * guardar datos de consultorio
     */
     public function requestOffice()
     {
        
         $this->validate(request(),[
                 'name' => 'required',
                   
         ]);
         
         $requestOffice = auth()->user()->requestOffices()->create(request()->all());

         try {
            
            \Mail::to($this->administrators)->send(new NewRequestOffice($requestOffice));

        }catch (\Swift_TransportException $e)  //Swift_RfcComplianceException
        {
            \Log::error($e->getMessage());
        }


 
         return $requestOffice;
 
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
        
        try {
                        
            \Mail::to($office->administrators())->send(new NewOffice($office,$medic));
            
        }catch (\Swift_TransportException $e)  //Swift_RfcComplianceException
        {
            \Log::error($e->getMessage());
        }

    

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
                'file' => 'mimes:jpeg,bmp,png',     
        ]);
         
         $data = request()->all();

         /*if($data['notification_date'])
         {
             $data['notification'] = 1;
         }else{
            
             $data['notification'] = 0;
         }*/

        $office = $this->officeRepo->update($id, $data);
        
        $mimes = ['jpg','jpeg','bmp','png'];
        $fileUploaded = "error";
       
        if(request()->file('file'))
        {
        
            $file = request()->file('file');

            $ext = $file->guessClientExtension();

            if(in_array($ext, $mimes))
                $fileUploaded = $file->storeAs("offices/". $office->id, "photo.jpg",'public');
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

        $res = auth()->user()->offices()->detach($office->id);
        
        if($office->type == "Consultorio Independiente") $this->officeRepo->delete($id);

        return '';

    }

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getOffices()
    {
        $search = request()->all();
        $search['active'] = 1;
        
        $offices = $this->officeRepo->findAllByDoctorWithoutPagination(auth()->user(),$search['active']);

        return $offices;
        
    }
    public function getAllOffices()
    {
        $search = request()->all();
        $search['active'] = 1;
        
        $offices = $this->officeRepo->findAllWithoutPagination(auth()->id(),$search);

        return $offices;
        
    }
   

}
