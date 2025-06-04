@if($tipo==2)
    {!! Form::open(array('url'=>'mostrar_visita/'.$idvisita,'method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}

@else
    {!! Form::open(array('url'=>'mostrar_visita_dueno/'.$idvisita,'method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
@endif
<div class="form-group">
    <div class="input-group">
        <input type="text" class="form-control" id="searchText" name="searchText" placeholder="Buscar..."
               value="{{$searchText}}"
               onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()">
        <span class="input-group-btn">
			<button type="submit" class="btn btn-primary"><span class="fa fa-search"></span> Buscar</button>
		</span>
    </div>
</div>

{{Form::close()}}
