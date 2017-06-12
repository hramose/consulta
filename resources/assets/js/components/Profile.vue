<template>
	
      <div class="form-horizontal">
        <div class="form-group">
          <label for="name" class="col-sm-2 control-label">Nombre </label>

          <div class="col-sm-10">
            <input type="text" class="form-control" id="name" name="name" placeholder="Name" required v-model="profile.name" >
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-2 control-label">Email</label>

          <div class="col-sm-10">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" disabled v-model="profile.email" required>
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-sm-2 control-label">Cambiar contraseña</label>

          <div class="col-sm-10">
            <input type="password" class="form-control" id="password" name="password" placeholder="Escribe la nueva contraseña" v-model="profile.password">
          </div>
        </div>
       
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-danger"  @click="updateUser(profile)">Guardar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
          </div>
        </div>
      </div>
             
</template>

<script>
    export default {
      props: ['user'],
      data () {
        return {
          profile: {
            name:"",
            email:"",
            password: ""
          },
          loader:false
 
        }
      },
      methods: {
         
          updateUser (data) {
            this.loader = true;
            var resource = this.$resource('/medic/account/edit');

                resource.update(data).then((response) => {
                      console.log(response.status);
                      console.log(response.data);
                      
                      bus.$emit('alert', response.data);
                    
                     this.loader = false;
                }, (response) => {
                    console.log('error al actualizar usuario')
                    this.loader = false;
                });
          }
     
      },
      created () {
           console.log('Component ready. Profile')
           this.profile.name = this.user.name;
           this.profile.email = this.user.email;
           
          
      }
    }
</script>