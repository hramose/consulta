@extends('layouts.app-patient')
@section('css')
 
@endsection
@section('content')
    
    <div id="infoBox" class="alert alert-success" ></div>
  @include('layouts/partials/header-pages',['page'=>'Encuesta'])


    <section class="content">
       <div class="row">
       	 <div class="col-md-12">
       	     <poll :poll="{{ $poll }}"></poll>
       	     	
       	 
       		
       	 </div>
       	 
       </div>
       
         
    </section>


@endsection
@section('scripts')


@endsection
