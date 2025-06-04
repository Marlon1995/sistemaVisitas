@extends('layouts.app')
@section('content')
    @include('/loading')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <h2 class="text-center">Módulo de Organizaciones</h2>
            @include('Duenos.buscar_organizacion')
            <div class="panel panel-primary">

                <div class="panel-heading">Organizaciones</div>

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
                                    <button class="edit-modal btn btn-info" data-id="{{$post->idOrganizacion}}"
                                            data-nombre="{{$post->Nombre}}" data-piso="{{$post->idPiso}}"
                                            data-nombre_encargado="{{$post->nombre_encargado}}"
                                            data-email_encargado="{{$post->email_encargado}}"
                                            data-id_encargado="{{$post->id}}">
                                        <span class="glyphicon glyphicon-edit"></span> Editar
                                    </button>

                                    <a class="btn btn-warning"
                                       onclick="event.preventDefault(); document.getElementById('descargar-{{$post->idOrganizacion}}').submit();">
                                        <span
                                                class="glyphicon glyphicon-eye-open"></span> Descargar
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
    <script>
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
        });
        $('.modal-footer').on('click', '.edit', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'POST',
                url: "{{url('organizaciones_dueno')}}" + "/" + id,
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
                        setTimeout(location.reload(), 5000);

                    }
                 $(".loader").fadeOut("slow");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $(".loader").fadeOut("slow");
                    toastr.error('No guardado!' + "Error 500", 'Error Alert', {timeOut: 5000});
                }
            });
        });

        $("#editModal").on('keyup', function (e) {
            if (e.keyCode == 13) {
                $('.edit').click();
            }
        });
    </script>
@endsection
