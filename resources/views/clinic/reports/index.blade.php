@extends('layouts.app-clinic')

@section('css')
   <link rel="stylesheet" href="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
@endsection
@section('content')

 
        @include('layouts/partials/header-pages',['page'=>'Reportes'])


    <section class="content">
		<reports-clinic :clinic="{{ auth()->user()->offices->first()->id }}"></reports-clinic>
    </section>


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