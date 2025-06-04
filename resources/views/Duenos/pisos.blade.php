@extends('layouts.app')
@section('content')
    @include('/loading')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <h2 class="text-center">Módulo de pisos</h2>
            @include('Duenos.buscar_piso')

            <div class="panel panel-primary">

                <div class="panel-heading">Pisos</div>

                <div class="panel-body table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="postTable">
                        <thead>
                        <tr>
                            <th valign="middle">ID</th>
                            <th>Piso</th>
                            <th>Edificio</th>
                            <th>Impresora</th>
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
                                <td>{{$post->nombre_impresora}}</td>
                                <td>
                                    @if($post->Estado==0)
                                        <h4><span class="label label-danger">Anulado</span></h4>
                                    @else
                                        <h4><span class="label label-success">Activo</span></h4>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{url('organizaciones_dueno').'/'.$post->idPiso}}">
                                        <button class="btn btn-primary"><span
                                                    class="glyphicon glyphicon-eye-open"></span> Organizaciones
                                        </button>
                                    </a>
                                    <button class="edit-modal btn btn-info" data-id="{{$post->idPiso}}"
                                            data-nombre="{{$post->Nombre}}" data-edificio="{{$post->idEdificio}}"  data-impresora="{{$post->idImpresora}}">
                                        <span class="glyphicon glyphicon-edit"></span> Editar
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
                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Impresora">Impresora:
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <select name="impresora_edit" id="impresora_edit" class="form-control selectpicker"
                                        data-live-search="true">
                                    <option value="{{NULL}}">Seleccione una impresora</option>
                                    @foreach($impresoras as $pos1t)
                                        <option value="{{$pos1t->idImpresora}}">
                                            {{$pos1t->Nombre}}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="errorImpresora text-center alert alert-danger hidden"></p>
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
            $('.modal-title').text('ACTUALIZAR PISO/SECCIÓN');
            $('#id_edit').val($(this).data('id'));
            $('#nombre_edit').val($(this).data('nombre'));
            $('#edificio_edit').val($(this).data('edificio'));
            $('#impresora_edit').val($(this).data('impresora'));
            id = $('#id_edit').val();
            $('#editModal').modal('show');
            $('select[name=edificio_edit]').val($(this).data('edificio'));
            $('select[name=impresora_edit]').val($(this).data('impresora'));
            $('.selectpicker').selectpicker('refresh');
        });
        $('.modal-footer').on('click', '.edit', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'POST',
                url: "{{url('pisos_duenos')}}" + "/" + id,
                data: {
                    '_token': $('input[name=_token]').val(),
                    'id': $("#id_edit").val(),
                    'txt_nombre': $('#nombre_edit').val(),
                    'txt_id_edificio': $('#edificio_edit').val(),
                    'txt_id_impresora': $('#impresora_edit').val()
                },
                success: function (data) {
                    $('.errorNombre').addClass('hidden');
                    $('.errorEdificio').addClass('hidden');
                    $('.errorImpresora').addClass('hidden');

                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#editModal').modal('show');
                            toastr.error('No guardado!' +
                                data.errors.txt_nombre + " \n" +
                                data.errors.errorEdificio + " \n"+
                                data.errors.errorImpresora + " \n", 'Error Alert', {timeOut: 5000});
                        }, 500);

                        if (data.errors.errorEdificio) {
                            $('.errorEdificio').removeClass('hidden');
                            $('.errorEdificio').text(data.errors.errorEdificio);
                        }

                        if (data.errors.txt_id_impresora) {
                            $('.errorImpresora').removeClass('hidden');
                            $('.errorImpresora').text(data.errors.txt_id_impresora);
                        }

                        if (data.errors.txt_nombre) {
                            $('.errorNombre').removeClass('hidden');
                            $('.errorNombre').text(data.errors.txt_nombre);
                        }

                    } else {
                        toastr.success('Actualizado con exito!', 'Satisfactorio', {timeOut: 5000});


                    }
                    $(".loader").fadeOut("slow");

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $(".loader").fadeOut("slow");
                    toastr.error('No guardado!' + "Error 500", 'Error Alert', {timeOut: 5000});
                    setTimeout(location.reload(), 5000);
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
