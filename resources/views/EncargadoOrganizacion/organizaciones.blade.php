@extends('layouts.app')
@section('content')
    @include('/loading')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <h2 class="text-center">Módulo de Organizaciones</h2>
            @include('EncargadoOrganizacion.buscar_organizacion')
            <div class="panel panel-primary">

                <div class="panel-heading">
                    Organizaciones
                </div>

                <div class="panel-body table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="postTable">
                        <thead>
                        <tr>
                            <th valign="middle">ID</th>
                            <th>Nombre</th>
                            <th>Edificio</th>
                            <th>Piso/sección</th>
                            <th>Nombre encargado</th>
                            <th>Email encargado</th>
                            <th>Opciones</th>
                        </tr>
                        {{ csrf_field() }}
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr class="item{{$post->idOrganizacion}}">
                                <td>{{$post->idOrganizacion}}</td>
                                <td>{{$post->Nombre}}</td>
                                <td>{{$post->nombre_edificio}}</td>
                                <td>{{$post->nombre_piso}}</td>
                                <td>{{$post->nombre_encargado}}</td>
                                <td>{{$post->email_encargado}}</td>
                                <td>
                                    <a href="{{url('getVisitas').'/'.$post->idOrganizacion}}">
                                        <button class="btn btn-primary"><span
                                                    class="glyphicon glyphicon-eye-open"></span> Detalles
                                        </button>
                                    </a>

                                    <a class="btn btn-warning"
                                       onclick="event.preventDefault(); document.getElementById('descargar-{{$post->idOrganizacion}}').submit();">
                                        <span
                                                class="fa fa-download"></span> Descargar Reporte
                                    </a>

                                    <form id="descargar-{{$post->idOrganizacion}}"
                                          action="{{ url('reporte_documento') }}" method="POST" target="_blank"
                                          style="display: none;">
                                        <input class="hidden" name="organizacion" value="{{$post->idOrganizacion}}">
                                        <input class="hidden" name="edificio" value="{{$post->idEdificio}}">

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
