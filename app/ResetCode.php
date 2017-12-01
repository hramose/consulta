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
      $status = [
        'status' => 1,
        'message' => 'ok'
      ];
    
      if($this->user->phone){

        $message = "Utiliza el codigo para poder cambiar la contraseña de tu usuario en GPS Medica. El Codigo es: ". $this->code;

       
        try {
            \Twilio::message('+506'.$this->user->phone, $message);
        } catch ( \Services_Twilio_RestException $e ) {
             $status['status'] = 0;
             $status['message'] = $e->getMessage();
             \Log::error($e->getMessage());
        }

      }
       
      if ($this->user->email) {
          try {
              \Mail::to($this->user->email)->send(new ResetCodeEmail($this->code));
          } catch (\Swift_TransportException $e) {  //Swift_RfcComplianceException
              \Log::error($e->getMessage());
          }
      }

      return $status;

   }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
