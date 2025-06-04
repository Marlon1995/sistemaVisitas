@extends('layouts.app')
@section('content')
    @include('/loading')
    <div class="row">
        <div class="col-xs-12">
            <h2 class="text-center">Módulo de usuarios</h2>

            @include('Usuarios.buscar')
            <div class="panel panel-primary">
                <div class="panel-heading">

                    <a href="#" class="add-modal btn btn-primary "> <span class="   glyphicon glyphicon-floppy-saved">Crear</span></a>
                </div>

                <div class="panel-body table-responsive" id="modal">

                    <table class="table table-striped table-bordered table-hover" id="postTable">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombres</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Estado</th>
                            <th>Opciones</th>

                        </tr>
                        {{ csrf_field() }}
                        </thead>
                        <tbody>
                        @foreach ($posts as $pos)
                            <tr class="item{{$pos->id}}">
                                <td>{{$pos->id}}</td>
                                <td>{{$pos->name}}</td>
                                <td>{{$pos->email}}</td>
                                <td>{{$pos->UserName}}</td>
                                <td>@if($pos->Estado==0)
                                        <h4><span class="label label-danger">Anulado</span></h4>
                                    @else
                                        <h4>
                                            <span class="label label-success">Activo</span>
                                        </h4>
                                    @endif
                                </td>
                                <td>
                                    @if($pos->id==1)
                                        <a href="{{URL::action('UsuarioControl@show',$pos->id)}}">
                                            <button class="btn btn-primary form-control"><span
                                                        class="glyphicon glyphicon-eye-open"></span> Detalles
                                            </button>
                                        </a>
                                    @else
                                        <a href="{{URL::action('UsuarioControl@show',$pos->id)}}">
                                            <button class="btn btn-primary"><span
                                                        class="glyphicon glyphicon-eye-open"></span> Detalles
                                            </button>
                                        </a>
                                    @endif
                                    @if($pos->id!=1)


                                        @if($pos->Estado==0)
                                            <a href="" data-target="#modal-activar-{{$pos->id}}" data-toggle="modal">
                                                <button class="delete-modal btn btn-warning"><span
                                                            class="glyphicon glyphicon-trash"></span> Activar
                                                </button>
                                            </a>
                                        @else
                                            <a href="" data-target="#modal-delete-{{$pos->id}}" data-toggle="modal">
                                                <button class="delete-modal btn btn-danger"><span
                                                            class="glyphicon glyphicon-trash"></span> Anular
                                                </button>
                                            </a>
                                        @endif
                                        <a href="{{URL::action('UsuarioControl@cambiar_index_administrador',$pos->id)}}">
                                            <button class="btn btn-info"><span class="glyphicon glyphicon-edit"></span>
                                                Actualizar
                                            </button>
                                        </a>

                                    @endif


                                </td>
                            </tr>
                            @include('Usuarios.modal')
                            @include('Usuarios.modal1')

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


    <div id="addModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-label-left" novalidate>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Usuario">Nombre
                                Usuario:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="usuario_add" id="usuario_add" type="text">
                                <p class="errorNombreUsuario text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="id">UserName:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="username_add" id="username_add" type="text">
                                <p class="errorUserName text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="id">Password:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="password_add" id="password_add" type="password">
                                <p class="errorPassword text-center alert alert-danger hidden"></p>
                            </div>
                        </div>

                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary add" data-dismiss="modal">
                            <span class='glyphicon glyphicon-check'></span> Crear
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        // add a post
        $(document).on('click', '.add-modal', function () {
            $('.modal-title').text('Inserta Usuario');
            $('#addModal').modal('show');
        });


        $('.modal-footer').on('click', '.add', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'post',
                url: 'usuarios',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'UserName': $('#username_add').val(),
                    'password_usuario': $('#password_add').val(),
                    'nombre_usuario': $('#usuario_add').val()
                },
                success: function (data) {
                    $('.errorNombreUsuario').addClass('hidden');
                    $('.errorPassword').addClass('hidden');
                    $('.errorUserName').addClass('hidden');
                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#addModal').modal('show');
                            toastr.error('No guardado!' + "\n" + data.errors.UserName + "\n" + data.errors.password_usuario + "\n" + data.errors.password_usuario, 'Error Alert', {timeOut: 5000});
                        }, 500);

                        if (data.errors.UserName) {
                            $('.errorUserName').removeClass('hidden');
                            $('.errorUserName').text(data.errors.UserName);
                        }
                        if (data.errors.password_usuario) {
                            $('.errorPassword').removeClass('hidden');
                            $('.errorPassword').text(data.errors.password_usuario);
                        }
                        if (data.errors.nombre_usuario) {
                            $('.errorNombreUsuario').removeClass('hidden');
                            $('.errorNombreUsuario').text(data.errors.nombre_usuario);
                        }

                    } else {
                        toastr.success('  Con exito!', 'Success Alert', {timeOut: 5000});
                        $('#postTable').prepend("<tr class=item" + data.id + ">" +
                            "<td>" + data.id + "</td>" +
                            "<td>" + data.name + "</td>" +
                            "<td>" + data.email + "</td>" +
                            "<td>" + data.UserName + "</td>" +
                            "<td><h4><span class='label label-success'>Activo</span></h4></td>" +

                            "<td>" +
                            "<a href='" + "{{url('usuarios')}}/" + data.id + "'><button class='btn btn-primary'><span class='glyphicon glyphicon-eye-open'></span> Detalles</button></a>" +

                            " <a href='' data-target='#modal-delete-" + data.id + "' data-toggle='modal'><button class='delete-modal btn btn-danger'><span class='glyphicon glyphicon-trash'></span> Anular</button></a>" +

                            " <a href='" + "{{url('cambiar_admin')}}/" + data.id + "'><button class='btn btn-info'><span class='glyphicon glyphicon-edit'></span> Actualizar</button></a>" +

                            "</td>" +
                            "</tr>");

                        $('#modal').prepend("<div class='modal fade modal-slide-in-right' aria-hidden='true' role='dialog' tabindex='-1' id='modal-delete-" + data.id + "'>" +
                            "<form method='post' action='" + "{{url('usuarios')}}/" + data.id + "'>" +
                            '<input name="_method" type="hidden" value="DELETE">' +
                            '{!! csrf_field() !!}' +
                            "<div class='modal-dialog'>" +
                            "<div class='modal-content'>" +
                            "<div class='modal-header'>" +
                            "<button type='button' class='close' data-dismiss='modal'         aria-label='Close'>" +
                            "<span aria-hidden='true'>×</span>" +
                            "</button>" +
                            "<h4 class='modal-title'>Anulación usuario</h4>" +
                            "</div>" +
                            "<div class='modal-body'>" +
                            "<p>Confirme si desea Anulación de usuario</p>" +
                            "</div>" +
                            "<div class='modal-footer'>" +
                            "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>" +
                            "<button type='submit' class='btn btn-primary'>Confirmar</button>" +
                            "</div>" +
                            "</div>" +
                            "</div>" +
                            "</form>" +
                            "</div>");

                    }
                    $(".loader").fadeOut("slow");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $(".loader").fadeOut("slow");
                    toastr.error('No guardado!' + "Error 500", 'Error Alert', {timeOut: 5000});
                }
            });
        });

        $("#addModal").on('keyup', function (e) {
            if (e.keyCode == 13) {
                $('.add').click();
            }
        });


    </script>
@endsection





