<?php

namespace App\Http\Controllers\Admin;


use App\ReviewApp;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
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
    public function index()
    {
        
        
        $reviews = ReviewApp::orderBy('created_at','DESC')->paginate(10);
        $avgRating = $reviews->avg('rating');

        $rating_app_cache = round($avgRating,1);


      return view('admin.reviews.index', compact('reviews','rating_app_cache'));

        
        
    }

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function delete($id)
    {
        $reviewApp = ReviewApp::find($id);
       
        $reviewApp  = $reviewApp->delete();



      return redirect()->back();

        
        
    }



   
}
