@extends('layouts.app')
@section('content')
    @include('/loading')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <h2 class="text-center">Módulo de Organizaciones</h2>
            @include('Organizaciones.buscar')
            <div class="panel panel-primary">

                <div class="panel-heading">

                    <a href="#" class="add-modal btn btn-primary "> <span class="   glyphicon glyphicon-floppy-saved">Crear</span></a>
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
                                    <button class="show-modal btn btn-success" data-id="{{$post->idOrganizacion}}"
                                            data-nombre="{{$post->Nombre}}"
                                            data-piso="{{$post->nombre_edificio.' '.$post->nombre_piso}}"
                                            data-nombre_encargado="{{$post->nombre_encargado}}"
                                            data-email_encargado="{{$post->email_encargado}}">
                                        <span class="glyphicon glyphicon-eye-open"></span> Mostrar
                                    </button>
                                    <button class="edit-modal btn btn-info" data-id="{{$post->idOrganizacion}}"
                                            data-nombre="{{$post->Nombre}}" data-piso="{{$post->idPiso}}"
                                            data-nombre_encargado="{{$post->nombre_encargado}}"
                                            data-email_encargado="{{$post->email_encargado}}"
                                            data-id_encargado="{{$post->id}}">
                                        <span class="glyphicon glyphicon-edit"></span> Editar
                                    </button>
                                    <button class="delete-modal btn btn-danger" data-id="{{$post->idOrganizacion}}"
                                            data-nombre="{{$post->Nombre}}">
                                        <span class="glyphicon glyphicon-trash"></span> Eliminar
                                    </button>

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
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Nombre
                                organización: <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" data-validate-length-range="4" name="nombre_add"
                                       id="nombre_add" placeholder="Nombre de organizacion..." required="required"
                                       type="text">
                                <p class="errorNombre text-center alert alert-danger hidden"></p>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="edificios">Pisos/sección:
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <select name="piso_add" id="piso_add" class="form-control selectpicker"
                                        data-live-search="true">
                                    <option value="{{NULL}}">Seleccione un edificio</option>
                                    @foreach($pisos as $post)
                                        <option value="{{$post->idPiso}}">
                                            {{$post->nombre_edificio.' - '.$post->nombre_piso}}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="errorPiso text-center alert alert-danger hidden"></p>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="edificios">Nombres
                                encargado: <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" data-validate-length-range="4" name="nombre_encargado_add"
                                       id="nombre_encargado_add" placeholder="Nombre de encargado..."
                                       type="text">
                                <p class="errorNombreEncargado text-center alert alert-danger hidden"></p>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="edificios">Email
                                encargado: <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" data-validate-length-range="4" name="email_encargado_add"
                                       id="email_encargado_add" placeholder="Email de encargado..." 
                                       type="text">
                                <p class="errorEmailEncargado text-center alert alert-danger hidden"></p>
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
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="id">ID:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="id_show" id="id_show" type="text" disabled="">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Organizacion:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="nombre_show" id="nombre_show" type="text" disabled="">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Piso/sección:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="piso_show" id="piso_show" type="text" disabled="">
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Nombres
                                encargado:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="nombre_encargado_show" id="nombre_encargado_show"
                                       type="text" disabled="">
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Email
                                Encargadon:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="email_encargado_show" id="email_encargado_show"
                                       type="text" disabled="">
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
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Nombre
                                organización: <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" data-validate-length-range="4" name="nombre_edit"
                                       id="nombre_edit" placeholder="Nombre de organizacion..." required="required"
                                       type="text">
                                <p class="errorNombre text-center alert alert-danger hidden"></p>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="edificios">Pisos/sección:
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <select name="piso_edit" id="piso_edit" class="form-control selectpicker"
                                        data-live-search="true">
                                    <option value="{{NULL}}">Seleccione un edificio</option>

                                    @foreach($pisos as $post)
                                        <option value="{{$post->idPiso}}">
                                            {{$post->nombre_edificio.' - '.$post->nombre_piso}}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="errorPiso text-center alert alert-danger hidden"></p>
                            </div>
                        </div>


                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="edificios">Nombres
                                encargado: <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" data-validate-length-range="4" name="nombre_encargado_edit"
                                       id="nombre_encargado_edit" placeholder="Nombre de encargado..."
                                       required="required" type="text">
                                <p class="errorNombreEncargado text-center alert alert-danger hidden"></p>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="edificios">Email
                                encargado: <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" data-validate-length-range="4" name="email_encargado_edit"
                                       id="email_encargado_edit" placeholder="Email de encargado..." required="required"
                                       type="text">
                                <p class="errorEmailEncargado text-center alert alert-danger hidden"></p>
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
                                <input class="form-control" name="nombre_delete" id="nombre_delete" type="text"
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
            $('.modal-title').text('CREAR ORGANIZACIÓN');
            $('#addModal').modal('show');
        });
        $('.modal-footer').on('click', '.add', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'POST',
                url: 'organizaciones',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'txt_id_piso': $('#piso_add').val(),
                    'txt_nombres_encargado': $('#nombre_encargado_add').val(),
                    'txt_email_encargado': $('#email_encargado_add').val(),
                    'txt_nombre': $('#nombre_add').val()

                },
                success: function (data) {
                    $('.errorNombre').addClass('hidden');
                    $('.errorPiso').addClass('hidden');
                    $('.errorEmailEncargado').addClass('hidden');
                    $('.errorNombreEncargado').addClass('hidden');
                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#addModal').modal('show');
                            toastr.error('No guardado!' +
                                data.errors.txt_nombres_encargado + " \n" +
                                data.errors.txt_email_encargado + " \n" +
                                data.errors.txt_id_piso + " \n" +
                                data.errors.txt_nombre + " \n", 'Error Alert', {timeOut: 5000});
                        }, 500);

                        if (data.errors.txt_nombres_encargado) {
                            $('.errorNombreEncargado').removeClass('hidden');
                            $('.errorNombreEncargado').text(data.errors.txt_nombres_encargado);
                        }

                        if (data.errors.txt_email_encargado) {
                            $('.errorEmailEncargado').removeClass('hidden');
                            $('.errorEmailEncargado').text(data.errors.txt_email_encargado);
                        }


                        if (data.errors.txt_id_piso) {
                            $('.errorPiso').removeClass('hidden');
                            $('.errorPiso').text(data.errors.txt_id_piso);
                        }


                        if (data.errors.txt_nombre) {
                            $('.errorNombre').removeClass('hidden');
                            $('.errorNombre').text(data.errors.txt_nombre);
                        }


                    } else {


                        toastr.success('  Con exito!', 'Success Alert', {timeOut: 5000});
                        $('#postTable').prepend("<tr class=item" + data.idOrganizacion + ">" +
                            "<td>" + data.idOrganizacion + "</td>" +
                            "<td>" + data.Nombre + "</td>" +
                            "<td>" + data.nombre_edificio + "</td>" +
                            "<td>" + data.nombre_piso + "</td>" +
                            "<td>" + data.nombre_encargado + "</td>" +
                            "<td>" + data.email_encargado + "</td>" +
                            "<td>" +
                            "<button class='show-modal btn btn-success'" +
                            "data-id='" + data.idOrganizacion + "'" +
                            " data-piso='" + data.nombre_edificio + " " + data.nombre_piso + "'" +
                            " data-nombre='" + data.Nombre + "'" +
                            " data-nombre_encargado='" + data.nombre_encargado + "'" +
                            " data-email_encargado='" + data.email_encargado + "'" +
                            "><span class='glyphicon glyphicon-eye-open'></span> Mostrar</button> " +

                            "<button class='edit-modal btn btn-info'" +
                            "data-id='" + data.idOrganizacion + "'" +
                            " data-nombre='" + data.Nombre + "'" +
                            " data-piso='" + data.idPiso + "'" +
                            " data-nombre_encargado='" + data.nombre_encargado + "'" +
                            " data-email_encargado='" + data.email_encargado + "'" +
                            " data-id_encargado='" + data.id + "'" +
                            "><span class='glyphicon glyphicon-edit'></span> Editar</button> " +

                            "<button class='delete-modal btn btn-danger'" +
                            "data-id='" + data.idOrganizacion + "'" +
                            " data-nombre='" + data.Nombre + "'" +
                            "><span class='glyphicon glyphicon-trash'></span> Eliminar</button> " +

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
            $('.modal-title').text('MOSTAR EDIFICIO');
            $('#id_show').val($(this).data('id'));
            $('#nombre_show').val($(this).data('nombre'));
            $('#piso_show').val($(this).data('piso'));
            $('#nombre_encargado_show').val($(this).data('nombre_encargado'));
            $('#email_encargado_show').val($(this).data('email_encargado'));
            $('#showModal').modal('show');
        });


        // Edit a post
        $(document).on('click', '.edit-modal', function () {
            $('.modal-title').text('ACTUALIZAR EDIFICIO');
            $('#id_edit').val($(this).data('id'));
            $('#nombre_edit').val($(this).data('nombre'));
            $('#piso_edit').val($(this).data('piso'));
            $('#nombre_encargado_edit').val($(this).data('nombre_encargado'));
            $('#email_encargado_edit').val($(this).data('email_encargado'));
            id = $('#id_edit').val();
            id_usu = $(this).data('id_encargado');
            $('#editModal').modal('show');
            $('select[name=piso_edit]').val($(this).data('piso'));
            $('.selectpicker').selectpicker('refresh');
        });
        $('.modal-footer').on('click', '.edit', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'PUT',
                url: 'organizaciones/' + id,
                data: {
                    '_token': $('input[name=_token]').val(),
                    'id': $("#id_edit").val(),
                    'txt_id_piso': $('#piso_edit').val(),
                    'txt_nombres_encargado': $('#nombre_encargado_edit').val(),
                    'txt_email_encargado': $('#email_encargado_edit').val(),
                    'txt_idusuario': id_usu,
                    'txt_nombre': $('#nombre_edit').val()
                },
                success: function (data) {
                    $('.errorNombre').addClass('hidden');
                    $('.errorPiso').addClass('hidden');
                    $('.errorEmailEncargado').addClass('hidden');
                    $('.errorNombreEncargado').addClass('hidden');
                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#editModal').modal('show');
                            toastr.error('No guardado!' +
                                data.errors.txt_nombres_encargado + " \n" +
                                data.errors.txt_email_encargado + " \n" +
                                data.errors.txt_id_piso + " \n" +
                                data.errors.txt_nombre + " \n", 'Error Alert', {timeOut: 5000});
                        }, 500);


                        if (data.errors.txt_nombres_encargado) {
                            $('.errorNombreEncargado').removeClass('hidden');
                            $('.errorNombreEncargado').text(data.errors.txt_nombres_encargado);
                        }

                        if (data.errors.txt_email_encargado) {
                            $('.errorEmailEncargado').removeClass('hidden');
                            $('.errorEmailEncargado').text(data.errors.txt_email_encargado);
                        }


                        if (data.errors.txt_id_piso) {
                            $('.errorPiso').removeClass('hidden');
                            $('.errorPiso').text(data.errors.txt_id_piso);
                        }


                        if (data.errors.txt_nombre) {
                            $('.errorNombre').removeClass('hidden');
                            $('.errorNombre').text(data.errors.txt_nombre);
                        }


                    } else {
                        toastr.success('Actualizado con exito!', 'Satisfactorio', {timeOut: 5000});
                        $('.item' + data.idOrganizacion).replaceWith("<tr class=item" + data.idOrganizacion + ">" +
                            "<td>" + data.idOrganizacion + "</td>" +
                            "<td>" + data.Nombre + "</td>" +
                            "<td>" + data.nombre_edificio + "</td>" +
                            "<td>" + data.nombre_piso + "</td>" +
                            "<td>" + data.nombre_encargado + "</td>" +
                            "<td>" + data.email_encargado + "</td>" +
                            "<td>" +
                            "<button class='show-modal btn btn-success'" +
                            "data-id='" + data.idOrganizacion + "'" +
                            " data-piso='" + data.nombre_edificio + " " + data.nombre_piso + "'" +
                            " data-nombre='" + data.Nombre + "'" +
                            " data-nombre_encargado='" + data.nombre_encargado + "'" +
                            " data-email_encargado='" + data.email_encargado + "'" +
                            "><span class='glyphicon glyphicon-eye-open'></span> Mostrar</button> " +

                            "<button class='edit-modal btn btn-info'" +
                            "data-id='" + data.idOrganizacion + "'" +
                            " data-nombre='" + data.Nombre + "'" +
                            " data-piso='" + data.idPiso + "'" +
                            " data-nombre_encargado='" + data.nombre_encargado + "'" +
                            " data-email_encargado='" + data.email_encargado + "'" +
                            " data-id_encargado='" + data.id + "'" +
                            "><span class='glyphicon glyphicon-edit'></span> Editar</button> " +

                            "<button class='delete-modal btn btn-danger'" +
                            "data-id='" + data.idOrganizacion + "'" +
                            " data-nombre='" + data.Nombre + "'" +
                            "><span class='glyphicon glyphicon-trash'></span> Eliminar</button> " +

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
            $('.modal-title').text('ANULAR ORGANIZACIÓN');
            $('#id_delete').val($(this).data('id'));
            $('#nombre_delete').val($(this).data('nombre'));
            $('#deleteModal').modal('show');
            id = $('#id_delete').val();
        });
        $('.modal-footer').on('click', '.delete', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'DELETE',
                url: 'organizaciones/' + id,
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
