<?php

namespace App\Http\Controllers\Api;


use App\ReviewApp;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class ReviewController extends ApiController
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

    
    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function store(Request $request)
    {
        $this->validate($request,[
                'app' => 'required',
                'comment' => ['required'],
                'rating' => 'required',
                
            ]);

        $user = $request->user();
        

        if(!$user) return $this->respondNotFound('Error al enviar calificación');
        

        $review = New ReviewApp;
        $review->storeReview($user->id, request('comment'), request('rating'), request('app'));



        return $this->respondCreated('Calificación Enviada correctamente');

         /*$data =request()->all();
        $data['user_id'] = $user->id;
        
        $review = ReviewApp::create($data);*/

        
        
    }



   
}
