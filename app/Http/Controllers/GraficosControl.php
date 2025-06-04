<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PermisosModelo;
use App\RolesModelo;
use App\AccesosModelo;
use App\User;
use DB;
class GraficosControl extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
            $permisos =PermisosModelo::where('Permisos.idUsuario', '=', \Auth::user()->id)
            ->select(['Permisos.idRol'])
            ->get();

    	$usuarios=PermisosModelo::join('Roles','Roles.idRol','=','Permisos.idRol')->select('Roles.Rol',DB::RAW('count(*) as total'))
    	->GroupBy('Roles.Rol')
    	->get();
        
           $datos_visita_edificio = AccesosModelo::join('Organizaciones', 'Organizaciones.idOrganizacion', '=', 'Accesos.idOrganizacion')
                ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                ->join('Visitas', 'Visitas.idVisita', '=', 'Accesos.idVisita')
                ->join('Personas', 'Personas.idPersona', '=', 'Accesos.idPersona');
            if (PermisosModelo::where('idUsuario', '=', \Auth::user()->id)->where('idRol', '=', 1)->count() == 0) {
                 $datos_visita_edificio = $datos_visita_edificio->join('AsignacionEdificio', 'AsignacionEdificio.idEdificio', '=', 'Edificios.idEdificio')->Where('AsignacionEdificio.idUSuario', '=', \Auth::user()->id);
            }
            $visitas_edificio_dueno = $datos_visita_edificio->select([DB::RAW('count(Accesos.idAcceso) as total'), 'Edificios.Nombre as nombre_edificio'])
            ->GroupBy('Edificios.Nombre')
            ->get();

         
         $labels = AccesosModelo::join('Organizaciones', 'Organizaciones.idOrganizacion', '=', 'Accesos.idOrganizacion')
                ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                ->join('Visitas', 'Visitas.idVisita', '=', 'Accesos.idVisita')
                ->join('Personas', 'Personas.idPersona', '=', 'Accesos.idPersona');

            if (PermisosModelo::where('idUsuario', '=', \Auth::user()->id)->where('idRol', '=', 1)->count() == 0) {
                 $labels = $labels->join('AsignacionEdificio', 'AsignacionEdificio.idEdificio', '=', 'Edificios.idEdificio')->Where('AsignacionEdificio.idUSuario', '=', \Auth::user()->id);
            }

            $labels = $labels->select(['Organizaciones.Nombre as nombre_organizacion'])
            ->distinct()
            ->get();

           $serie = AccesosModelo::join('Organizaciones', 'Organizaciones.idOrganizacion', '=', 'Accesos.idOrganizacion')
                ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                ->join('Visitas', 'Visitas.idVisita', '=', 'Accesos.idVisita')
                ->join('Personas', 'Personas.idPersona', '=', 'Accesos.idPersona');

            if (PermisosModelo::where('idUsuario', '=', \Auth::user()->id)->where('idRol', '=', 1)->count() == 0) {
                 $serie = $serie->join('AsignacionEdificio', 'AsignacionEdificio.idEdificio', '=', 'Edificios.idEdificio')->Where('AsignacionEdificio.idUSuario', '=', \Auth::user()->id);
            }

            $serie=$serie->select(['Visitas.Fecha'])
            ->distinct()
            ->get();

            $data=array();
            $title=array();
            array_push($title,'Fechas');
            foreach ($serie as $value) {
                     $data_aux=array();                        
                     array_push($data_aux, $value->Fecha);
                     foreach ($labels as $value1) {
                        $cont=AccesosModelo::join('Organizaciones', 'Organizaciones.idOrganizacion', '=', 'Accesos.idOrganizacion')
                        ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                        ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                        ->join('Visitas', 'Visitas.idVisita', '=', 'Accesos.idVisita')
                        ->join('Personas', 'Personas.idPersona', '=', 'Accesos.idPersona');
                        if (PermisosModelo::where('idUsuario', '=', \Auth::user()->id)->where('idRol', '=', 1)->count() == 0) {
                            $cont = $cont->join('AsignacionEdificio', 'AsignacionEdificio.idEdificio', '=', 'Edificios.idEdificio')->Where('AsignacionEdificio.idUSuario', '=', \Auth::user()->id);
                        }
                        $cont=$cont->where('Visitas.Fecha','=', $value->Fecha)
                        ->where('Organizaciones.Nombre','=', $value1->nombre_organizacion)
                        ->count();
                        if(count($title) <= count($labels))
                        {
                          array_push($title,$value1->nombre_organizacion);
                        }
                        array_push($data_aux, $cont);
                     }
             array_push($data,json_encode($data_aux));
            }
            $value_line_data_organizacion=array("title"=>json_encode($title),"data"=>$data);

            //return response()->json($value_line_data_organizacion);


            $labels1 = AccesosModelo::join('Organizaciones', 'Organizaciones.idOrganizacion', '=', 'Accesos.idOrganizacion')
                ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                ->join('Visitas', 'Visitas.idVisita', '=', 'Accesos.idVisita')
                ->join('Personas', 'Personas.idPersona', '=', 'Accesos.idPersona');

            if (PermisosModelo::where('idUsuario', '=', \Auth::user()->id)->where('idRol', '=', 1)->count() == 0) {
                 $labels1 = $labels1->Where('Organizaciones.idUsuario', '=', \Auth::user()->id);
            }

            $labels1 = $labels1->select(['Organizaciones.Nombre as nombre_organizacion'])
            ->distinct()
            ->get();

           $serie1 = AccesosModelo::join('Organizaciones', 'Organizaciones.idOrganizacion', '=', 'Accesos.idOrganizacion')
                ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                ->join('Visitas', 'Visitas.idVisita', '=', 'Accesos.idVisita')
                ->join('Personas', 'Personas.idPersona', '=', 'Accesos.idPersona');

            if (PermisosModelo::where('idUsuario', '=', \Auth::user()->id)->where('idRol', '=', 1)->count() == 0) {
                 $serie1 = $serie1->Where('Organizaciones.idUsuario', '=', \Auth::user()->id);
            }

            $serie1=$serie1->select(['Visitas.Fecha'])
            ->distinct()
            ->get();

            $data=array();
            $title=array();
            array_push($title,'Fechas');
            foreach ($serie1 as $value) {
                     $data_aux=array();                        
                     array_push($data_aux, $value->Fecha);
                     foreach ($labels1 as $value1) {
                        $cont1=AccesosModelo::join('Organizaciones', 'Organizaciones.idOrganizacion', '=', 'Accesos.idOrganizacion')
                        ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                        ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                        ->join('Visitas', 'Visitas.idVisita', '=', 'Accesos.idVisita')
                        ->join('Personas', 'Personas.idPersona', '=', 'Accesos.idPersona');
                          if (PermisosModelo::where('idUsuario', '=', \Auth::user()->id)->where('idRol', '=', 1)->count() == 0) {
                            $cont1 = $cont1->Where('Organizaciones.idUsuario', '=', \Auth::user()->id);
                        }
                        $cont1=$cont1->where('Visitas.Fecha','=', $value->Fecha)
                        ->where('Organizaciones.Nombre','=', $value1->nombre_organizacion)
                        ->count();
                        if(count($title) <= count($labels1))
                        {
                          array_push($title,$value1->nombre_organizacion);
                        }
                        array_push($data_aux, $cont1);
                     }
             array_push($data,json_encode($data_aux));
            }
            $value_line_data_organizacion_encargado=array("title"=>json_encode($title),"data"=>$data);
           return view('Graficos.index', ["permisos"=>$permisos,"usuarios"=>$usuarios,"visitas_edificio_dueno"=>$visitas_edificio_dueno,"value_line_data_organizacion"=>(object)$value_line_data_organizacion,"value_line_data_organizacion_encargado"=>(object)$value_line_data_organizacion_encargado]);
    }
}
