@extends('layouts.app')
@section('content')
    @include('/loading')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <h2 class="text-center">Listado de visitantes</h2>
            @include('Personas.buscar')
            <div class="panel panel-primary">

                <div class="panel-heading">
                    @foreach($permisos as $permis)
                        @if($permis->idRol==1)
                            <a href="#" class="add-modal btn btn-primary "> <span
                                        class="   glyphicon glyphicon-floppy-saved">Crear Visitante</span></a>
                            <?php break;?>
                        @endif
                    @endforeach
                </div>

                <div class="panel-body table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="postTable">
                        <thead>
                        <tr>
                            <th valign="middle">ID</th>
                            <th>Nombres</th>
                            <th>Cedula</th>
                            <th>Sexo</th>
                            <th>Foto</th>
                            <th>Opciones</th>
                        </tr>
                        {{ csrf_field() }}
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr class="item{{$post->idPersona}}">
                                <td>{{$post->idPersona}}</td>
                                <td>{{$post->Nombres}}</td>
                                <td>{{$post->Cedula}}</td>
                                <td>{{$post->Sexo=='M'?'Hombre':'Mujer'}}</td>
                                @if($post->Sexo=='M')
                                    <td><img src="{{asset('public/avatar_hombre.png')}}" width="80px" height="80px">
                                    </td>
                                @else
                                    <td><img src="{{asset('public/avatar_mujer.png')}}" width="80px" height="80px"></td>
                                @endif

                                <td>
                                    <button class="show-modal btn btn-success" data-id="{{$post->idPersona}}"
                                            data-nombres="{{$post->Nombres}}" data-cedula="{{$post->Cedula}}"
                                            data-direccion="{{$post->Direccion}}"
                                            data-sexo="{{$post->Sexo=='M'?'Hombre':'Mujer'}}"
                                            data-foto="{{$post->Sexo=='M'?'avatar_hombre.png':'avatar_mujer.png'}}">
                                        <span class="glyphicon glyphicon-eye-open"></span> Mostrar
                                    </button>

                                    @foreach($permisos as $permis)
                                        @if($permis->idRol==1)
                                            <button class="edit-modal btn btn-info" data-id="{{$post->idPersona}}"
                                                    data-nombres="{{$post->Nombres}}" data-cedula="{{$post->Cedula}}"
                                                    data-direccion="{{$post->Direccion}}" data-sexo="{{$post->Sexo}}">
                                                <span class="glyphicon glyphicon-edit"></span> Editar
                                            </button>
                                            <button class="delete-modal btn btn-danger" data-id="{{$post->idPersona}}"
                                                    data-nombres="{{$post->Nombres}}">
                                                <span class="glyphicon glyphicon-trash"></span> Eliminar
                                            </button>                    <?php break;?>
                                        @endif

                                    @endforeach
                                    <a class="btn btn-warning"
                                       onclick="event.preventDefault(); document.getElementById('descargar-{{$post->idPersona}}').submit();">
                                        <span
                                                class="fa fa-download"></span> Descargar Reporte
                                    </a>

                                    <form id="descargar-{{$post->idPersona}}" action="{{ url('reporte_documento') }}"
                                          method="POST"
                                          target="_blank"
                                          style="display: none;">
                                        <input class="hidden" name="persona" value="{{$post->idPersona}}">
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
    <!-- Modal form to add a post -->
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
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Nombres:
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" data-validate-length-range="4" name="nombres_add"
                                       id="nombres_add" placeholder="Nombres de persona..." required="required"
                                       type="text">
                                <p class="errorNombres text-center alert alert-danger hidden"></p>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Cedula">Cedula:
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" data-validate-length-range="4" name="cedula_add"
                                       id="cedula_add" placeholder="Nombres de persona..." required="required"
                                       type="text">
                                <p class="errorCedula text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Dirección">Dirección:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="direccion_add" id="direccion_add" type="text"
                                       placeholder="Dirección">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Cedula">Sexo:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <select class="form-control" name="sexo_add" id="sexo_add">
                                    <option value="M">Hombre</option>
                                    <option value="F">Mujer</option>
                                </select>
                            </div>
                        </div>

                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success add" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-check'></span> Guardar
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal form to show a post -->
    <div id="showModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-label-left" novalidate>
                        <div class="item form-group">

                            <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12" align="center">
                                <img src="" id="imagen" onerror="this.src='public/avatar.png'" width="80px"
                                     height="80px">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="id">ID:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="id_show" id="id_show" type="text" disabled="">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Nombres:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="nombres_show" id="nombres_show" type="text"
                                       disabled="">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Cedula">Cedula:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="cedula_show" id="cedula_show" type="text" disabled="">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Dirección">Dirección:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="direccion_show" id="direccion_show" type="text"
                                       disabled="">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Cedula">Sexo:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="sexo_show" id="sexo_show" type="text" disabled="">
                            </div>
                        </div>

                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal form to edit a form -->
    <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-label-left" novalidate>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="id">ID:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="id_edit" id="id_edit" type="text" disabled="">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Nombres:
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" data-validate-length-range="4" name="nombres_edit"
                                       id="nombres_edit" placeholder="Nombres de persona..." required="required"
                                       type="text">
                                <p class="errorNombres text-center alert alert-danger hidden"></p>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Cedula">Cedula:
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" data-validate-length-range="4" name="cedula_edit"
                                       id="cedula_edit" placeholder="Nombres de persona..." required="required"
                                       type="text">
                                <p class="errorCedula text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Dirección">Dirección:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="direccion_edit" id="direccion_edit" type="text"
                                       placeholder="Dirección">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Cedula">Sexo:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <select class="form-control" name="sexo_edit" id="sexo_edit">
                                    <option value="M">Hombre</option>
                                    <option value="F">Mujer</option>
                                </select>
                            </div>
                        </div>


                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary edit" data-dismiss="modal">
                            <span class='glyphicon glyphicon-check'></span> Actualizar
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal form to delete a form -->
    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Seguro deseas eliminar?</h3>
                    <br/>
                    <form class="form-horizontal form-label-left" novalidate>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="id">ID:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="id_delete" id="id_delete" type="text" disabled="">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Nomnbre:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="nombres_delete" id="nombres_delete" type="text"
                                       disabled="">
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger delete" data-dismiss="modal">
                            <span id="" class='glyphicon glyphicon-trash'></span> Eliminar
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
        // add a new post
        $(document).on('click', '.add-modal', function () {
            $('.modal-title').text('CREAR PERSONA');
            $('#addModal').modal('show');
        });
        $('.modal-footer').on('click', '.add', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'POST',
                url: 'personas',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'txt_nombre_persona': $('#nombres_add').val(),
                    'txt_cedula_persona': $('#cedula_add').val(),
                    'txt_direccion_persona': $('#direccion_add').val(),
                    'txt_sexo_persona': $('#sexo_add').val()

                },
                success: function (data) {
                    $('.errorNombres').addClass('hidden');
                    $('.errorCedula').addClass('hidden');
                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#addModal').modal('show');
                            toastr.error('No guardado!' +
                                data.errors.txt_nombre_persona + " \n" +
                                data.errors.txt_cedula_persona + " \n", 'Error Alert', {timeOut: 5000});
                        }, 500);

                        if (data.errors.txt_nombre_persona) {
                            $('.errorNombres').removeClass('hidden');
                            $('.errorNombres').text(data.errors.txt_nombre_persona);
                        }


                        if (data.errors.txt_cedula_persona) {
                            $('.errorCedula').removeClass('hidden');
                            $('.errorCedula').text(data.errors.txt_cedula_persona);
                        }

                    } else {
                        toastr.success('  Con exito!', 'Success Alert', {timeOut: 5000});
                        $('#postTable').prepend("<tr class=item" + data.idPersona + ">" +
                            "<td>" + data.idPersona + "</td>" +
                            "<td>" + data.Nombres + "</td>" +
                            "<td>" + data.Cedula + "</td>" +
                            "<td>" + (data.Sexo == 'M' ? 'Hombre' : 'Mujer') + "</td>" +
                            "<td><img src='" + "{{asset('public')}}" + (data.Sexo == 'M' ? '/avatar_hombre.png' : '/avatar_mujer.png') + "' width='80px' height='80px'></td>" +
                            "<td>" +
                            "<button class='show-modal btn btn-success'" +
                            "data-id='" + data.idPersona + "'" +
                            " data-nombres='" + data.Nombres + "'" +
                            " data-cedula='" + data.Cedula + "'" +
                            " data-sexo='" + (data.Sexo == 'M' ? 'Hombre' : 'Mujer') + "'" +
                            " data-direccion='" + data.Direccion + "'" +
                            " data-foto='" + (data.Sexo == 'M' ? 'avatar_hombre.png' : 'avatar_mujer.png') + "'" +
                            "><span class='glyphicon glyphicon-eye-open'></span> Mostrar</button> " +

                            "<button class='edit-modal btn btn-info'" +
                            "data-id='" + data.idPersona + "'" +
                            " data-nombres='" + data.Nombres + "'" +
                            " data-cedula='" + data.Cedula + "'" +
                            " data-sexo='" + data.Sexo + "'" +
                            " data-direccion='" + data.Direccion + "'" +
                            "><span class='glyphicon glyphicon-edit'></span> Editar</button> " +

                            "<button class='delete-modal btn btn-danger'" +
                            "data-id='" + data.idPersona + "'" +
                            " data-nombres='" + data.Nombres + "'" +
                            "><span class='glyphicon glyphicon-trash'></span> Eliminar</button> " +
                            "<a class='btn btn-warning' onclick='event.preventDefault();document.getElementById(" + '"descargar"' + ").submit();'>" +
                            "<span class='fa fa-download'></span> Descargar</a>" +
                            "<form style='display: none;'target='_blank'id='descargar' method='post' action='" + "{{url('reporte_documento')}}" + "'>" +
                            '<input class="hidden"  name="persona" value="' + data.idPersona + '">' +
                            '{!! csrf_field() !!}' +
                            "</form>" +
                            "</td>" +
                            "</tr>");
                    }
                    $(".loader").fadeOut("slow");

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $(".loader").fadeOut("slow");
                    toastr.error('No guardado!' + "Error 500", 'Error Alert', {timeOut: 5000});
                }
            });
        });

        // Show a post
        $(document).on('click', '.show-modal', function () {
            $('.modal-title').text('MOSTAR PERSONA');
            $('#id_show').val($(this).data('id'));
            $('#nombres_show').val($(this).data('nombres'));
            $('#cedula_show').val($(this).data('cedula'));
            $('#direccion_show').val($(this).data('direccion'));
            $('#sexo_show').val($(this).data('sexo'));
            $('#imagen').attr('src', '{{asset("public")}}' + '/' + $(this).data('foto'));
            $('#showModal').modal('show');
        });


        // Edit a post
        $(document).on('click', '.edit-modal', function () {
            $('.modal-title').text('ACTUALIZAR PERSONA');
            $('#id_edit').val($(this).data('id'));
            $('#nombres_edit').val($(this).data('nombres'));
            $('#cedula_edit').val($(this).data('cedula'));
            $('#direccion_edit').val($(this).data('direccion'));
            $('#sexo_edit').val($(this).data('sexo'));

            id = $('#id_edit').val();
            $('#editModal').modal('show');
        });
        $('.modal-footer').on('click', '.edit', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'PUT',
                url: 'personas/' + id,
                data: {
                    '_token': $('input[name=_token]').val(),
                    'id': $("#id_edit").val(),
                    'txt_nombre_persona': $('#nombres_edit').val(),
                    'txt_cedula_persona': $('#cedula_edit').val(),
                    'txt_direccion_persona': $('#direccion_edit').val(),
                    'txt_sexo_persona': $('#sexo_edit').val()
                },
                success: function (data) {
                    $('.errorNombres').addClass('hidden');
                    $('.errorCedula').addClass('hidden');
                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#editModal').modal('show');
                            toastr.error('No guardado!' +
                                data.errors.txt_nombre_persona + " \n" +
                                data.errors.txt_nombre_persona + " \n", 'Error Alert', {timeOut: 5000});
                        }, 500);

                        if (data.errors.txt_nombre_persona) {
                            $('.errorNombres').removeClass('hidden');
                            $('.errorNombres').text(data.errors.txt_nombre_persona);
                        }


                        if (data.errors.txt_cedula_persona) {
                            $('.errorCedula').removeClass('hidden');
                            $('.errorCedula').text(data.errors.txt_cedula_persona);
                        }


                    } else {
                        toastr.success('Actualizado con exito!', 'Satisfactorio', {timeOut: 5000});
                        $('.item' + data.idPersona).replaceWith("<tr class=item" + data.idPersona + ">" +
                            "<td>" + data.idPersona + "</td>" +
                            "<td>" + data.Nombres + "</td>" +
                            "<td>" + data.Cedula + "</td>" +
                            "<td>" + (data.Sexo == 'M' ? 'Hombre' : 'Mujer') + "</td>" +
                            "<td><img src='" + "{{asset('public')}}" + (data.Sexo == 'M' ? '/avatar_hombre.png' : '/avatar_mujer.png') + "' width='80px' height='80px'></td>" +
                            "<td>" +
                            "<button class='show-modal btn btn-success'" +
                            "data-id='" + data.idPersona + "'" +
                            " data-nombres='" + data.Nombres + "'" +
                            " data-cedula='" + data.Cedula + "'" +
                            " data-sexo='" + (data.Sexo == 'M' ? 'Hombre' : 'Mujer') + "'" +
                            " data-direccion='" + data.Direccion + "'" +
                            " data-foto='" + (data.Sexo == 'M' ? 'avatar_hombre.png' : 'avatar_mujer.png') + "'" +
                            "><span class='glyphicon glyphicon-eye-open'></span> Mostrar</button> " +

                            "<button class='edit-modal btn btn-info'" +
                            "data-id='" + data.idPersona + "'" +
                            " data-nombres='" + data.Nombres + "'" +
                            " data-cedula='" + data.Cedula + "'" +
                            " data-sexo='" + data.Sexo + "'" +
                            " data-direccion='" + data.Direccion + "'" +
                            "><span class='glyphicon glyphicon-edit'></span> Editar</button> " +

                            "<button class='delete-modal btn btn-danger'" +
                            "data-id='" + data.idPersona + "'" +
                            " data-nombres='" + data.Nombres + "'" +
                            "><span class='glyphicon glyphicon-trash'></span> Eliminar</button> " +
                            "<a class='btn btn-warning' onclick='event.preventDefault();document.getElementById(" + '"descargar"' + ").submit();'>" +
                            "<span class='fa fa-download'></span> Descargar</a>" +
                            "<form style='display: none;'target='_blank'id='descargar' method='post' action='" + "{{url('reporte_documento')}}" + "'>" +
                            '<input class="hidden"  name="persona" value="' + data.idPersona + '">' +
                            '{!! csrf_field() !!}' +
                            "</form>" +
                            "</td>" +
                            "</tr>");

                    }
                    $(".loader").fadeOut("slow");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $(".loader").fadeOut("slow");
                    toastr.error('No guardado!' + "Error 500", 'Error Alert', {timeOut: 5000});
                }
            });
        });

        // delete a post
        $(document).on('click', '.delete-modal', function () {
            $('.modal-title').text('ANULAR PERSONA');
            $('#id_delete').val($(this).data('id'));
            $('#nombres_delete').val($(this).data('nombres'));
            $('#deleteModal').modal('show');
            id = $('#id_delete').val();
        });
        $('.modal-footer').on('click', '.delete', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'DELETE',
                url: 'personas/' + id,
                data: {
                    '_token': $('input[name=_token]').val(),
                },
                success: function (data) {
                    toastr.success('Anulado con exito!', 'Satisfactorio', {timeOut: 5000});
                    $('.item' + id).remove();
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

        $("#editModal").on('keyup', function (e) {
            if (e.keyCode == 13) {
                $('.edit').click();
            }
        });
    </script>
@endsection
