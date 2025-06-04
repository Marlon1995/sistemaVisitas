@extends('layouts.app')
@section('content')
    @include('/loading')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <h2 class="text-center">Módulo de pisos</h2>
            @include('Pisos.buscar')
            <div class="panel panel-primary">

                <div class="panel-heading">

                    <a href="#" class="add-modal btn btn-primary "> <span class="   glyphicon glyphicon-floppy-saved">Crear</span></a>
                </div>

                <div class="panel-body table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="postTable">
                        <thead>
                        <tr>
                            <th valign="middle">ID</th>
                            <th>Piso</th>
                            <th>Edificio</th>
                            <th>Opciones</th>
                        </tr>
                        {{ csrf_field() }}
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr class="item{{$post->idPiso}}">
                                <td>{{$post->idPiso}}</td>
                                <td>{{$post->Nombre}}</td>
                                <td>{{$post->nombre_edificio}}</td>
                                <td>
                                    <button class="show-modal btn btn-success" data-id="{{$post->idPiso}}"
                                            data-nombre="{{$post->Nombre}}" data-edificio="{{$post->nombre_edificio}}"
                                    >
                                        <span class="glyphicon glyphicon-eye-open"></span> Mostrar
                                    </button>
                                    <button class="edit-modal btn btn-info" data-id="{{$post->idPiso}}"
                                            data-nombre="{{$post->Nombre}}" data-edificio="{{$post->idEdificio}}"
                                            >
                                        <span class="glyphicon glyphicon-edit"></span> Editar
                                    </button>
                                    <button class="delete-modal btn btn-danger" data-id="{{$post->idPiso}}"
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
                </div><!-- /.panel panel-default -->
            </div>
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
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Piso y
                                Seccio: <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" data-validate-length-range="4" name="nombre_add"
                                       id="nombre_add" placeholder="Nombre y sección de piso..." required="required"
                                       type="text">
                                <p class="errorNombre text-center alert alert-danger hidden"></p>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="edificios">Edificio:
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <select name="edificio_add" id="edificio_add" class="form-control selectpicker"
                                        data-live-search="true">
                                    <option value="{{NULL}}">Seleccione un edificio</option>
                                    @foreach($edificios as $post)
                                        <option value="{{$post->idEdificio}}">
                                            {{$post->Nombre}}
                                        </option>
                                    @endforeach
                                </select>

                                <p class="errorEdificio text-center alert alert-danger hidden"></p>
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
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Piso y
                                Seccio: <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" data-validate-length-range="4" name="nombre_show"
                                       id="nombre_show" placeholder="Nombre y sección de piso..." disabled=""
                                       type="text">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Edificio">Edificio:
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" data-validate-length-range="4" name="edificio_show"
                                       id="edificio_show" placeholder="Nombre Edificio..." disabled="" type="text">
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
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Nombres">Piso y
                                Seccio: <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" data-validate-length-range="4" name="nombre_edit"
                                       id="nombre_edit" placeholder="Nombre y sección de piso..."
                                       required="required"
                                       type="text">
                                <p class="errorNombre text-center alert alert-danger hidden"></p>
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Cedula">Edificio:
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <select name="edificio_edit" id="edificio_edit" class="form-control selectpicker"
                                        data-live-search="true">
                                    <option value="{{NULL}}">Seleccione un edificio</option>

                                    @foreach($edificios as $post)
                                        <option value="{{$post->idEdificio}}">
                                            {{$post->Nombre}}
                                        </option>
                                    @endforeach
                                </select>

                                <p class="errorEdificio text-center alert alert-danger hidden"></p>
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
            $('.modal-title').text('CREAR PISO/SECCIÓN');
            $('#addModal').modal('show');
        });
        $('.modal-footer').on('click', '.add', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'POST',
                url: 'pisos',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'txt_nombre': $('#nombre_add').val(),
                    'txt_id_edificio': $('#edificio_add').val()
                },
                success: function (data) {
                    $('.errorNombre').addClass('hidden');
                    $('.errorEdificio').addClass('hidden');
                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#addModal').modal('show');
                            toastr.error('No guardado!' +
                                data.errors.txt_nombre + " \n" +
                                data.errors.errorEdificio + " \n" +
                                " \n", 'Error Alert', {timeOut: 5000});
                        }, 500);

                        if (data.errors.errorEdificio) {
                            $('.errorEdificio').removeClass('hidden');
                            $('.errorEdificio').text(data.errors.errorEdificio);
                        }

                        if (data.errors.txt_nombre) {
                            $('.errorNombre').removeClass('hidden');
                            $('.errorNombre').text(data.errors.txt_nombre);
                        }

                    } else {
                        toastr.success('  Con exito!', 'Success Alert', {timeOut: 5000});
                        $('#postTable').prepend("<tr class=item" + data.idPiso + ">" +
                            "<td>" + data.idPiso + "</td>" +
                            "<td>" + data.Nombre + "</td>" +
                            "<td>" + data.nombre_edificio + "</td>" +
                            "<td>" +
                            "<button class='show-modal btn btn-success'" +
                            "data-id='" + data.idPiso + "'" +
                            " data-nombre='" + data.Nombre + "'" +
                            " data-edificio='" + data.nombre_edificio + "'" +
                            "><span class='glyphicon glyphicon-eye-open'></span> Mostrar</button> " +

                            "<button class='edit-modal btn btn-info'" +
                            "data-id='" + data.idPiso + "'" +
                            " data-nombre='" + data.Nombre + "'" +
                            " data-edificio='" + data.idEdificio + "'" +
                            "><span class='glyphicon glyphicon-edit'></span> Editar</button> " +

                            "<button class='delete-modal btn btn-danger'" +
                            "data-id='" + data.idPiso + "'" +
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
            $('.modal-title').text('MOSTAR PISO/SECCIÓN');
            $('#id_show').val($(this).data('id'));
            $('#nombre_show').val($(this).data('nombre'));
            $('#edificio_show').val($(this).data('edificio'));
            $('#showModal').modal('show');
        });


        // Edit a post
        $(document).on('click', '.edit-modal', function () {
            $('.modal-title').text('ACTUALIZAR PISO/SECCIÓN');
            $('#id_edit').val($(this).data('id'));
            $('#nombre_edit').val($(this).data('nombre'));
            $('#edificio_edit').val($(this).data('edificio'));
            id = $('#id_edit').val();
            $('#editModal').modal('show');
            $('select[name=edificio_edit]').val($(this).data('edificio'));
            $('.selectpicker').selectpicker('refresh');
        });
        $('.modal-footer').on('click', '.edit', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'PUT',
                url: 'pisos/' + id,
                data: {
                    '_token': $('input[name=_token]').val(),
                    'id': $("#id_edit").val(),
                    'txt_nombre': $('#nombre_edit').val(),
                    'txt_id_edificio': $('#edificio_edit').val(),
                },
                success: function (data) {
                    $('.errorNombre').addClass('hidden');
                    $('.errorEdificio').addClass('hidden');

                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#editModal').modal('show');
                            toastr.error('No guardado!' +
                                data.errors.txt_nombre + " \n" +
                                data.errors.errorEdificio + " \n" +" \n", 'Error Alert', {timeOut: 5000});
                        }, 500);

                        if (data.errors.errorEdificio) {
                            $('.errorEdificio').removeClass('hidden');
                            $('.errorEdificio').text(data.errors.errorEdificio);
                        }

                        if (data.errors.txt_nombre) {
                            $('.errorNombre').removeClass('hidden');
                            $('.errorNombre').text(data.errors.txt_nombre);
                        }

                    } else {
                        toastr.success('Actualizado con exito!', 'Satisfactorio', {timeOut: 5000});
                        $('.item' + data.idPiso).replaceWith("<tr class=item" + data.idPiso + ">" +
                            "<td>" + data.idPiso + "</td>" +
                            "<td>" + data.Nombre + "</td>" +
                            "<td>" + data.nombre_edificio + "</td>" +

                            "<td>" +
                            "<button class='show-modal btn btn-success'" +
                            "data-id='" + data.idPiso + "'" +
                            " data-nombre='" + data.Nombre + "'" +
                            " data-edificio='" + data.nombre_edificio + "'" +
                            "><span class='glyphicon glyphicon-eye-open'></span> Mostrar</button> " +

                            "<button class='edit-modal btn btn-info'" +
                            "data-id='" + data.idPiso + "'" +
                            " data-nombre='" + data.Nombre + "'" +
                            " data-edificio='" + data.idEdificio + "'" +
                            "><span class='glyphicon glyphicon-edit'></span> Editar</button> " +

                            "<button class='delete-modal btn btn-danger'" +
                            "data-id='" + data.idPiso + "'" +
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
            $('.modal-title').text('ANULAR PISO/SECCIÓN');
            $('#id_delete').val($(this).data('id'));
            $('#nombre_delete').val($(this).data('nombre'));
            $('#deleteModal').modal('show');
            id = $('#id_delete').val();
        });
        $('.modal-footer').on('click', '.delete', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'DELETE',
                url: 'pisos/' + id,
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
