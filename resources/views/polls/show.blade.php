@extends('layouts.app-patient')
@section('css')
 <style> 
 .ratings {
        width: 290px !important;
      
    } 
    .ratings img{
        width: 55px;
        height: 50px;
        display: inline-block;
    }
 </style>
@endsection
@section('content')
    
    <div id="infoBox" class="alert alert-success" ></div>
  @include('layouts/partials/header-pages',['page'=>'Encuesta'])


    <section class="content">
       <div class="row">
       	 <div class="col-md-12">
       	     <!-- <poll></poll> -->
              <div class="box">
                
                <div class="box-body">
                  
                      <div id="question1" class="question">
                          <p>Satisfaccion de la consulta</p>
                            <div class="form-group">
                               
                                <div class="col-sm-10">
                                  <div id="star" class="ratings"></div>
                                  <textarea cols="30" rows="10" name="comments1" class="form-control"></textarea>
                                </div>
                            </div>
                            
                           
                         <div class="form-group">
                          <div class="col-sm-10">
                            <button type="submit" class="btn btn-info">Guardar</button>
                          </div>
                        </div>
                      </div>
                      
                    <div id="question2" class="question">
                        <p>Satisfaccion medicamento</p>
                          
                          <div class="col-sm-10">
                                <div id="star2" class="ratings"></div>
                                <textarea cols="30" rows="10" name="comments2" class="form-control"></textarea>
                          </div>
                          
                        <div class="form-group">
                        <div class="col-sm-10">
                          <button type="submit" class="btn btn-info">Guardar</button>
                        </div>
                      </div>
                  </div>
               
                  
                </div>
                
            </div>
       	     	
       	   
       		
       	 </div>
       	 
       </div>
       
         
    </section>


@endsection
@section('scripts')

 <script src="/js/plugins/jquery.raty.min.js"></script>
 <script>
    $('#star').raty({
      path: '/img/',
      iconRange: [
        { range: 1, on: 'muy-malo.png', off: 'muy-malo-off.png' },
        { range: 2, on: 'malo.png', off: 'malo-off.png' },
        { range: 3, on: 'regular.png', off: 'regular-off.png' },
        { range: 4, on: 'bueno.png', off: 'bueno-off.png' },
        { range: 5, on: 'excelente.png', off: 'excelente-off.png' }
      ]
    });
    $('#star2').raty({
      path: '/img/',
      iconRange: [
        { range: 1, on: 'muy-malo.png', off: 'muy-malo-off.png' },
        { range: 2, on: 'malo.png', off: 'malo-off.png' },
        { range: 3, on: 'regular.png', off: 'regular-off.png' },
        { range: 4, on: 'bueno.png', off: 'bueno-off.png' },
        { range: 5, on: 'excelente.png', off: 'excelente-off.png' }
      ]
    });


 </script>
@endsection
