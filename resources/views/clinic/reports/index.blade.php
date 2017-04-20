@extends('layouts.app-clinic')

@section('css')

@endsection
@section('content')

 
        @include('layouts/partials/header-pages',['page'=>'Reportes'])


    <section class="content">
		<reports-clinic></reports-clinic>
    </section>


@endsection
@section('scripts')

@endsection