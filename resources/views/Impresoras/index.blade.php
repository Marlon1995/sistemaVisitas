@extends('layouts.app')
@section('content')
    @include('/loading')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <h2 class="text-center">Módulo de Impresoras</h2>
            @include('Edificios.buscar')
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
                            <th>Descripción</th>
                            <th>Opciones</th>
                        </tr>
                        {{ csrf_field() }}
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr class="item{{$post->idImpresora}}">
                                <td>{{$post->idImpresora}}</td>
                                <td>{{$post->Nombre}}</td>
                                <td>{{$post->Descripcion}}</td>
                                <td>
                                    <button class="show-modal btn btn-success" data-id="{{$post->idImpresora}}"
                                            data-nombre="{{$post->Nombre}}" data-descripcion="{{$post->Descripcion}}">
                                        <span class="glyphicon glyphicon-eye-open"></span> Mostrar
                                    </button>
                                    <button class="edit-modal btn btn-info" data-id="{{$post->idImpresora}}"
                                            data-nombre="{{$post->Nombre}}" data-descripcion="{{$post->Descripcion}}">
                                        <span class="glyphicon glyphicon-edit"></span> Editar
                                    </button>
                                    <button class="delete-modal btn btn-danger" data-id="{{$post->idImpresora}}"
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
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Nombre:
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" data-validate-length-range="4" name="nombre_add"
                                       id="nombre_add" placeholder="Nombre de impresora..." required="required"
                                       type="text">
                                <p class="errorNombre text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Descripción:
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <textarea class="form-control" name="descripcion_add"
                                          id="descripcion_add" placeholder="Descripción de impresora..."
                                          required="required"></textarea>
                                <p class="errorDescripcion text-center alert alert-danger hidden"></p>
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
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Nombre:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="nombre_show" id="nombre_show" type="text" disabled="">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Descripción:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <textarea class="form-control" name="descripcion_show"
                                          id="descripcion_show" placeholder="Descripción de impresora..." disabled="">
                                </textarea>
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
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Nombre:
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" data-validate-length-range="4" name="nombres_edit"
                                       id="nombre_edit" placeholder="Nombre de edificio..." required="required"
                                       type="text">
                                <p class="errorNombre text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Descripción:
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <textarea class="form-control" name="descripcion_edit"
                                          id="descripcion_edit" placeholder="Descripción de impresora..."
                                          required="required">
                                </textarea>
                                <p class="errorDescripcion text-center alert alert-danger hidden"></p>
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
            $('.modal-title').text('CREAR IMPRESORA');
            $('#addModal').modal('show');
        });
        $('.modal-footer').on('click', '.add', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'POST',
                url: 'impresoras',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'txt_descripcion_impresora': $('#descripcion_add').val(),
                    'txt_nombre_impresora': $('#nombre_add').val()

                },
                success: function (data) {
                    $('.errorNombre').addClass('hidden');
                    $('.errorDescripcion').addClass('hidden');
                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#addModal').modal('show');
                            toastr.error('No guardado!' +
                                data.errors.txt_nombre_impresora + " \n" +
                                data.errors.txt_descripcion_impresora + " \n", 'Error Alert', {timeOut: 5000});
                        }, 500);

                        if (data.errors.txt_nombre_impresora) {
                            $('.errorNombre').removeClass('hidden');
                            $('.errorNombre').text(data.errors.txt_nombre_impresora);
                        }
                        if (data.errors.txt_descripcion_impresora) {
                            $('.errorDescripcion').removeClass('hidden');
                            $('.errorDescripcion').text(data.errors.txt_descripcion_impresora);
                        }

                    } else {
                        toastr.success('  Con exito!', 'Success Alert', {timeOut: 5000});
                        $('#postTable').append("<tr class=item" + data.idImpresora + ">" +
                            "<td>" + data.idImpresora + "</td>" +
                            "<td>" + data.Nombre + "</td>" +
                            "<td>" + data.Descripcion + "</td>" +
                            "<td>" +
                            "<button class='show-modal btn btn-success'" +
                            "data-id='" + data.idImpresora + "'" +
                            " data-nombre='" + data.Nombre + "'" +
                            " data-descripcion='" + data.Descripcion + "'" +
                            "><span class='glyphicon glyphicon-eye-open'></span> Mostrar</button> " +

                            "<button class='edit-modal btn btn-info'" +
                            "data-id='" + data.idImpresora + "'" +
                            " data-nombre='" + data.Nombre + "'" +
                            " data-descripcion='" + data.Descripcion + "'" +
                            "><span class='glyphicon glyphicon-edit'></span> Editar</button> " +

                            "<button class='delete-modal btn btn-danger'" +
                            "data-id='" + data.idImpresora + "'" +
                            " data-nombre='" + data.Nombre + "'" +
                            "><span class='glyphicon glyphicon-trash'></span> Eliminar</button> " +
                            "</td>" +
                            "</tr>");


                    }
                    $(".loader").fadeOut("slow");
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $(".loader").fadeOut("slow");
                    toastr.error('No guardado!' + "Error 500", 'Error Alert', {timeOut: 5000});
                }
            });
        });

        // Show a post
        $(document).on('click', '.show-modal', function () {
            $('.modal-title').text('MOSTAR IMPRESORA');
            $('#id_show').val($(this).data('id'));
            $('#nombre_show').val($(this).data('nombre'));
            $('#descripcion_show').val($(this).data('descripcion'));
            $('#showModal').modal('show');
        });


        // Edit a post
        $(document).on('click', '.edit-modal', function () {
            $('.modal-title').text('ACTUALIZAR IMPRESORA');
            $('#id_edit').val($(this).data('id'));
            $('#nombre_edit').val($(this).data('nombre'));
            $('#descripcion_edit').val($(this).data('descripcion'));
            id = $('#id_edit').val();
            $('#editModal').modal('show');
        });
        $('.modal-footer').on('click', '.edit', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'PUT',
                url: 'impresoras/' + id,
                data: {
                    '_token': $('input[name=_token]').val(),
                    'id': $("#id_edit").val(),
                    'txt_descripcion_impresora': $('#descripcion_edit').val(),
                    'txt_nombre_impresora': $('#nombre_edit').val()
                },
                success: function (data) {
                    $('.errorNombre').addClass('hidden');
                    $('.errorDescripcion').addClass('hidden');
                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#editModal').modal('show');
                            toastr.error('No guardado!' +
                                data.errors.txt_nombre_impresora + " \n" +
                                data.errors.txt_descripcion_impresora + " \n", 'Error Alert', {timeOut: 5000});
                        }, 500)

                        if (data.errors.txt_nombre_impresora) {
                            $('.errorNombre').removeClass('hidden');
                            $('.errorNombre').text(data.errors.txt_nombre_impresora);
                        }
                        if (data.errors.txt_descripcion_impresora) {
                            $('.errorDescripcion').removeClass('hidden');
                            $('.errorDescripcion').text(data.errors.txt_descripcion_impresora);
                        }
                    } else {
                        toastr.success('Actualizado con exito!', 'Satisfactorio', {timeOut: 5000});
                        $('.item' + data.idImpresora).replaceWith("<tr class=item" + data.idImpresora + ">" +
                            "<td>" + data.idImpresora + "</td>" +
                            "<td>" + data.Nombre + "</td>" +
                            "<td>" + data.Descripcion + "</td>" +
                            "<td>" +
                            "<button class='show-modal btn btn-success'" +
                            "data-id='" + data.idImpresora + "'" +
                            " data-nombre='" + data.Nombre + "'" +
                            " data-descripcion='" + data.Descripcion + "'" +
                            "><span class='glyphicon glyphicon-eye-open'></span> Mostrar</button> " +

                            "<button class='edit-modal btn btn-info'" +
                            "data-id='" + data.idImpresora + "'" +
                            " data-nombre='" + data.Nombre + "'" +
                            " data-descripcion='" + data.Descripcion + "'" +
                            "><span class='glyphicon glyphicon-edit'></span> Editar</button> " +

                            "<button class='delete-modal btn btn-danger'" +
                            "data-id='" + data.idImpresora + "'" +
                            " data-nombre='" + data.Nombre + "'" +
                            "><span class='glyphicon glyphicon-trash'></span> Eliminar</button> " +
                            "</td>" +
                            "</tr>");
                    }
                    $(".loader").fadeOut("slow");
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $(".loader").fadeOut("slow");
                    toastr.error('No guardado!' + "Error 500", 'Error Alert', {timeOut: 5000});
                }
            });
        });
        // delete a post
        $(document).on('click', '.delete-modal', function () {
            $('.modal-title').text('ANULAR IMPRESORA');
            $('#id_delete').val($(this).data('id'));
            $('#nombre_delete').val($(this).data('nombre'));
            $('#deleteModal').modal('show');
            id = $('#id_delete').val();
        });
        $('.modal-footer').on('click', '.delete', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'DELETE',
                url: 'impresoras/' + id,
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
