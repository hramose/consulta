<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Mail\ResetCodeEmail;


class ResetCode extends Model
{
   
    protected $fillable =["user_id",'code','phone'];

    public $timestamps = false;

    protected $dates = ['created_at'];

   public static function generateFor(User $user)
   {

       return static::create([
           "user_id" => $user->id,
           "phone" => $user->phone,
           "code" => rand(0, 9999)
       ]);
   }

   public function send()
   {
    
      if($this->user->phone){

        $message = "Utiliza el codigo para poder cambiar la contraseña de tu usuario en GPS Medica. El Codigo es: ". $this->code;

        \Twilio::message('+506'.$this->user->phone, $message);
        

      }
       
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
