@extends('layouts.app')
@section('content')
    @include('/loading')
    <div class="row">
        <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">

            <div class="alert alert-success">
                <ul>
                    <li>SUPER USUARIO</li>
                </ul>
            </div>

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
                                CAMBIO DE CONTRASEÑA DE USUARIOS</h2>

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

                    <form accept="{{url('cambiar_admin').'/'.$Usuario->id}}" method="post">
                        {{ csrf_field() }}
                        @if($Usuario->id == 1)
                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                <div class="form-group">
                                    <label for="dato">Nombres</label>
                                    <input type="text" name="nombre" class="form-control" disabled=""
                                           value="{{$Usuario->name}}">
                                </div>
                            </div>
                        @else
                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                <div class="form-group">
                                    <label for="dato">Nombres</label>
                                    <input type="text" name="nombre" class="form-control" required
                                           value="{{$Usuario->name}}">
                                </div>
                            </div>
                        @endif


                        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="dato">Contraseña Nueva</label>
                                <input type="password" name="nueva" class="form-control" required
                                       pattern="^(?=(?:.*\d){1})(?=(?:.*[A-Z]){1})(?=(?:.*[a-z]){1})\S{8,}$"
                                       title="La contraseña debe tener al menos 1 mayusculas, 1 minusculas, 1 numeros, 1 caracteres especiales y minimo 8 caracteres">
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="dato">Impresora</label>
                                <select name="impresora_add" id="impresora_add" class="form-control selectpicker"
                                        data-live-search="true" required="true">
                                    <option value="{{NULL}}">Seleccione una impresora</option>
                                    @foreach($impresoras as $pos1t)
                                        <option value="{{$pos1t->idImpresora}}">
                                            {{$pos1t->Nombre}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                            <div class="form-group">
                                <br>
                                <button type="submit" class="btn btn-warning form-control"><span
                                            class="glyphicon glyphicon-edit"></span> Cambiar Contraseña
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
            $('select[name=impresora_add]').val("<?php echo $Usuario->idImpresora ;?>");
            $('.selectpicker').selectpicker('refresh');
    </script>
@endsection
