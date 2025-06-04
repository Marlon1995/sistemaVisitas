@extends('layouts.app')
@section('content')
    @include('/loading')

    <div class="row">
        <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
            @if ($Usuario->Estado==0)
                <div class="alert alert-danger">
                    <ul>
                        <li>Usuario Anulado</li>
                    </ul>
                </div>
            @else
                <div class="alert alert-success">
                    <ul> @if($Usuario->id==1)
                            <li>
                                Super Administrador
                            </li>
                        @else
                            <li>
                                Usario Activo
                            </li>

                        @endif</ul>
                </div>
            @endif
        </div>

        <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">

            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-2">
                        <div class="form-group">
                            <img src="{{asset('/public/images/logo.png')}}" alt="Logo"
                                 style="width: 120px;">
                        </div>
                    </div>

                    <div class="col-lg-10 col-sm-10 col-md-10 col-xs-12">
                        <div class="form-group">
                            <h2 style="font-size: 25px;font-weight: bold; margin-left: -200px; text-align: center;">
                                ASIGNACION DUEÑO EDIFICIO</h2>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <hr>

                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <center>
                            <div class="form-group">
                                <h2><span class="label label-primary">{{$Usuario->name}}</span></h2>
                            </div>
                        </center>

                    </div>


                    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                        <div class="form-group">
                            <label for="dato">Email</label>
                            <input type="text" name="NumeroDocumento" value="{{$Usuario->email}}" class="form-control"
                                   disabled="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                        <div class="form-group">
                            <label for="dato">UserName</label>
                            <input type="text" name="NumeroSolicitud" value="{{$Usuario->UserName}}"
                                   class="form-control" disabled="">
                        </div>
                    </div>
                    @if($Usuario->id!=1)
                        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12" style="margin-bottom: 10px;">
                            <a href="#" class="add-modal btn btn-primary form-control "> <span
                                        class="   glyphicon glyphicon-floppy-saved">AGREGAR EDIFICIO</span></a>
                        </div>
                    @endif
                    <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                        <div class="panel panel-info">

                            <div class="panel-heading">Edificios</div>

                            <div class="panel-body table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="postTable">
                                    <thead>
                                    <tr>
                                        <th valign="middle">ID</th>
                                        <th>Edificio</th>
                                        @if($Usuario->id!=1)

                                            <th>Opciones</th>
                                        @endif
                                    </tr>
                                    {{ csrf_field() }}
                                    </thead>
                                    <tbody>
                                    @foreach($Asignados as $post)
                                        <tr class="item{{$post->idDuenoEdificio}}">
                                            <td>{{$post->idDuenoEdificio}}</td>
                                            <td>{{$post->Nombre}}</td>
                                            @if($Usuario->id!=1)

                                                <td>
                                                    <button class="delete-modal btn btn-danger"
                                                            data-id="{{$post->idDuenoEdificio}}"
                                                            data-edificio="{{$post->Nombre}}">
                                                        <span class="glyphicon glyphicon-trash"></span></button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div><!-- /.panel-body -->
                        </div><!-- /.panel panel-default -->
                    </div>

                </div>


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
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="id">Usuario:
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="id_add" id="id_add" type="text"
                                       value="{{$Usuario->id}}" disabled="">
                            </div>
                        </div>

                        <div class="item form-group">
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Edificios">Edificios:
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <select class="form-control selectpicker" data-live-search="true" id="edificio_add"
                                        name="edificio_add">
                                    @foreach($Edificios as $edf)
                                        <option value="{{$edf->idEdificio}}">{{$edf->Nombre}}</option>

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
                            <label class="control-label col-md-3 col-sm-3  col-lg-3 col-xs-12" for="Edificio">Edificio:
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
                                <input class="form-control" name="edificio_delete" id="edificio_delete"
                                       placeholder="Edificio" type="text" disabled>
                                <p class="errorEdificio text-center alert alert-danger hidden"></p>
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
            $('.modal-title').text('AGREGAR EDIFICIOS');
            $('#addModal').modal('show');
        });
        $('.modal-footer').on('click', '.add', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'POST',
                url: '{{url("dueno")}}',
                data: {
                    '_token': $('input[name=_token]').val(),
                    'edificio': $('#edificio_add').val(),
                    'id_usuario': $('#id_add').val()
                },
                success: function (data) {

                    $('.errorEdificio').addClass('hidden');

                    if ((data.errors)) {
                        setTimeout(function () {
                            $('#addModal').modal('show');
                            toastr.error('No guardado!' + data.errors.rol, 'Error Alert', {timeOut: 5000});
                        }, 500);

                        if (data.errors.edificio) {
                            $('.errorEdificio').removeClass('hidden');
                            $('.errorEdificio').text(data.errors.edificio);
                        }


                    } else {
                        toastr.success('  Con exito!', 'Success Alert');

                        $('#postTable').append("<tr class=item" + data.idDuenoEdificio + ">" +
                            "<td>" + data.idDuenoEdificio + "</td>" +
                            "<td>" + data.Nombre + "</td>" +
                            "<td>" +
                            "<button class='delete-modal btn btn-danger' data-id='" + data.idDuenoEdificio + "' data-edificio='" + data.Nombre + "'><span class='glyphicon glyphicon-trash'></span></button> " +
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
            $('.modal-title').text('ANULAR EDIFICIO');
            $('#id_delete').val($(this).data('id'));
            $('#edificio_delete').val($(this).data('edificio'));
            $('#deleteModal').modal('show');
            id = $('#id_delete').val();
        });
        $('.modal-footer').on('click', '.delete', function () {
            $(".loader").fadeIn("slow");
            $.ajax({
                type: 'DELETE',
                url: '{{url("dueno")."/"}}' + id,
                data: {
                    '_token': $('input[name=_token]').val()
                },
                success: function (data) {
                    toastr.success('Quitado con exito!', 'Satisfactorio', {timeOut: 5000});
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

    </script>
@endsection