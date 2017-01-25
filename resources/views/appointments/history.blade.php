@extends('layouts.app-patient')

@section('content')
     
     @include('layouts/partials/header-pages',['page'=>'Historial de citas'])


    <section class="content">
        <div class="row" style="text-align:  center;">
          @include('patients/partials/appointments',['fromPatient'=> '1'])
        </div>

    </section>

<form method="post" id="form-delete" data-confirm="Estas Seguro?">
  <input name="_method" type="hidden" value="DELETE">{{ csrf_field() }}
</form>
@endsection
@section('scripts')

@endsection