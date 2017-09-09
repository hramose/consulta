
<div class="box box-warning">

    <div class="box-header with-border">
      <h3 class="box-title">Archivos Resultados de Laboratorios</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
      </div>
      <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      
    @if(! isset($read))
      <lab-results :results="{{ $patient->labresults }}" :patient_id="{{ $patient->id }}"></lab-results>	
    @else 
      <lab-results :results="{{ $patient->labresults }}" :patient_id="{{ $patient->id }}" :read="true"></lab-results>	
    @endif
    </div>
    <!-- /.box-body -->
</div>