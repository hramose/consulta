@extends('layouts.app-admin')

@section('css')
   <link rel="stylesheet" href="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
@endsection
@section('content')

 
        @include('layouts/partials/header-pages',['page'=>'Reportes Pacientes'])

   <section class="content">
        <reports-patients></reports-patients>
    </section>
       


@endsection
@section('scripts')
<script src="/js/plugins/moment/moment.min.js"></script>
<script src="/js/plugins/moment/locale/es.js"></script>

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