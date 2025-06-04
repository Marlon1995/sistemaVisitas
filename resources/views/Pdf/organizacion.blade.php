<!DOCTYPE html>
<html>
<head>
    <title>Reportes</title>
    <style>

        body {
            width: 1000px;
            font-family: 'trebuchet MS', 'Lucida sans', Arial;
            font-size: 10px;
            color: #444;
        }

        table {
            *border-collapse: collapse; /* IE7 and lower */
            border-spacing: 0;
            width: 700px;

        }

        .bordered {
            border: solid #ccc 1px;
            -moz-border-radius: 6px;
            -webkit-border-radius: 6px;
            border-radius: 6px;
            -webkit-box-shadow: 0 1px 1px #ccc;
            -moz-box-shadow: 0 1px 1px #ccc;
            box-shadow: 0 1px 1px #ccc;
        }

        .bordered tr:hover {
            background: #fbf8e9;
            -o-transition: all 0.1s ease-in-out;
            -webkit-transition: all 0.1s ease-in-out;
            -moz-transition: all 0.1s ease-in-out;
            -ms-transition: all 0.1s ease-in-out;
            transition: all 0.1s ease-in-out;
        }

        .bordered td, .bordered th {
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        .bordered th {
            background-color: #dce9f9;
            background-image: -webkit-gradient(linear, left top, left bottom, from(#ebf3fc), to(#dce9f9));
            background-image: -webkit-linear-gradient(top, #ebf3fc, #dce9f9);
            background-image: -moz-linear-gradient(top, #ebf3fc, #dce9f9);
            background-image: -ms-linear-gradient(top, #ebf3fc, #dce9f9);
            background-image: -o-linear-gradient(top, #ebf3fc, #dce9f9);
            background-image: linear-gradient(top, #ebf3fc, #dce9f9);
            -webkit-box-shadow: 0 1px 0 rgba(255, 255, 255, .8) inset;
            -moz-box-shadow: 0 1px 0 rgba(255, 255, 255, .8) inset;
            box-shadow: 0 1px 0 rgba(255, 255, 255, .8) inset;
            border-top: none;
            text-shadow: 0 1px 0 rgba(255, 255, 255, .5);
        }

        .bordered td:first-child, .bordered th:first-child {
            border-left: none;
        }

        .bordered th:first-child {
            -moz-border-radius: 6px 0 0 0;
            -webkit-border-radius: 6px 0 0 0;
            border-radius: 6px 0 0 0;
        }

        .bordered th:last-child {
            -moz-border-radius: 0 6px 0 0;
            -webkit-border-radius: 0 6px 0 0;
            border-radius: 0 6px 0 0;
        }

        .bordered th:only-child {
            -moz-border-radius: 6px 6px 0 0;
            -webkit-border-radius: 6px 6px 0 0;
            border-radius: 6px 6px 0 0;
        }

        .bordered tr:last-child td:first-child {
            -moz-border-radius: 0 0 0 6px;
            -webkit-border-radius: 0 0 0 6px;
            border-radius: 0 0 0 6px;
        }

        .bordered tr:last-child td:last-child {
            -moz-border-radius: 0 0 6px 0;
            -webkit-border-radius: 0 0 6px 0;
            border-radius: 0 0 6px 0;
        }


        /*----------------------*/

        .zebra td, .zebra th {
            padding: 10px;
            border-bottom: 1px solid #f2f2f2;
        }

        .zebra tbody tr:nth-child(even) {
            background: #f5f5f5;
            -webkit-box-shadow: 0 1px 0 rgba(255, 255, 255, .8) inset;
            -moz-box-shadow: 0 1px 0 rgba(255, 255, 255, .8) inset;
            box-shadow: 0 1px 0 rgba(255, 255, 255, .8) inset;
        }

        .zebra th {
            text-align: left;
            text-shadow: 0 1px 0 rgba(255, 255, 255, .5);
            border-bottom: 1px solid #ccc;
            background-color: #eee;
            background-image: -webkit-gradient(linear, left top, left bottom, from(#f5f5f5), to(#eee));
            background-image: -webkit-linear-gradient(top, #f5f5f5, #eee);
            background-image: -moz-linear-gradient(top, #f5f5f5, #eee);
            background-image: -ms-linear-gradient(top, #f5f5f5, #eee);
            background-image: -o-linear-gradient(top, #f5f5f5, #eee);
            background-image: linear-gradient(top, #f5f5f5, #eee);
        }

        .zebra th:first-child {
            -moz-border-radius: 6px 0 0 0;
            -webkit-border-radius: 6px 0 0 0;
            border-radius: 6px 0 0 0;
        }

        .zebra th:last-child {
            -moz-border-radius: 0 6px 0 0;
            -webkit-border-radius: 0 6px 0 0;
            border-radius: 0 6px 0 0;
        }

        .zebra th:only-child {
            -moz-border-radius: 6px 6px 0 0;
            -webkit-border-radius: 6px 6px 0 0;
            border-radius: 6px 6px 0 0;
        }

        .zebra tfoot td {
            border-bottom: 0;
            border-top: 1px solid #fff;
            background-color: #f1f1f1;
        }

        .zebra tfoot td:first-child {
            -moz-border-radius: 0 0 0 6px;
            -webkit-border-radius: 0 0 0 6px;
            border-radius: 0 0 0 6px;
        }

        .zebra tfoot td:last-child {
            -moz-border-radius: 0 0 6px 0;
            -webkit-border-radius: 0 0 6px 0;
            border-radius: 0 0 6px 0;
        }

        .zebra tfoot td:only-child {
            -moz-border-radius: 0 0 6px 6px;
            -webkit-border-radius: 0 0 6px 6px
            border-radius: 0 0 6px 6px
        }

        #footer {
            position: fixed;
            left: 0px;
            bottom: -100px;
            right: 0px;
            height: 150px;
        }

        #footer .page:after {
            content: counter(page, upper-roman);
        }

    </style>
</head>

<body>
<table>
    <thead>
    <tr>
        <td style="width: 50px;"><img width="100px" height="50px" src="{{asset('public/images/logo_principal.png')}}">
        </td>
        <td style="text-align: center;"><h2>Sistema Control de visitas</h2>
            <h3>{{$formato==0?'Reportes':'Reporte por usuario'}}</h3>
        <h5>Generado por: <b>{{$usuario->name}}</b></h5></td>

        <td style="width:100px;text-align: center;">Qutio, {{$date}}</td>
    </tr>
    </thead>
</table>
<br>


@if($formato==0)
    <table class="bordered">
        <thead>
        <tr>
            <th># ticket</th>
            <th>Atendido por</th>
            <th>Edificio</th>
            <th>Piso</th>
            <th>Organización</th>
            <th>Código Tarjeta</th>
            <th>Nombres</th>
            <th>Cedula</th>
            <th>Foto</th>
            <th>Fecha</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $dat)
            <tr>
                <td>{{$dat->idAcceso}}</td>
                <td>{{$dat->NombreAtendido}}</td>
                <td>{{$dat->nombre_edificio}}</td>
                <td>{{$dat->nombre_piso}}</td>
                <td>{{$dat->nombre_organizacion}}</td>
                <td>{{$dat->CodigoTarjeta}}</td>
                <td>{{$dat->Nombres}}</td>
                <td>{{$dat->Cedula}}</td>
                @if($dat->Sexo=='M')
                    <td><img src="{{asset('public/avatar_hombre.png')}}" width="30px" height="30px"></td>
                @else
                    <td><img src="{{asset('public/avatar_mujer.png')}}" width="30px" height="30px"></td>
                @endif
                <td>{{$dat->Creacion}}</td>
            </tr>
            ?>
        @endforeach
        </tbody>
    </table>
@else
    <table style="background-color: #dce9f9;">
        <thead>
        <tr>
            <th style="text-align: center; margin: 0; padding: 0; justify-items: center;">
                <h3>{{(count($data)>0)?$data[0]->Nombres:''}}</h3>
                <br>
                @if($data[0]->Sexo=='M')
                    <img  style="margin-top: -5px;" src="{{asset('public/avatar_hombre.png')}}" width="30px">
                @else
                    <img  style="margin-top: -5px;" src="{{asset('public/avatar_mujer.png')}}" width="30px">
                @endif
                <h3 style="margin-top: -5px;">{{(count($data)>0)?($data[0]->Sexo=='M'?'Hombre':'Mujer'):''}}</h3>
            </th>
            <th style="text-align: center; margin: 0; padding: 0;">
                <h3>{{(count($data)>0)?$data[0]->Cedula:''}}</h3>
            </th>
        </tr>
        </tr>
        </thead>
    </table>
    <br>
    <table class="bordered">
        <thead>
        <tr>
            <th># ticket</th>
            <th>Atendido por</th>
            <th>Edificio</th>
            <th>Piso</th>
            <th>Organización</th>
            <th>Código Tarjeta</th>
            <th>Fecha</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $dat)
            <tr>
                <td>{{$dat->idAcceso}}</td>
                <td>{{$dat->NombreAtendido}}</td>
                <td>{{$dat->nombre_edificio}}</td>
                <td>{{$dat->nombre_piso}}</td>
                <td>{{$dat->nombre_organizacion}}</td>
                <td>{{$dat->CodigoTarjeta}}</td>
                <td>{{$dat->Creacion}}</td>
            </tr>
            ?>
        @endforeach
        </tbody>
    </table>


@endif

</body>
</html>
