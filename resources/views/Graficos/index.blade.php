@extends('layouts.app')
@section('content')
    @include('/loading')
 
    <div class="row">
      @foreach($permisos as $permis)
        @if($permis->idRol==1)
        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Usuarios</div>
                <div class="panel-body">
                    <div id="piechart" style="width: 100%; height: 500px;"></div>
                </div>
            </div>
        </div>
         @endif

        @if($permis->idRol==3)
          <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Visitas por Edificio</div>
                <div class="panel-body">
                    <div id="piechart1" style="width: 100%; height: 500px;"></div>
                </div>
            </div>
        </div>
    <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Visitas por Organización</div>
                <div class="panel-body">
            <div id="curve_chart" style="width: 100%; height: 500px;"></div>
                </div>
            </div>
        </div>
        @endif
    
     @if($permis->idRol==4)

    <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Visitas por Organización</div>
                <div class="panel-body">
            <div id="curve_chart1" style="width: 100%; height: 500px;"></div>
                </div>
            </div>
        </div>
        @endif
   @endforeach
    </div>

    <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Tipos de usuarios', 'Total'],
                    @foreach($usuarios as $value)
                ['{{$value->Rol}}',{{$value->total}}],
                @endforeach
            ]);

            var options = {
                title: ''
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }

       google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(grafico_bar_edificios);
        function grafico_bar_edificios() {

            var data = google.visualization.arrayToDataTable([
                ['Edificios', 'Visitas Edificios'],
                    @foreach($visitas_edificio_dueno as $value)
                ['{{$value->nombre_edificio}}',{{$value->total}}],
                @endforeach
            ]);

            var options = {
                 chart: {
                    title: '',
                    subtitle: '',
                },
                bars: 'vertical' // Required for Material Bar Charts.
            };

            var chart = new google.charts.Bar(document.getElementById('piechart1'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
        }


  google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(grafico_organizacion);

      function grafico_organizacion() {
        var data = google.visualization.arrayToDataTable([
          {!!$value_line_data_organizacion->title !!},
          @foreach($value_line_data_organizacion->data as $value)
           {!! $value !!},
          @endforeach

        ]);

        var options = {
     title: '',
          hAxis: {title: 'Fechas',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
      }

      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(grafico_organizacion_encargado);

      function grafico_organizacion_encargado() {
        var data = google.visualization.arrayToDataTable([
          {!!$value_line_data_organizacion_encargado->title !!},
          @foreach($value_line_data_organizacion_encargado->data as $value)
           {!! $value !!},
          @endforeach

        ]);

        var options = {
     title: '',
          hAxis: {title: 'Fechas',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart1'));
        chart.draw(data, options);
      }


    </script>
@endsection
