<?php namespace App\Repositories;


use App\Office;
use App\Poll;
use App\Role;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class ClinicRepository extends DbRepository{


    /**
     * Construct
     * @param User $model
     */
    function __construct(Office $model)
    {
        $this->model = $model;
        $this->limit = 10;
    }

   
   

    /**
     * Find all the users for the admin panel
     * @internal param $username
     * @param null $search
     * @return mixed
     */
    public function findAll($search = null)
    {
        $order = 'distance';
        $dir = 'desc';

        if (! count($search) > 0) return $this->model->paginate($this->limit);

        if (isset($search['q']) && trim($search['q']))
        {
            $offices = $this->model->Active(1)->Search($search['q']);
        } else
        {
            $offices = $this->model->Active(1);
        }
       
        if (isset($search['lat']) && $search['lat'] != "" && isset($search['lon']) && $search['lon'] != "")
        {
            
            $offices = $offices->NearLatLng($search['lat'], $search['lon'], 25, 'K');
            $offices = $offices->orderBy('distance','ASC');

        }


        if (isset($search['province']) && $search['province'] != "")
        {
            $offices = $offices->where('province', $search['province']);
                               
        }

         if (isset($search['canton']) && $search['canton'] != "")
        {
            $offices = $offices->where('canton', $search['canton']);
                               
        }
         if (isset($search['district']) && $search['district'] != "")
        {
            $offices = $offices->where('district', $search['district']);
                                
        }


        if (isset($search['order']) && $search['order'] != "")
        {
            $order = $search['order'];
        }
        if (isset($search['dir']) && $search['dir'] != "")
        {
            $dir = $search['dir'];
        }

        $paginator = paginate($offices->get()->all(), $this->limit);
          
        return $paginator; //$offices->paginate($this->limit);

    }


     /**
     * Get all the appointments for the admin panel
     * @param $search
     * @return mixed
     */
    public function reportsStatistics($search)
    {
        
         $order = 'created_at';
         $dir = 'desc';

        
        $appointments = $this->model;

        if (isset($search['reviewType']) && $search['reviewType'] == "Médico" && isset($search['medic']) && $search['medic'] != "")
        {
           
            $polls = Poll::where('user_id', $search['medic'])->get();

           
        }
        
        
        if (isset($search['date1']) && $search['date1'] != "")
        {
           
            
            
            $date1 = new Carbon($search['date1']);
            $date2 = (isset($search['date2']) && $search['date2'] != "") ? $search['date2'] : $search['date1'];
            $date2 = new Carbon($date2);
            
         
            $polls = $polls->where([['polls.created_at', '>=', $date1],
                    ['polls.created_at', '<=', $date2->endOfDay()]]);
            
        }
//          $mod = @mysql_query("SELECT SUM(valor) as valor FROM opciones WHERE id_encuesta = ".$id);
// while($result = @mysql_fetch_object($mod)){
         $totalAnswers = 0;
         foreach ($polls as $poll) {
             foreach ($poll->questions as $q) {
                 $totalAnswers += $q->answers()->sum('rate');
             }
             
         }
         

        /* $sql = "SELECT a.titulo as titulo, a.fecha as fecha, b.id as id, b.nombre as nombre, b.valor as valor FROM encuestas a INNER JOIN opciones b ON a.id = b.id_encuesta WHERE a.id = ".$id;
$req = @mysql_query($sql);

while($result = @mysql_fetch_object($req)){
    if($aux == 0){
            echo "<h1>".$result->titulo."</h1>";
            echo "<ul class='votacion'>";
        $aux = 1;
    }
    echo '<li><div class="fl">'.$result->nombre.'</div><div class="fr">Votos: '.$result->valor.'</div>';
    if($suma == 0){
        echo '<div class="barra cero" style="width:0%;"></div></li>';
    }else{
        echo '<div class="barra" style="width:'.($result->valor*100/$suma).'%;">'.round($result->valor*100/$suma).'%</div></li>';
    }

}*/
           
        /*$statistics = $polls->selectRaw('name, count(*) items')
                         ->groupBy('name')
                         ->orderBy('name','DESC')
                         ->get()
                         ->toArray();*/
         
      //return $statistics;
       
    }


    



   


}