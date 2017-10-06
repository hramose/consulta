
<div class="box box-default">

    <div class="box-header with-border">
      <h3 class="box-title">Archivos</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
      </div>
      <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <ul id="files-list" class="todo-list ui-sortable">
        @foreach($files as $file)
        <li>
         
          <a href="/storage/patients/{{ $file->patient_id }}/files/{{ $file->name }}" title="{{ $file->name }}" target="_blank"><span class="text">{{ $file->name }}</span></a>
          
          <div class="tools">
          @if(! isset($read))
            <i class="fa fa-trash-o delete" data-file="/patients/{{ $file->patient_id }}/files/{{ $file->name }}" data-id="{{ $file->id }}"></i>
            @endif
          </div>
        </li>
        @endforeach 
        <!-- {{--@foreach($files as $file)--}}
        <li>
         
          <a href="{{-- Storage::url($file) --}}" title="{{-- $file --}}" target="_blank"><span class="text">{{--  explode("/",$file)[3]  --}}</span></a>
          
          <div class="tools">
          @if(! isset($read))
            <i class="fa fa-trash-o delete" data-file="{{-- $file --}}"></i>
            @endif
          </div>
        </li>
       {{--  @endforeach --}} -->
      </ul>
        @if(! isset($read))
          <a class="UploadButton btn btn-primary btn-block" id="UploadFile">Subir Archivo</a> 
        @endif
       
      
        
    </div>
    <!-- /.box-body -->
</div>