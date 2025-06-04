@extends('layouts.app')
@section('content')
    @include('/loading')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <h2 class="text-center">Módulo Visitas</h2>
            @include('Accesos.buscar')
            <div class="panel panel-primary">
                <div class="panel-heading">
                    @if($tipo==2)
                        <a href="#" class="add-modal btn btn-primary "> <span
                                    class="   glyphicon glyphicon-floppy-saved">Crear</span></a>
                    @else
                        Visistas
                    @endif

                </div>
                <div class="panel-body table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="postTable">
                        <thead>
                        <tr>
                            <th valign="middle">ID</th>
                            <th>Edificio</th>
                            <th>Fecha</th>
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
                                @if($tipo==2)
                                    <td>
                                        <a href="{{URL::action('AccesosControl@show',$post->idVisita)}}">
                                            <button class="btn btn-primary"><span
                                                        class="glyphicon glyphicon-eye-open"></span> Detalles
                                            </button>
                                        </a>
                                    </td>
                                @else
                                    <td>
                                        <a href="{{URL::action('AccesosControl@show_dueno',$post->idVisita)}}">
                                            <button class="btn btn-primary"><span
                                                        class="glyphicon glyphicon-eye-open"></span> Detalles
                                            </button>
                                        </a>
                                    </td>
                                @endif

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div><!-- /.panel-body -->
                <div class="text-center">
                    {{$posts->render()}}
                </div>            </div><!-- /.panel panel-default -->
        </div>
    </div>
    <script type="text/javascript">
        $(document).on('click', '.add-modal', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'POST',
                url: "{{url('crear_fecha_visita')}}",
                data: {
                    '_token': $('input[name=_token]').val(),
                    'edificio': "{{$Edificio}}"
                },
                success: function (data) {
                    if ((data.errors)) {
                        setTimeout(function () {
                            toastr.error('No guardado!' +
                                data.errors.edificio + " \n", 'Error Alert', {timeOut: 5000});
                        }, 500);
                    } else {
                        toastr.success('  Con exito!', 'Success Alert', {timeOut: 5000});
                        location.href = "{{url('mostrar_visita')}}" + "/" + data.idVisita;

                    }
                    $(".loader").fadeOut("slow");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $(".loader").fadeOut("slow");
                    toastr.error('No guardado!' + "Error 500", 'Error Alert', {timeOut: 5000});
                }
            });
        });
    </script>
@endsection
