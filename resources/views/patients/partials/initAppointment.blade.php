<div class="modal fade" id="initAppointment" role="dialog" aria-labelledby="initAppointment">
    <div class="modal-dialog " role="document">
      <div class="modal-content">
        <div class="modal-header">
        
        <h4 class="modal-title" id="initAppointmentLabel">Iniciando la cita</h4>
        </div>
        <div class="modal-body" data-modaldate data-modalpatient data-slotDuration="{{ auth()->user()->settings->slotDuration }}">
            <div class="callout callout-info">
              <h4>Datos iniciales de la cita!</h4>

              <p>Puedes cambiar la fecha y las horas de la cita. </p>
            </div>
            <div class="content form-horizontal">
                
               <div class="row">
                 <div class="col-xs-12 col-sm-8">
                    <div class="form-group">
                    <label>Paciente: <span id="patient-name"></span></label>
                    </div>
                    <div class="form-group">
                          <select name="search-offices" id="search-offices" class="search-offices form-control select2 " style="width: 100%;">
                            <!-- <option value=""></option> -->
                          </select>
                          <ul class="search-list todo-list">
                          
                         </ul>
                  </div>
                </div>
                </div>
                <div class="row">
                 <div class="col-xs-12 col-sm-4">
                    <div class="form-group">
                      <label>Fecha:</label>
                      <div class="input-group date col-sm-10">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                       
                        <input type="text" class="form-control pull-right"  name="date" id="datetimepicker1">
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-6 col-sm-3">
                    <div class="form-group">
                      <label>Hora de inicio:</label>

                        <div class="input-group col-xs-9 col-sm-10">
                          <input type="text" class="form-control " name="start" id="datetimepicker2" >

                          <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                          </div>
                        </div>
                    </div>
                  </div>
                   <div class="col-xs-6 col-sm-3">
                      <div class="form-group">
                         <label>Hora de fin:</label>

                          <div class="input-group col-xs-9 col-sm-10">
                            <input type="text" class="form-control " name="end" id="datetimepicker3">

                            <div class="input-group-addon">
                              <i class="fa fa-clock-o"></i>
                            </div>
                          </div>
                      </div>
                  </div>
                </div>
                <div class="form-group">
                  
                 <button type="button" class="btn btn-primary add-cita">Iniciar la cita</button>
                </div>
              
            </div>
            

             
           
           
             
        </div>
         <div class="modal-footer" >
         
         
          <button type="button" class="btn btn-danger btn-cancel" data-dismiss="modal">Cancelar</button>
         
        </div>
      </div>
    </div>
  </div>