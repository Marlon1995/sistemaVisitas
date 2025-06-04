@extends('layouts.app')
@section('content')
    @include('/loading')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <h2 class="text-center">Módulo de visitas</h2>
            @if($fecha_actual==$fecha_visita && $tipo==2 || $tipo==0)
                @include('Accesos.create')
            @endif
            <div class="panel panel-primary">
                <div class="panel-heading">Usuarios</div>
                <div class="panel-body table-responsive" id="modal">
                    @include('Accesos.buscar1')
                    <table class="table table-striped table-bordered table-hover" id="postTable">
                        <thead>
                        <tr>
                            <th valign="middle">#Ticket</th>
                            <th>Edificio</th>
                            <th>Piso</th>
                            <th>Organización</th>
                            <th>Código Tarjeta</th>
                            <th>Nombres</th>
                            <th>Cedula</th>
                            <th>Foto</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            @if($tipo==0)
                            
                                @endif
                        </tr>
                        {{ csrf_field() }}
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr class="item{{$post->idAcceso}}">
                                <td>{{$post->idAcceso}}</td>
                                <td>{{$post->nombre_edificio}}</td>
                                <td>{{$post->nombre_piso}}</td>
                                <td>{{$post->nombre_organizacion}}</td>
                                <td>{{$post->CodigoTarjeta}}</td>
                                <td>{{$post->Nombres}}</td>
                                <td>{{$post->Cedula}}</td>                                
                                  @if($post->Sexo=='M')
                                <td><img src="{{asset('public/avatar_hombre.png')}}" width="80px" height="80px"></td>
                                @else
                                <td><img src="{{asset('public/avatar_mujer.png')}}" width="80px" height="80px"></td>
                                @endif
                                
                                <td>{{$post->Creacion}}</td>
                                <td>
                                    @if($post->Estado==0)
                                        <h4><span class="label label-danger">Anulado</span></h4>
                                    @else
                                        <h4><span class="label label-success">Activo</span></h4>
                                    @endif
                                </td>
                                <td>
                                    @if($post->Estado==0 && $tipo==0)
                                        <a href="" data-target="#modal-activar-{{$post->idAcceso}}" data-toggle="modal">
                                            <button class="delete-modal btn btn-warning"><span
                                                        class="glyphicon glyphicon-trash"></span> Activar
                                            </button>
                                        </a>
                                    @else
                                        @if($tipo==0)
                                            <a href="" data-target="#modal-delete-{{$post->idAcceso}}"
                                               data-toggle="modal">
                                              
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @include('Accesos.modal')
                            @include('Accesos.modal1')
                        @endforeach
                        </tbody>
                    </table>
                </div><!-- /.panel-body -->
                <div class="text-center">
                    {{$posts->render()}}
                </div>            </div><!-- /.panel panel-default -->
        </div>
    </div>
@endsection
