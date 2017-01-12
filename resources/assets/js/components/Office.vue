<template>	
	<div class="form-horizontal">
      <div class="form-group">
        <label for="office_name" class="col-sm-2 control-label">Nombre</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" name="name" placeholder="Nombre del consultorio" v-model="office.name" >
          <form-error v-if="errors.name" :errors="errors" style="float:right;">
              {{ errors.name[0] }}
          </form-error>
          </div>
      </div>
      <div class="form-group">
        <label for="office_address" class="col-sm-2 control-label">Dirección</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" name="address" placeholder="Dirección"  v-model="office.address">
          <form-error v-if="errors.address" :errors="errors" style="float:right;">
              {{ errors.address[0] }}
          </form-error>
        </div>
      </div>
      <div class="form-group">
        <label for="office_province" class="col-sm-2 control-label">Provincia</label>

        <div class="col-sm-10">
          <select class="form-control " style="width: 100%;" name="province" placeholder="-- Selecciona provincia --"  v-model="office.province">
            <option></option>
            <option v-for="item in provincias" v-bind:value="item"> {{ item }}</option>
            
          </select>
          <form-error v-if="errors.province" :errors="errors" style="float:right;">
              {{ errors.province[0] }}
          </form-error>
        </div>
      </div>
      <div class="form-group">
        <label for="office_city" class="col-sm-2 control-label">Ciudad</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" name="city" placeholder="Ciudad" v-model="office.city" >
          <form-error v-if="errors.city" :errors="errors" style="float:right;">
              {{ errors.city[0] }}
          </form-error>
        </div>
      </div>
      <div class="form-group">
        <label for="office_phone" class="col-sm-2 control-label">Teléfono</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" name="phone" placeholder="Teléfono" v-model="office.phone">
          <form-error v-if="errors.phone" :errors="errors" style="float:right;">
              {{ errors.phone[0] }}
          </form-error>
        </div>
      </div>
       <div class="form-group">
        <label for="lat" class="col-sm-2 control-label">Coordenadas (Para Google Maps y Waze)</label>

        
             <div class="col-sm-3">
              <div class="input-group">
                <span class="input-group-addon">lat:</span>
                <input type="text" class="form-control" name="lat" placeholder="10.637875" v-model="office.lat">
              </div>
               
            </div>
            <div class="col-sm-3">
              <div class="input-group">
                <span class="input-group-addon">lon:</span>
                <input type="text" class="form-control" name="lon" placeholder="-85.434431" v-model="office.lon">
              </div>
               
            </div>
            <div class="col-sm-3">
          
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                Ver ejemplo
              </button>
              

              <!-- Modal -->
              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel">Ejemplo de Coordenadas</h4>
                    </div>
                    <div class="modal-body">
                      <img src="/img/img-mapa-coordenadas.png" alt="Coordenadas Google Maps" style="width: 100%;" />
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>


            </div>
            
          
        
       
      </div>
      <div class="form-group">
          
         
      </div>
     
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-danger" @click="save()">Guardar</button>
        </div>
      </div>
      <h3>Tus Consultorios o clínicas</h3>
      <ul id="offices-list" class="todo-list ui-sortable" v-show="consultorios.length">
       
        <li v-for="item in consultorios">
          <!-- todo text -->
          <a href="#"><i class="fa fa-building"></i><span><span class="text" @click="edit(item)"> {{ item.name }} - {{ item.province }}, {{ item.city }} - {{ item.phone }}</span></span></a>
          <!-- General tools such as edit or delete-->
          <div class="tools">
            <i class="fa fa-edit" @click="edit(item)"></i>
            <i class="fa fa-trash-o delete" @click="remove(item)"></i>
          </div>
        </li>
       
      </ul>
  </div>
</template>

<script>
    import FormError from './FormError.vue';

    export default {
      props: ['offices'],
      data () {
        return {
          consultorios: [],
          provincias: ['Guanacaste','San Jose','Heredia','Limon','Cartago','Puntarenas','Alajuela'],
          loader:false,
          office: {},
          errors: []
         
        
          
        }
      },
      components:{
        FormError
      },
      methods: {
        edit(office) {

          this.office = office;
        
        },
        save() {

          //var resource = this.$resource('/medic/account/offices');
           if(this.office.id)
           {
             var resource = this.$resource('/medic/account/offices/'+ this.office.id);

                resource.update(this.office).then((response) => {
                    
                     bus.$emit('alert', 'Consultorio Actualizado','success');
                     this.loader = false;
                     this.errors = [];
                     this.office = {};
                }, (response) => {
                    console.log(response.data)
                    this.loader = false;
                    this.loader_message ="Error al guardar cambios";
                    this.errors = response.data;
                });

           }else{
              this.$http.post('/medic/account/offices', this.office).then((response) => {
                    console.log(response.status);
                    console.log(response.data);
                    if(response.status == 200 && response.data)
                    {
                      this.consultorios.push(response.data);
                      bus.$emit('alert', 'Consultorio Agregado','success');
                      this.office = {};
                      this.errors = [];
                    }
                   this.loader = false;
              }, (response) => {
                  console.log('error al guardar consultorio')
                  this.loader = false;
                   this.errors = response.data;
              });
        
            }

      },
	     

      remove(item){
           

            this.$http.delete('/medic/account/offices/'+item.id).then((response) => {

                  if(response.status == 200)
                  {
                     var index = this.consultorios.indexOf(item)
                    this.consultorios.splice(index, 1);
                    bus.$emit('alert', 'Consultorio Eliminado','success');
                  }

              }, (response) => {
                  
                   bus.$emit('alert', 'Error al eliminar el consultorio', 'danger');
                  this.loader = false;
              });


          }
    

      },
      created () {
             console.log('Component ready. office')

             this.consultorios = this.offices;
            
        }
    }
</script>