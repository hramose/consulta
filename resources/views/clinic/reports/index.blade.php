@extends('layouts.app-clinic')

@section('css')

@endsection
@section('content')

 
        @include('layouts/partials/header-pages',['page'=>'Reportes'])


    <section class="content">
		<reports-clinic :clinic="{{ auth()->user()->offices->first()->id }}"></reports-clinic>
    </section>


@endsection
@section('scripts')

@endsection