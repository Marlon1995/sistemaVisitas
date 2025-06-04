{!! Form::open(array('url'=>'getVisitas/'.$organizacion,'method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}

<div class="row" style="margin-bottom: 15px;">

    <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4  ">
        <label class="form-label">Desde</label>
        <input type="date" class="form-control" id="searchText" name="searchText" placeholder="Buscar..."
               value="{{$searchText}}">
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4  ">

        <label class="form-label">Hasta</label>
        <input type="date" class="form-control" id="searchText1" name="searchText1" placeholder="Buscar..."
               value="{{$searchText1}}">
    </div>

    <div class="col-md-3 col-sm-3 col-xs-12 col-lg-3" style="margin-top: 3px;">
        <br>
        <button type="submit" class="btn btn-primary"><span class="fa fa-search"></span> Buscar</button>
        <a class="btn btn-warning"
           onclick="event.preventDefault(); document.getElementById('descargar_fecha').submit();">
                                        <span
                                                class="fa fa-download"></span> Descargar
        </a>

    </div>
</div>
{{Form::close()}}

<form id="descargar_fecha" action="{{ url('reporte_documento') }}" method="POST" target="_blank"
      style="display: none;">
    <input class="hidden" id="organizacion" name="organizacion" value="{{$organizacion}}">
    <input class="hidden" id="desde" name="desde">
    <input class="hidden" id="hasta" name="hasta">
    {{ csrf_field() }}
</form>
<script>
    $("#searchText").change(function () {
        $("#desde").val($("#searchText").val());

    });
    $("#searchText1").change(function () {
        $("#hasta").val($("#searchText1").val());
    });
</script>
