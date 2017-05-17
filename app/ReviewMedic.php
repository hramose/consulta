<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ReviewMedic extends Model
{
    protected $table = 'review_medics';
    protected $fillable = [
        'rating','comment'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function author(){

        return $this->belongsTo('App\User','author_id');
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
    public function getTimeagoAttribute()
    {
        Carbon::setLocale(config('app.locale'));
        $date = Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans();
        return $date;
    }
    // this function takes in user ID, comment and the rating and attaches the review to the user by its ID, then the average rating for the user is recalculated
    public function storeReviewForUser($userId, $authorId, $comment, $rating)
    {
        $user = User::find($userId);

        // this will be added when we add user's login functionality
        //$this->user_id = Auth::user()->id;
        $this->author_id = $authorId;
        $this->comment = $comment;
        $this->rating = $rating;
        $user->reviewsMedic()->save($this);

        // recalculate ratings for the specified product
        $user->recalculateRatingMedic();
    }
}
