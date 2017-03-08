<template>
<div>
  <div class="modal fade" id="modalReminder" tabindex="-1" role="dialog" aria-labelledby="modalReminderLabel">
    <div class="modal-dialog " role="document">
      <div class="modal-content">
        <div class="modal-header">
        
          <h4 class="modal-title" id="modalReminderLabel">Recordatorio de la cita</h4>
        </div>
        <div class="modal-body" data-appointment>
            
           
           <select name="reminder_time" id="reminder_time" class="form-control">
             <option disabled></option>
             <option value="02:00:00">2 hrs antes</option>
             <option value="06:00:00">6 hrs antes</option>
             <option value="24:00:00">1 dia antes</option>
           </select>

              
          
               
            
             
             
        </div>
         <div class="modal-footer">
         
          <button type="button" class="btn btn-default pull-left btn-cancelar-cita" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary btn-finalizar-reminder">Crear Recordatorio</button>
          <button type="button" class="btn btn-primary btn-close-cita" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  
  
</div>
</template>

<script>

    
    export default {

     

    
    }
</script>