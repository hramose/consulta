<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($notification) {
            if ($notification->media) {
                \Storage::disk('public')->delete($notification->media);
            }
        });
    }
}
