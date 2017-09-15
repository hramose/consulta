@extends('layouts.app-admin')

@section('css')
   <link rel="stylesheet" href="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
@endsection
@section('content')

 
        @include('layouts/partials/header-pages',['page'=>'Tipos de Reportes'])

    <div class="content">
        <div class="row">
           <div class="col-md-4 col-sm-6 col-xs-12">
              <a href="/admin/reports/medics" class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-user-md"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Medicos <span data-toggle="tooltip" title="" class="badge bg-light-blue" data-original-title="{{ $medics }} Médicos">{{ $medics }}</span></span> 
                  <span class="info-box-number"><small>Activos, inactivos, consultas</small></span>
                </div>
                <!-- /.info-box-content -->
              </a>
              <!-- /.info-box -->
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <a href="/admin/reports/clinics" class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-building"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Clinicas <span data-toggle="tooltip" title="" class="badge bg-light-blue" data-original-title="{{ $medics }} Clinicas">{{ $clinics }}</span></span> 
                  <span class="info-box-number"><small>Registradas, consultas</small></span>
                </div>
                <!-- /.info-box-content -->
              </a>
              <!-- /.info-box -->
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <a href="/admin/reports/patients" class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-user"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Pacientes <span data-toggle="tooltip" title="" class="badge bg-light-blue" data-original-title="{{ $patients }} Pacientes">{{ $patients }}</span></span> 
                  <span class="info-box-number"><small>Por regiones</small></span>
                </div>
                <!-- /.info-box-content -->
              </a>
              <!-- /.info-box -->
            </div>
       </div>
          
          <h2>Reporte de Ingresos</h2>
          <reports-incomes></reports-incomes>
     
        
    </div>
       


@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/locale/es.js"></script>

<script src="/js/bootstrap.min.js"></script>

 <script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script> 
<script>
  $(function () {
     $(".dropdown-toggle").dropdown();

    $('#datepicker1').datetimepicker({
            format:'YYYY-MM-DD',
            locale: 'es',
            
         });
     $('#datepicker2').datetimepicker({
            format:'YYYY-MM-DD',
            locale: 'es',
            
         });
    

  });
</script>
@endsection