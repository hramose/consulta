
<div class="box box-success">

    <div class="box-header with-border">
      <h3 class="box-title">Signos vitales actuales</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
      </div>
      <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
     
       
		 <signs :signs="{{ $patient->vitalSigns }}"></signs>	
      
        
    </div>
    <!-- /.box-body -->
</div>