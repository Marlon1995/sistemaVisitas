<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Centro Financiero</title>
</head>
<body>
<h3>Visita {{$mensaje->Creacion}}</h3>
<p><b>La persona</b>: {{$mensaje->Nombres}}</p>
<p><b>Cedula</b>: {{$mensaje->Cedula}}</p>
<p><b>Sexo de la persona</b>: {{$mensaje->Sexo=='M'?'Hombre':'Mujer'}}</p>
<p><b>Ha visitado la organización</b>: {{$mensaje->nombre_organizacion}}</p>
<p><b>Código Tarjeta:</b>: {{$mensaje->CodigoTarjeta}}</p>
<p><b>Ubicada en el edificio</b>: {{$mensaje->nombre_edificio}} - <b>Piso/Sección:</b> {{$mensaje->nombre_piso}}</p>
<p><b>Ticket numero</b>: {{$mensaje->idAcceso}}</p>
</body>
</html>
