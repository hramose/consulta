<?php

namespace App\Http\Controllers;

use App\Http\Requests\PollRequest;
use App\Poll;
use Illuminate\Http\Request;

class PollController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
       
    }

    public function show($id)
    {
    	$poll = Poll::find($id);
        
        if(!$poll) return redirect('/');
        
        if($poll->user_id != auth()->id()) return redirect('/');

        if($poll->completed) return redirect('/');



    	return view('polls.show')->with(compact('poll'));
    }

    /**
     * Actualizar Poll
     */
    public function update($id, PollRequest $request)
    {
        $poll = Poll::find($id);
        $poll->medical_care = request('medical_care');
        $poll->treatment = request('treatment');
        $poll->satisfaction = request('satisfaction');
        $poll->completed = 1;
        $poll->save();
        
        flash('Encuesta Enviada! Gracias por evaluación','success');
        
        return redirect('/');

    }
}
