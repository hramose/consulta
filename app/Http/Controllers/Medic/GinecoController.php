<?php

namespace App\Http\Controllers\Medic;


use App\Gineco;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GinecoController extends Controller
{
    function __construct()
    {
    	 $this->middleware('auth');
    	 
    }

    public function store()
    {
        $data = request()->all();

        $data['user_id'] = auth()->id();
       
        $gineco = Gineco::create($data);
        $gineco->load('user');
     
        return $gineco;
     
    }
    public function destroy($id)
    {
       
        $gineco = Gineco::findOrFail($id)->delete($id);

     
        return '';
     
    }
}
