<template>	
	<div class="form-horizontal">
      <div class="form-group">
        <label for="office_name" class="col-sm-2 control-label">Nombre</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" id="office_name" placeholder="Nombre del Consultorio" value="" required>
        </div>
      </div>
      <div class="form-group">
        <label for="office_address" class="col-sm-2 control-label">Dirección</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" id="office_address" placeholder="Dirección" value="">
        </div>
      </div>
      <div class="form-group">
        <label for="office_province" class="col-sm-2 control-label">Provincia</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" id="office_province" placeholder="Provincia" >
        </div>
      </div>
      <div class="form-group">
        <label for="office_city" class="col-sm-2 control-label">Ciudad</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" id="office_city" placeholder="Ciudad" >
        </div>
      </div>
      <div class="form-group">
        <label for="office_phone" class="col-sm-2 control-label">Teléfono</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" id="office_phone" placeholder="Teléfono">
        </div>
      </div>
     
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-danger">Guardar</button>
        </div>
      </div>
    </div>
</template>

<script>
    export default {
      props: ['office'],
      data () {
        return {
          consultorio: {},
          loader:false,
         
        
          
        }
      },
      methods: {
         
        
	     update(data) {

	        var resource = this.$resource('/account/edit');
	         
	            resource.update(data).then((response) => {
	                  console.log(response.status);
	                  console.log(response.data);
	                 this.loader = false;
	            }, (response) => {
	                console.log('error al actualizar usuario')
	                this.loader = false;
	            });
	      }
    

      },
      created () {
             console.log('Component ready. office')
             //this.consultorio.name = this.office.name;
            
        }
    }
</script>