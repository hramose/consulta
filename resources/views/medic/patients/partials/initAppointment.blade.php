<div class="modal fade" id="initAppointment" role="dialog" aria-labelledby="initAppointment">
    <div class="modal-dialog " role="document">
      <div class="modal-content">
        <div class="modal-header">
        
        <h4 class="modal-title" id="initAppointmentLabel">Iniciando la cita</h4>
        </div>
        <div class="modal-body" data-modaldate data-modalpatient data-slotDuration="{{ auth()->user()->settings->slotDuration }}">
            <div class="callout callout-info">
              <p>Selecciona un horario en el calendario para ver los campos disponibles en la clinica correspondiente </p>
            </div>
            <div class="content form-horizontal">
                
               <!-- <div class="row">
                 <div class="col-xs-12 col-sm-12">
                    <div class="form-group">
                    <label>Paciente: <span id="patient-name"></span></label>
                    </div>
                    <div class="form-group">
                          <select name="search-offices" id="search-offices" class="search-offices form-control select2 " style="width: 100%;">
                            
                          </select>
                          <ul class="search-list todo-list">
                          
                         </ul>
                  </div>
                </div>
                </div> -->
                
                <div class="row">
                  <div class="col-xs-12 col-sm-7 calendar-popup" >
                 
                    <div id="miniCalendar" data-slotDuration="{{ auth()->user()->settings->slotDuration }}" data-minTime="{{ auth()->user()->settings->minTime }}" data-maxTime="{{ auth()->user()->settings->maxTime }}" data-freeDays="{{ auth()->user()->settings->freeDays }}"></div>
                  </div>
                  <div class="col-xs-12 col-sm-5">
                      
                          
                          <div class="form-group">
                            <h3>Horario Disponible</h3>
                            <span class="label label-warning schedule-clinic"></span>
                              <select name="schedule-list" id="schedule-list" class="form-control ">
                                
                              </select>
                              
                          </div>
                          <!-- <div class="schedule-list">
                          
                          </div> -->
                          <input type="hidden" name="office_id" >
                          <input type="hidden" name="date" >
                          <input type="hidden" name="start" >
                          <input type="hidden" name="end">
                          <div class="form-group">
                      
                            <button type="button" class="btn btn-primary add-cita">Iniciar la cita</button>
                          </div>
                      
                      
                  </div>
                
                </div>
               
              
            </div>
            

             
           
           
             
        </div>
         <div class="modal-footer" >
         
         
          <button type="button" class="btn btn-danger btn-cancel" data-dismiss="modal">Cancelar</button>
         
        </div>
      </div>
    </div>
  </div>