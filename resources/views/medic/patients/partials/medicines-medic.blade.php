
<div class="box box-info">

    <div class="box-header with-border">
      <h3 class="box-title">Medicamentos Activos</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
      </div>
      <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
     
    @if(! isset($read))
      <medicines :medicines="{{ $patient->medicines()->where('medic_id', auth()->id())->get() }}" :patient_id="{{ $patient->id }}" :medic_id="{{ auth()->id() }}"></medicines>
    @else
      <medicines :medicines="{{ $patient->medicines()->where('medic_id', auth()->id())->get() }}" :patient_id="{{ $patient->id }}" :read="true" :medic_id="{{ auth()->id() }}"></medicines>
    @endif
        
    </div>
    <!-- /.box-body -->
</div>