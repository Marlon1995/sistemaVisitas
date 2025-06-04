@if($tipo==2)
    {!! Form::open(array('url'=>'visitas_edificios/'.$Edificio,'method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}

@else
    {!! Form::open(array('url'=>'visitas_edificios_dueno/'.$Edificio,'method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
@endif
<div class="row" style="margin-bottom: 15px;">

    <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4  ">
        <label class="form-label">Desde</label>
        <input type="date" class="form-control" id="searchText" name="searchText" placeholder="Buscar..."
               value="{{$searchText}}">
    </div>
    <div class="col-md-5 col-sm-5 col-xs-12 col-lg-5  ">

        <label class="form-label">Hasta</label>
        <input type="date" class="form-control" id="searchText1" name="searchText1" placeholder="Buscar..."
               value="{{$searchText1}}">

    </div>
    <div class="col-md-9 col-sm-9 col-xs-12 col-lg-9">
        <br>
        <select name="searchText2" id="searchText2" class="form-control selectpicker" value="{{$searchText2}}"
                data-live-search="true">
            <option value="">Seleccione una organizacion</option>
            @foreach($organizaciones as $post)
                <option value="{{$post->idOrganizacion}}">
                    {{$post->Nombre}}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-12 col-lg-3" style=" float: right; ">
        <br>
        <button type="submit" class="btn btn-primary"><span class="fa fa-search"></span> Buscar</button>
        <a class="btn btn-warning"
           onclick="event.preventDefault(); document.getElementById('descargar_fecha').submit();">
                                        <span
                                                class="glyphicon glyphicon-eye-open"></span> Descargar
        </a>
    </div>
</div>
{{Form::close()}}
<form id="descargar_fecha" action="{{ url('reporte_documento') }}" method="POST" target="_blank"
>
    <input class="hidden" id="edificio" name="edificio" value="{{$Edificio}}">
    <input class="hidden" id="desde" name="desde">
    <input class="hidden" id="hasta" name="hasta">
    <input class="hidden" id="organizacion" name="organizacion">
    {{ csrf_field() }}
</form>
<script>
    $("#searchText").change(function () {
        $("#desde").val($("#searchText").val());

    });
    $("#searchText1").change(function () {
        $("#hasta").val($("#searchText1").val());
    });
    $("#searchText2").change(function () {
        $("#organizacion").val($("#searchText2").val());
    });

    $('select[name=searchText2]').val('{{$searchText2}}');
    $('.selectpicker').selectpicker('refresh');

</script>
