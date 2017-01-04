<!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <!--<img class="profile-user-img img-responsive img-circle" src="/img/user4-128x128.jpg" alt="User profile picture">-->
              @if(isset($patient))
                <img class="profile-user-img img-responsive img-circle" src="{{ (Storage::disk('public')->exists('patients/'.$patient->id.'/photo.jpg')) ? Storage::url('patients/'.$patient->id.'/photo.jpg') : Storage::url('avatars/default-avatar.jpg') }}" alt="User profile picture">
                <h3 class="profile-username text-center">{{ $patient->first_name }}</h3>

                <p class="text-muted text-center">{{ $patient->city }}</p>
                
                <a class="UploadButton btn btn-primary btn-block" id="UploadPhoto" data-url="/patients/photos">Subir Foto</a> 
              @else
                <img class="profile-user-img img-responsive img-circle" src="{{ Storage::url('avatars/default-avatar.jpg') }}" alt="User profile picture">
              @endif

               
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->