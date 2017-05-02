<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Nopathological;
use Illuminate\Http\Request;

class NopathologicalController extends Controller
{
    function __construct()
    {
    	 $this->middleware('auth');
    	 
    }

    public function store()
    {
        $data = request()->all();

        $data['user_id'] = auth()->id();
       
        $nopathological = Nopathological::create($data);
        $nopathological->load('user');
     
        return $nopathological;
     
    }
    public function destroy($id)
    {
       
        $nopathological = Nopathological::findOrFail($id)->delete($id);

     
        return '';
     
    }
}
