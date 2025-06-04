{!! Form::open(array('url'=>'usuarios','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<div class="form-group">
    <div class="input-group">
        <input type="text" class="form-control" name="searchText" placeholder="Buscar..." value="{{$searchText}}"
               style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
        <span class="input-group-btn">
			<button type="submit" class="btn btn-primary"><span class="fa fa-search"></span> Buscar</button>
		</span>
    </div>
</div>
{{Form::close()}}
