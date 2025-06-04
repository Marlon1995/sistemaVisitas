<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Control de Acceso</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{asset('public/tema/lib/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/tema/lib/css/font-awesome.css')}}">
    <link rel="stylesheet" href="{{asset('public/tema/lib/css/AdminLTE.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/tema/lib/css/_all-skins.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/tema/lib/css/bootstrap-select.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/tema/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/tema/persona.css')}}">
    <link href="{{asset('public/tema/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet"/>
    <script src="{{asset('public/tema/jquery.min.js')}}"></script>
    <script type="text/javascript" src="https://www.nodeanalytics.net/webchat/chatweb/js/jquery-migrate-3.0.1.min.js"></script>
    <script src="{{asset('public/tema/toastr.min.js')}}"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link href="{{asset('/public/images/favicon.png')}}" rel="shortcut icon" type="image/png"/>
    <?php
    $logeado = \Auth::user();
    $permisos = \DB::table('Permisos')
        ->where('Permisos.idUsuario', '=', $logeado->id)
        ->select(['Permisos.idRol'])
        ->get();
    ?>
</head>
<body class="hold-transition skin-blue sidebar-mini" id="bode_html">
<div class="wrapper">
    <header class="main-header">
        <a href="{{url('/')}}" class="logo">
            Centro Financiero
            <span class="logo-mini"><b>Control Visita</b></span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Navegación</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs">{{ Auth::user()->name }} <p class="fa fa-gear"></p></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-body">
                                <div class="col-xs-12 text-center">
                                    <a href="{{ url('cambiar') }}"><span class="fa fa-user"></span> Perfil</a>
                                </div>
                            </li>
                            <li class="user-footer">

                                <div class="pull-right">
                                    <a href="{{url('logout')}}" class="btn btn-default btn-flat">Cerrar <p
                                                class="fa fa-times-circle-o"></p></a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">
                <li class="header"></li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i><span>Inicio</span>
                        <i class="fa fa-angle-left pull-right"></i>

                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{url('/')}}"><i class="fa fa-circle-o"></i>Inicio</a></li>
                        <li><a href="{{url('dashboard')}}"><i class="fa fa-circle-o"></i>Dashboard</a></li>
                    </ul>
                </li>
                @foreach($permisos as $permisos)
                    @if($permisos->idRol==1)
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-cogs"></i> <span>Super Administrador</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/usuarios')}}"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                                <li><a href="{{url('/edificios')}}"><i class="fa fa-circle-o"></i>Edificios </a>
                                </li>
                                <li><a href="{{url('/pisos')}}"><i class="fa fa-circle-o"></i>Pisos </a>
                                </li>
                                <li><a href="{{url('/organizaciones')}}"><i class="fa fa-circle-o"></i>Organizaciones
                                    </a>
                                </li>
                                <li><a href="{{url('/impresoras')}}"><i class="fa fa-circle-o"></i>Impresoras</a>
                                </li>

                                <li><a href="{{url('/auditorias')}}"><i class="fa fa-circle-o"></i>Auditoría</a>
                                </li>

                                <li><a href="{{url('/reporte')}}"><i class="fa fa-circle-o"></i>Reporte</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if($permisos->idRol==6)
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-cogs"></i> <span>Administrador</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/asignaciones')}}"><i class="fa fa-circle-o"></i>Encargado
                                        Puerta</a>
                                </li>
                                <li><a href="{{url('/dueno')}}"><i class="fa fa-circle-o"></i>Dueño Edificios</a>
                                </li>
                                <li><a href="{{url('/personas')}}"><i class="fa fa-circle-o"></i>Visitantes</a></li>

                            </ul>
                        </li>
                    @endif

                    @if($permisos->idRol==3)
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa fa-database"></i> <span>Encargado Edificio</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/edificios_duenos')}}"><i class="fa fa-circle-o"></i>Edificios</a>
                                </li>


                            </ul>
                        </li>
                    @endif
                    @if($permisos->idRol==4)
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-cc"></i> <span>Encargado Organización</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/organizaciones_encargados')}}"><i class="fa fa-circle-o"></i>
                                        Organizaciones</a></li>


                            </ul>
                        </li>
                    @endif
                    @if($permisos->idRol==5)
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-briefcase"></i> <span>Control de Visitas</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/edificios_puerta')}}"><i class="fa fa-circle-o"></i>Registro de
                                        Visitas</a>
                            </ul>
                        </li>
                    @endif

                @endforeach
            </ul>
        </section>
    </aside>
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">SISTEMA CONTROL DE VISITAS v2.0</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                                    @include('flash::message')
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">

        </div>
        <strong>Copyright &copy;<a href="#">CROSOFT SYSTEM</a>.</strong> Todos los derechos reservados.
    </footer>
    <script src="{{asset('public/tema/lib/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('public/tema/lib/js/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('public/tema/lib/js/app.min.js')}}"></script>
    <script src="{{asset('public/tema/persona.js')}}"></script>
    <script>
        $(document).ready(function() {
            $("form").keypress(function(e) {
                if (e.which == 13) {
                    return false;
                }
            });
        });
    </script>
    <!--  <script type="text/javascript">$("#bode_html").addClass("sidebar-collapse");</script>-->
</div>
</body>
</html>
</html>
