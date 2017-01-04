<?php

namespace App\Http\Controllers;

use App\PhysicalExam;
use Illuminate\Http\Request;

class PhysicalExamController extends Controller
{
    function __construct($foo = null)
    {
    	$this->middleware('auth');
    }
    public function update($id)
    {
       
        $physicalExam = PhysicalExam::findOrFail($id);

        $physicalExam->fill(request('data'));
        $physicalExam->save();
        
       return '';
    }
}
