<?php

namespace App\Http\Controllers\Api\Medic;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Mail\NewOffice;
use App\Mail\NewRequestOffice;
use App\Office;
use App\Repositories\OfficeRepository;
use App\RequestOffice;
use App\User;
use Illuminate\Http\Request;

class OfficeController extends ApiController
{
    
    function __construct(OfficeRepository $officeRepo)
    {
    	
        $this->middleware('auth');
    	$this->officeRepo = $officeRepo;

        $this->administrators = User::whereHas('roles', function ($query){
                        $query->where('name',  'administrador');
                    })->get();

    }

     public function index()
    {
        $user = request()->user();
        $search = request()->all();
        $search['active'] = 1;
        
        $offices = $this->officeRepo->findAllByDoctor($user,$search);

        return $offices;
        
    }

     /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     * @internal param int $id
     */
     public function show($id)
     {
         $clinic = Office::find($id);
         $clinic['medics'] = $clinic->doctors();
         if(! $clinic)
         {
             return $this->respondNotFound('clinic does not exist');
 
         }
         return $this->respond([
            'data' => $clinic//$this->productTransformer->transform($product)
         ]);
     }

     /**
     * Actualizar Paciente
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
       
        if(isset($data['notification_date']) && $data['notification_date'] != '0000-00-00 00:00:00' && $data['notification_date'] != '')
        {
            $data['notification'] = 1;
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

        /*$medic = request()->user();

        try {
                        
            \Mail::to($this->administrators)->send(new NewOffice($office,$medic));
            
        }catch (\Swift_TransportException $e)  //Swift_RfcComplianceException
        {
            \Log::error($e->getMessage());
        }*/

        

       

        return $office;
        


    }

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
       
        if(isset($data['notification_date']) && $data['notification_date'] != '0000-00-00 00:00:00' && $data['notification_date'] != '')
        {
            $data['notification'] = 1;
        }


       

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

        return $office;
    
        
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

        return $this->respondDeleted('Consultorio Eliminado Correctamente');

    }

    
   
   

}
