<?php

namespace App\Http\Controllers\Medic;

use App\Heredo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HeredoController extends Controller
{
    function __construct()
    {
    	 $this->middleware('auth');
    	 
    }

    public function store()
    {
        $data = request()->all();

        $data['user_id'] = auth()->id();
       
        $heredo = Heredo::create($data);
        $heredo->load('user');
     
        return $heredo;
     
    }
    public function destroy($id)
    {
       
        $heredo = Heredo::findOrFail($id)->delete($id);

     
        return '';
     
    }
}
