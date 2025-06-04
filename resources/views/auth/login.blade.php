<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb18030">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Control de visitas</title>
    <script src="{{asset('/public/login/js/jQuery-2.1.4.min.js')}}" type="text/javascript"></script>
    <link href="{{asset('/public/login/css/bootstrap.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('/public/favicon.png')}}" rel="shortcut icon" type="image/png"/>

</head>
<style>
    @font-face {
        font-family: Fuentechida;
    }

    body {
        background-image: url("{{asset('/public/login/fondo.jpg')}}");
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }

    .formulario {
        transition: 2s;
        margin-top: 50px;
        width: 30%;
    }


    .logo {
        height: 100px;
        margin-top: 5px;
    }

    .espaciado {
        margin-top: 5px;
    }

    fieldset {
        transition: 2s;
        margin-bottom: 50px;
        border-color: #01C9F1;
        border-style: groove;
        border-width: 5px;
        border-radius: 20px;
        border-top: none;
        box-shadow: 5px 0px 0px rgba(1, 201, 241, 1), -5px 0px 0px rgba(1, 201, 241, 1), 0px 5px 0px rgba(1, 201, 241, 1);

    }

    a {
        color: black;
    }

    label {
        color: black;
    }

    h1, h2, h3, h4 {

        color: black;
        text-align: center;
        font-family: "Times New Roman", Times, serif;
    }

    .Input {
        transition: .8s;
        background-color: rgba(0, 0, 0, .5);
        color: white;
        border-color: #006;
        border-bottom-color: white;
        border-bottom-style: groove;
        border-left: none;
        border-right: none;
        border-top: none;
        border-width: 4px;

    }

    .Input:hover {
        transition: .8s;
        background-color: rgba(55, 71, 79, .5);
        box-shadow: inset;
        border-bottom-color: #01C9F1;
    }

    .Input:focus {
        transition: .8s;
        border-bottom-color: #01C9F1;
    }


    @media screen and (max-width: 750px) {

        .logo {
            height: 50px;
            display: none;
        }

        .formulario {
            transition: 2s;
            width: 95%;
            margin-top: 10px;
        }
    }
</style>


<body>
@include('loading')
<div class="container formulario">
    <div class="row">
        <fieldset class="col-xs-10 col-xs-offset-1">


            <legend class="hidden-xs">
                <h3>Control de visitas</h3>
            </legend>

            <form method="POST" action="login" aria-label="{{ __('Login') }}">
                {!! csrf_field() !!}

                <div class="col-md-12">
                    @include('flash::message')
                </div>
                <div class="form-group row">
                    <label for="username" pattern="[a-z][0-9][.][a-z][0-9]"
                           class="col-sm-4 col-form-label text-md-right">{{ __('Usuario') }}</label>

                    <div class="col-md-6">
                        <input id="username" type="username"
                               class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username"
                               required autofocus>

                        @if ($errors->has('username'))
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>


                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password"
                               class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                               required>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                   value="{{ old('remember') ? 'checked' : '' }}">

                            <label class="form-check-label" for="remember">
                                Recordar
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary" id="enviar">
                            Ingresar
                        </button>


                    </div>
                </div>
            </form>
        </fieldset>

    </div>
</div>
<script type="text/javascript">
    $(window).load(function () {
        $(".loader").fadeOut("slow");
    });

    $("#enviar").click(function () {

      let user=  $('#username').val();
      let pass=$('#password').val();

        if(user.toString().length  >0 && pass.length >0){
                        $(".loader").fadeIn("slow");



        }
    });
</script>
</body>

</html>
