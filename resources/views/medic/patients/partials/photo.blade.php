<!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <!--<img class="profile-user-img img-responsive img-circle" src="/img/user4-128x128.jpg" alt="User profile picture">-->
              @if(isset($patient))
                
                   <img class="profile-user-img img-responsive img-circle" src="{{ getPhoto($patient) }}" alt="User profile picture">
                <h3 class="profile-username text-center">{{ $patient->first_name }}</h3>

                <p class="text-muted text-center">{{ $patient->city }}</p>
                @if(!isset($read))
                  <a class="UploadButton btn btn-primary btn-block" id="UploadPhoto" data-url="/medic/patients/photos">Subir Foto</a> 
                @endif
              @else
                <img class="profile-user-img img-responsive img-circle" src="/img/default-avatar.jpg" alt="User profile picture">
              @endif

               
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->