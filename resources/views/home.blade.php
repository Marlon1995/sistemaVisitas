@extends('layouts.app')
@section('content')
    @include('/loading')
    <div class="col-md-12 col-lg-12 col-xs-12 col-ms-12" style="background: #3c8dbc;">
        <div class="jumbotron" style="background: #3c8dbc;color: #fff; justify-items: center; display: grid;">
                <img src="{{asset('/public/images/logo_principal.svg')}}" width="500px" class="img-responsive img-rounded">
        </div>
    </div>
@endsection
