<?php

namespace App\Http\Controllers;

use App\Http\Requests\PollRequest;
use App\ReviewMedic;
use App\ReviewService;
use App\User;
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
        $this->middleware('auth')->except('sendPolls');
       
    }

    public function show($medic_id)
    {
    	//$poll = Poll::find($id)->with('questions.answers')->first();
       
        //if(!$poll) return redirect('/');
        
        //if($poll->user_id != auth()->id()) return redirect('/');

       // if($poll->completed) return redirect('/');
    

    	return view('polls.show')->with(compact('medic_id'));
    }

    public function store($user_id, PollRequest $request)
    {
     

        $user = User::find($user_id);

        if($user){
            $reviewService = New ReviewService;
            $reviewService->storeReviewForUser($user->id, auth()->id(), request('comment1'), request('rating'));

            $reviewMedic = New ReviewMedic;
            $reviewMedic->storeReviewForUser($user->id, auth()->id(), request('comment2'), request('rating2'));
        }

        return Redirect('/');

    }

    /**
     * Actualizar Poll
     */
   /* public function update($id, PollRequest $request)
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

    }*/

    public function sendPolls()
    {
    
        $exitCode = \Artisan::call('consulta:sendPolls');

        return $exitCode;

    }
}
