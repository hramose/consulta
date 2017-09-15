@extends('layouts.app-patient')
@section('css')

@endsection
@section('content')
    
    <div id="infoBox" class="alert alert-success" ></div>
  @include('layouts/partials/header-pages',['page'=>'Encuesta de Satisfacción'])


    <section class="content">
       <div class="row">
       	 <div class="col-md-12">
       	     <!-- <poll></poll> -->
             <form action="/medics/{{ $medic_id }}/polls" method="POST">
                   {{ csrf_field() }}
                  <div class="box">
                    
                    <div class="box-body">
                        <div class="form-horizontal">
                          
                       
                          <div id="question1" class="question ">
                              <h3>Nivel de satisfación del  servicio recibido</h3>
                                <div class="form-group">
                                    <div class="col-sm-10">
                                      <div id="star" class="ratings"></div>
                                      <div id="rating-target" class="ratings-targets"></div>
                                       @if ($errors->has('rating'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('rating') }}</strong>
                                          </span>
                                      @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                   
                                    <div class="col-sm-12">                             
                                      <textarea cols="30" rows="3" name="comment1" class="form-control" placeholder="Escribe algun comentario..."></textarea>
                                    </div>
                                </div>
                                
                               
                             
                          </div>
                          
                          <div id="question2" class="question ">
                                <h3>Nivel de satisfacción con el desempeño del médico</h3>
                                   <div class="form-group">
                                      <div class="col-sm-10">
                                        <div id="star2" class="ratings"></div>
                                        <div id="rating-target2" class="ratings-targets"></div>
                                        @if ($errors->has('rating2'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('rating2') }}</strong>
                                          </span>
                                      @endif
                                      </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-sm-12">
                                  
                                          <textarea cols="30" rows="3" name="comment2" class="form-control" placeholder="Escribe algun comentario..."></textarea>
                                    </div>
                                  </div>
                                  
                                
                          </div>
                       </div>
                   
                      
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-info">Enviar</button>
                    </div>
                    
                </div>
            </form>
       	     	
       	   
       		
       	 </div>
       	 
       </div>
       
         
    </section>


@endsection
@section('scripts')

 <script src="/js/plugins/jquery.raty.min.js"></script>
 <script>
    $('#star').raty({
      path: '/img/',
      scoreName: 'rating',
      iconRange: [
        { range: 1, on: 'muy-malo.png', off: 'muy-malo-off.png' },
        { range: 2, on: 'malo.png', off: 'malo-off.png' },
        { range: 3, on: 'regular.png', off: 'regular-off.png' },
        { range: 4, on: 'bueno.png', off: 'bueno-off.png' },
        { range: 5, on: 'excelente.png', off: 'excelente-off.png' }
      ],
      hints:['Muy Malo', 'Malo', 'regular', 'Bueno', 'Excelente'],
      mouseover : function(score, evt) {
       
        var target = $('#rating-target');

        if (score === null) {
          target.html('Boring!');
        } else if (score === undefined) {
          target.empty();
        } else {
          target.html('Puntos: ' + score);
        }
      }
    });
    $('#star2').raty({
      path: '/img/',
      scoreName:'rating2',
      iconRange: [
        { range: 1, on: 'muy-malo.png', off: 'muy-malo-off.png' },
        { range: 2, on: 'malo.png', off: 'malo-off.png' },
        { range: 3, on: 'regular.png', off: 'regular-off.png' },
        { range: 4, on: 'bueno.png', off: 'bueno-off.png' },
        { range: 5, on: 'excelente.png', off: 'excelente-off.png' }
      ],
      hints:['Muy Malo', 'Malo', 'regular', 'Bueno', 'Excelente'],
      mouseover : function(score, evt) {
       
        var target = $('#rating-target2');

        if (score === null) {
          target.html('Boring!');
        } else if (score === undefined) {
          target.empty();
        } else {
          target.html('Puntos: ' + score);
        }
      }
    });


 </script>
@endsection
