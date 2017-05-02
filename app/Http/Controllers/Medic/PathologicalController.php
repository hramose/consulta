<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Pathological;
use Illuminate\Http\Request;

class PathologicalController extends Controller
{
    function __construct()
    {
    	 $this->middleware('auth');
    	 
    }

    public function store()
    {
        $data = request()->all();

        $data['user_id'] = auth()->id();
       
        $pathological = Pathological::create($data);
        $pathological->load('user');
     
        return $pathological;
     
    }
    public function destroy($id)
    {
       
        $pathological = Pathological::findOrFail($id)->delete($id);

     
        return '';
     
    }
}
