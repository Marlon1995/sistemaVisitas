@extends('layouts.app')
@section('content')
    @include('/loading')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <h2 class="text-center">Módulo de visitas</h2>
            @include('EncargadoOrganizacion.buscar_visitas')
            <div class="panel panel-primary">

                <div class="panel-heading">Visitas</div>

                <div class="panel-body table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="postTable">
                        <thead>
                        <tr>
                            <th valign="middle">ID</th>
                            <th>Fecha</th>
                            <th>Edificio</th>
                            <th>Piso</th>
                            <th>Organizacion</th>
                            <th>Opciones</th>
                        </tr>
                        {{ csrf_field() }}
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr class="item{{$post->idVisita}}">
                                <td>{{$post->idVisita}}</td>
                                <td>{{$post->Fecha}}</td>
                                <td>{{$post->Nombre}}</td>
                                <td>{{$post->nombre_piso}}</td>

                                <td>{{$post->nombre_organizacion}}</td>

                                <td>
                                    <a href="{{url('accesos_encargado').'/'.$post->idVisita}}">
                                        <button class="btn btn-primary"><span
                                                    class="glyphicon glyphicon-eye-open"></span> Detalles
                                        </button>
                                    </a>
                                    <a class="btn btn-warning"
                                       onclick="event.preventDefault(); document.getElementById('descargar').submit();">
                                        <span
                                                class="fa fa-download"></span> Descargar
                                    </a>

                                    <form id="descargar" action="{{ url('reporte_documento') }}" method="POST"
                                          target="_blank"
                                          style="display: none;">
                                        <input class="hidden" name="organizacion" value="{{$post->idOrganizacion}}">
                                        <input class="hidden" name="edificio" value="{{$post->idEdificio}}">
                                        <input class="hidden" name="visita" value="{{$post->idVisita}}">
                                        <input class="hidden" name="desde" value="{{$post->Fecha}}">

                                        {{ csrf_field() }}
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div><!-- /.panel-body -->
                <div class="text-center">
                    {{$posts->render()}}
                </div>
            </div><!-- /.panel panel-default -->
        </div>
    </div>

@endsection
