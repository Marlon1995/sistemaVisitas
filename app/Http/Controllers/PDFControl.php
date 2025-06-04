<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use \Carbon\Carbon;
use App\VisitasModelo;
use App\AccesosModelo;

class PDFControl extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('pdf.index');
    }

    public function crearPDF($datos, $vistaurl, $tipo, $formato)
    {
        $data = $datos;
        $usuario = \Auth::user();
        $date = Carbon::now()->format('d-m-Y');
        $view = \View::make($vistaurl, compact('data', 'date', 'formato', 'usuario'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadHTML($view);
        if ($tipo == 1) {
            return $pdf->stream('reporte.pdf');
        }
        if ($tipo == 2) {
            return $pdf->download('reporte.pdf');
        }
    }

    public function crear_reporte_organizacion(Request $request)
    {
        $formato = 0;
        // return response()->json($request);
        $vistaurl = "Pdf.organizacion";
        //$Visitas=VisitasModelo::findOrFail($id);
        $datos = AccesosModelo::join('Organizaciones', 'Organizaciones.idOrganizacion', '=', 'Accesos.idOrganizacion')
            ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
            ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
            ->join('Visitas', 'Visitas.idVisita', '=', 'Accesos.idVisita')
            ->join('Personas', 'Personas.idPersona', '=', 'Accesos.idPersona')
            ->join('Users', 'Users.id', '=', 'Accesos.idUsuario')
            ->select(['Users.name as NombreAtendido', 'Edificios.Nombre as nombre_edificio', 'Organizaciones.Nombre as nombre_organizacion', 'Pisos.Nombre as nombre_piso', 'Personas.Nombres', 'Personas.Cedula', 'Personas.Sexo', 'Personas.Foto', 'Accesos.Creacion', 'Accesos.Estado', 'Visitas.idVisita','Accesos.idAcceso', 'Accesos.CodigoTarjeta']);

        if ($request->has('persona')) {
            $datos = $datos->where('Personas.idPersona', '=', $request->persona);
            $formato = 1;
        }

        if ($request->has('edificio')) {
            $datos = $datos->where('Visitas.idEdificio', '=', $request->edificio);
        }

        if ($request->has('desde') && (!$request->has('hasta'))) {
            $datos = $datos->where('Visitas.Fecha', '=', $request->desde);
        }

        if ($request->has('desde') && $request->has('hasta')) {
            $datos = $datos->WhereBetween('Visitas.Fecha', [$request->desde, $request->hasta]);
        }

        if ($request->has('organizacion')) {
            $datos = $datos->where('Organizaciones.idOrganizacion', '=', $request->organizacion);
        }

        if ($request->has('visita')) {
            $datos = $datos->where('Visitas.idVisita', '=', $request->visita);
        }

        $datos = $datos
            ->orderBy('Edificios.Nombre', 'DESC')
            ->get();
        // return response()->json($request->organizacion);
        return $this->crearPDF($datos, $vistaurl, 1, $formato);
        //return response()->json($datos);
    }
}
