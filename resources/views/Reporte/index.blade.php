@extends('layouts.app')
@section('content')
    @include('/loading')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <h2 class="text-center">Módulo de Reporte</h2>
            <a href="{{ url('/reporte') }}" class="btn btn-xs btn-info pull-right">Reporte</a>
  
        </div>
    </div>
@endsection
