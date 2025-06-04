@extends('layouts.app')
@section('content')
    @include('/loading')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <h2 class="text-center">Módulo de Auditorías</h2>
            @include('Auditorias.search')
            <div class="panel panel-primary">

                <div class="panel-heading">
                    Auditorías
                </div>

                <div class="panel-body table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="postTable">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombres</th>
                            <th>Operacción</th>
                            <th>Fecha</th>
                        </tr>
                        {{ csrf_field() }}
                        </thead>
                        <tbody>
                        @foreach($datos as $aud)
                            <tr class="item{{$aud->id}}">
                                <td>{{ $aud->id}}</td>
                                <td>{{ ($aud->name==null)?"Sistema":$aud->name}}</td>
                                <td width="50%">{{ $aud->Accion}}</td>
                                <td>{{ $aud->Creacion}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div><!-- /.panel-body -->
                <div class="text-center">
                    {{$datos->render()}}
                </div>
            </div><!-- /.panel panel-default -->
        </div>
    </div>
@endsection
