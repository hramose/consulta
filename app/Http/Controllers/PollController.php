<?php

namespace App\Http\Controllers;

use App\Http\Requests\PollRequest;
use App\Poll;
use App\PollOptions;
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
    	$poll = Poll::find($id)->with('questions.answers')->first();
       
        if(!$poll) return redirect('/');
        
        //if($poll->user_id != auth()->id()) return redirect('/');

       // if($poll->completed) return redirect('/');

      

    	return view('polls.show')->with(compact('poll'));
    }

    /**
     * Actualizar Poll
     */
    public function update($id, PollRequest $request)
    {
        $poll = Poll::find($id);
        //$poll->completed = 1;
        //$poll->save();

        $pollQuestion = $poll->questions()->where('id',request('question'))->first();//PollOptions::where('poll_id',$poll_id)->where('id',request('rate'))->first();
        $pollQuestion->completed = 1;
        $pollQuestion->save();

        $answer = $pollQuestion->answers()->where('id',request('rate'))->first();
        $answer->rate += 1;
        $answer->save();
        
        
        return $poll;

    }
}
