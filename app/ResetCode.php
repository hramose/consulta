<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Mail\ResetCodeEmail;

class ResetCode extends Model
{
   
    protected $fillable =["user_id",'code','phone'];

    public $timestamps = false;

    public function setCreatedAtAttribute($value) { 

        $this->attributes['created_at'] = \Carbon\Carbon::now(); 
    }

   public static function generateFor(User $user)
   {

       return static::create([
           "user_id" => $user->id,
           "phone" => $user->phone,
           "code" => str_random(4)
       ]);
   }

   public function send()
   {
      if ($this->user->email) {
          try {
              \Mail::to($this->user->email)->send(new ResetCodeEmail($this->code));
          } catch (\Swift_TransportException $e) {  //Swift_RfcComplianceException
              Log::error($e->getMessage());
          }
      }

   }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
