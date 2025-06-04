@extends('layouts.app')
@section('content')
    @include('/loading')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <h2 class="text-center">Módulo de asignación</h2>
            @include('AsignacionesDueno.buscar')
            <div class="panel panel-primary">

                <div class="panel-heading">Usuarios</div>

                <div class="panel-body table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="postTable">
                        <thead>
                        <tr>
                            <th valign="middle">ID</th>
                            <th>Nombres</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                        {{ csrf_field() }}
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr class="item{{$post->id}}">
                                <td>{{$post->id}}</td>
                                <td>{{$post->name}}</td>
                                <td>{{$post->email}}</td>
                                <td>{{$post->UserName}}</td>
                                <td>
                                    @if($post->Estado==0)
                                        <h4><span class="label label-danger">Anulado</span></h4>
                                    @else
                                        <h4><span class="label label-success">Activo</span></h4>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{URL::action('DuenoEdificioControl@show',$post->id)}}">
                                        <button class="btn btn-primary"><span
                                                    class="glyphicon glyphicon-eye-open"></span> Edificios
                                        </button>
                                    </a>
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
