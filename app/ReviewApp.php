<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewApp extends Model
{
    protected $table = 'review_apps';
    protected $fillable = [
        'user_id','app','comment'
    ];

      public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function scopeSpam($query)
    {
        return $query->where('spam', true);
    }

    public function scopeNotSpam($query)
    {
        return $query->where('spam', false);
    }
    public function scopeFilter($query, $filter)
    {
        if(!$filter) return $query;

        return $query->where('rating', $filter);
    }

    // this function takes in user ID, comment and the rating and attaches the review to the user by its ID, then the average rating for the user is recalculated
    public function storeReview($userId, $comment, $rating, $app)
    {
        $user = User::find($userId);

        // this will be added when we add user's login functionality
        //$this->user_id = Auth::user()->id;
        $this->comment = $comment;
        $this->rating = $rating;
        $this->app = $app;
        $user->reviewsApp()->save($this);

        // recalculate ratings for the specified product
        $user->recalculateRatingApp();
        
    }
}
