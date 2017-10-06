<?php

namespace App\Http\Controllers\Admin;


use App\RequestOffice;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class OfficeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('authByRole:administrador');
       
  

    }

    
    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function index()
    {
       
        $search['status'] = request('status');
    
        if (isset($search['status']) && $search['status'] != "")
        {
            $requestOffices = RequestOffice::with('user')->where('status', '=', $search['status'])->orderBy('created_at','DESC')->paginate(10);
        }else{
        
            $requestOffices = RequestOffice::with('user')->orderBy('created_at','DESC')->paginate(10);
        }


      return view('admin.offices.requests.index', compact('requestOffices','search'));

        
        
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
        $requestOffice = \DB::table('request_offices')
        ->where('id', $id)
        ->update(['status' => 1]); 

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
        $requestOffice = \DB::table('request_offices')
        ->where('id', $id)
        ->update(['status' => 0]); 

         return back();
     }

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function delete($id)
    {
        $requestOffice = RequestOffice::find($id);
       
        $requestOffice  = $requestOffice->delete();



      return redirect()->back();

        
        
    }



   
}
