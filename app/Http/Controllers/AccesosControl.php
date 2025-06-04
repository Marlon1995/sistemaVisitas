<?php

namespace App\Http\Controllers;

use App\PermisosModelo;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Illuminate\Http\Request;
use App\VisitasModelo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\AuditoriasModelo;
use App\EdificiosModelo;
use App\OrganizacionesModelo;
use App\ConfiguracionesModelo;
use App\PersonasModelo;
use Illuminate\Support\Facades\Redis;
use App\PisosModelo;
use App\AccesosModelo;
use App\Services\TelegramServices;
use Validator;
use Response;
use View;
use App\Mail\EnviarEmail;
use Illuminate\Support\Facades\Input;
use App\Jobs\ProcesarVisitaJob;
use DB;
use App\ImpresorasModelo;
class AccesosControl extends BaseSoapController
{
    protected $rules =
        [
            'edificio' => 'required'
        ];
    protected $rules1 =
        [
            'txt_nombre_persona' => 'required',
            'txt_sexo_persona' => 'required',
            'txt_cedula_persona' => 'required',
            'txt_organizacion' => 'required',
            'txt_codigo_tarjeta' => 'required'
        ];
    protected $rules2 =
        [
            'txt_cedula_persona' => 'required',
            'txt_organizacion' => 'required',
            'txt_codigo_tarjeta' => 'required'
        ];


    public function __construct()
    {
      //  $this->connection = config('global.urlRegistroCivil');
       // self::setWsdl($this->connection);
        //$this->service = InstanceSoapClient::init();
        $this->middleware('auth');
    }

    public function index($id_edificio, Request $request)
    {
        $tipo = 0;
        if ($request) {
            $edificio = EdificiosModelo::findOrFail($id_edificio);
            $query1 = trim($request->get('searchText'));
            $query2 = trim($request->get('searchText1'));
            $query3 = trim($request->get('searchText2'));

            $datos = VisitasModelo::join('Edificios', 'Edificios.idEdificio', '=', 'Visitas.idEdificio')
  ->join('Pisos', 'Pisos.idEdificio', '=', 'Edificios.idEdificio')
                ->join('Organizaciones', 'Organizaciones.idPiso', '=', 'Pisos.idPiso')
                ->select(['Visitas.Fecha', 'Visitas.idVisita', 'Edificios.Nombre', 'Edificios.idEdificio']);

            if (PermisosModelo::where('idUsuario', '=', \Auth::user()->id)->where('idRol', '=', 1)->count() == 0) {
                $datos = $datos->Where('AsignacionEdificio.idUSuario', '=', \Auth::user()->id)
                    ->join('AsignacionEdificio', 'AsignacionEdificio.idEdificio', '=', 'Edificios.idEdificio')
                    ->join('Users', 'Users.id', '=', 'AsignacionEdificio.idUSuario')
                    ->join('Permisos', 'Permisos.idUSuario', '=', 'Users.id');
                $tipo = 2;
            }

            $datos = $datos->Where('Edificios.Estado', '=', 1)
                ->Where('Visitas.idEdificio', '=', $edificio->idEdificio)
                ->distinct()
                ->orderBy('Visitas.idVisita', 'desc')
                ->Where(function ($query) use ($query1, $query2, $query3) {
                    $query
                        ->orWhereBetween('Visitas.Fecha', [$query1, $query2])
                        ->orwhere('Organizaciones.idOrganizacion', 'LIKE', '%' . $query3 . '%')
                        ->orwhere('Visitas.Fecha', 'LIKE', '%' . $query1 . '%');
                })->paginate(50);
            $organizaciones = [];
            if (count($datos) > 0) {
                $organizaciones = OrganizacionesModelo::join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                    ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                    ->select(['Organizaciones.Nombre','Organizaciones.idOrganizacion'])
                    ->where('Edificios.idEdificio', '=', $edificio->idEdificio)
                    ->where('Organizaciones.Estado', '=', 1)
                    ->where('Pisos.Estado', '=', 1)
                    ->where('Edificios.Estado', '=', 1)
                    ->get();
            }
            return view('Accesos.index', ["organizaciones" => $organizaciones, "tipo" => $tipo, "posts" => $datos, "searchText" => $query1, "searchText1" => $query2, "searchText2" => $query3, "Edificio" => $id_edificio]);
        }
    }

    public function index_dueno($id_edificio, Request $request)
    {
        $tipo = 0;
        if ($request) {
            $edificio = EdificiosModelo::findOrFail($id_edificio);
            $query1 = trim($request->get('searchText'));
            $query2 = trim($request->get('searchText1'));
            $query3 = trim($request->get('searchText2'));

            $datos = VisitasModelo::join('Edificios', 'Edificios.idEdificio', '=', 'Visitas.idEdificio')
                ->join('Pisos', 'Pisos.idEdificio', '=', 'Edificios.idEdificio')
                ->join('Organizaciones', 'Organizaciones.idPiso', '=', 'Pisos.idPiso')
                ->select(['Visitas.Fecha', 'Visitas.idVisita', 'Edificios.Nombre', 'Edificios.idEdificio']);

            if (PermisosModelo::where('idUsuario', '=', \Auth::user()->id)->where('idRol', '=', 1)->count() == 0) {
                $datos = $datos
                    ->join('DuenoEdificio', 'DuenoEdificio.idEdificio', '=', 'Edificios.idEdificio')
                    ->join('Users', 'Users.id', '=', 'DuenoEdificio.idUSuario')
                    ->join('Permisos', 'Permisos.idUSuario', '=', 'Users.id')
                    ->Where('DuenoEdificio.idUSuario', '=', \Auth::user()->id);
                $tipo = 1;
            }

            $datos = $datos->Where('Edificios.Estado', '=', 1)
                ->Where('Visitas.idEdificio', '=', $edificio->idEdificio)
                ->distinct()
                ->orderBy('Visitas.idVisita', 'desc')
                ->Where(function ($query) use ($query1, $query2, $query3) {
                    $query
                        ->orWhereBetween('Visitas.Fecha', [$query1, $query2])
                        ->orwhere('Organizaciones.idOrganizacion', 'LIKE', '%' . $query3 . '%')
                        ->orwhere('Visitas.Fecha', 'LIKE', '%' . $query1 . '%');
                })->paginate(50);
            $organizaciones = [];
            if (count($datos) > 0) {
                $organizaciones = OrganizacionesModelo::join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                    ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                    ->select(['Organizaciones.Nombre','Organizaciones.idOrganizacion'])
                    ->where('Edificios.idEdificio', '=', $edificio->idEdificio)
                    ->where('Organizaciones.Estado', '=', 1)
                    ->where('Pisos.Estado', '=', 1)
                    ->where('Edificios.Estado', '=', 1)
                    ->get();
            }


            return view('Accesos.index', ["organizaciones" => $organizaciones, "tipo" => $tipo, "posts" => $datos, "searchText" => $query1, "searchText1" => $query2, "searchText2" => $query3, "Edificio" => $id_edificio]);
        }
    }

    public function get_visitas_encargados($id, Request $request)
    {
        if ($request) {
            $query1 = trim($request->get('searchText'));
            $query2 = trim($request->get('searchText1'));
            $datos = OrganizacionesModelo::join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                ->join('Users', 'Users.id', '=', 'Organizaciones.idUsuario')
                ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                ->join('Visitas', 'Visitas.idEdificio', '=', 'Edificios.idEdificio')
                ->Where('Organizaciones.idOrganizacion', '=', $id)
                ->Where('Users.Estado', '=', 1)
                ->Where('Edificios.Estado', '=', 1);
            if (PermisosModelo::where('idUsuario', '=', \Auth::user()->id)->where('idRol', '=', 1)->count() == 0) {
                $datos = $datos->where('Users.id', '=', \Auth::user()->id);
            }
            $datos = $datos->select(['Visitas.Fecha', 'Visitas.idVisita', 'Pisos.Nombre as nombre_piso', 'Organizaciones.idOrganizacion', 'Organizaciones.Nombre as nombre_organizacion', 'Edificios.Nombre', 'Edificios.idEdificio'])
                ->Where(function ($query) use ($query1, $query2) {
                    $query
                        ->orWhereBetween('Visitas.Fecha', [$query1, $query2])
                        ->orwhere('Visitas.Fecha', 'LIKE', '%' . $query1 . '%');
                })->paginate(50);
            return view('EncargadoOrganizacion.visitas', ["organizacion" => $id, "posts" => $datos, "searchText" => $query1, "searchText1" => $query2]);
        }
    }

    public function store($id, Request $request)
    {
        $datos = [];
        $guardo=false;
        try {
            if ($request->has('tipo')) {
                if ($request->tipo) {
                    $validar = $this->rules2;
                } else {
                    $validar = $this->rules1;
                }
            } else {
                $validar = $this->rules1;
            }

            $validator = Validator::make(Input::all(), $validar);
            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            } else {
                DB::beginTransaction();
                $Personas = PersonasModelo::Where('Personas.Cedula', '=', $request->txt_cedula_persona)->first();
                if ($Personas == null) {
                    $Personas = new PersonasModelo();
                    if ($request->tipo) {
                        $obtenidos_registro = $this->buscar_registro($request->txt_cedula_persona);
                        if (!$obtenidos_registro->status) {
                            DB::rollback();
                            return response()->json(array('errors' => array("txt_error_web_service" => "No se ha podido realizar peticion al web service, compuebe su conexión a internet")));
                        } else {
                            if (empty($obtenidos_registro->data->razonsocial)) {
                                DB::rollback();
                                return response()->json(array('errors' => array("txt_cedula_persona" => "No se ha podido encontrar a la persona porfavor verificar los datos.")));
                            }
                        }

                        $Personas->Nombres = $obtenidos_registro->data->razonsocial;
                        $Personas->Cedula = $request->txt_cedula_persona;
                        $Personas->Sexo = $obtenidos_registro->data->sexo;
                        $Personas->Direccion = $obtenidos_registro->data->direccion;

                    } else {
                        $Personas->Nombres = $request->txt_nombre_persona;
                        $Personas->Cedula = $request->txt_cedula_persona;
                        $Personas->Sexo = $request->txt_sexo_persona;
                        $Personas->Direccion = "Sin especificar";
                    }
                    
                    $Personas->save();
                }
                $Visitas = new AccesosModelo();
                $Visitas->idPersona = $Personas->idPersona;
                $Visitas->idOrganizacion = $request->txt_organizacion;
                $Visitas->CodigoTarjeta = $request->txt_codigo_tarjeta;
                $Visitas->idVisita = $id;
                $Visitas->idUsuario = \Auth::user()->id;
                $Visitas->save();
                $guardo = true;
                $datos = AccesosModelo::join('Personas', 'Personas.idPersona', '=', 'Accesos.idPersona')
                    ->join('Organizaciones', 'Organizaciones.idOrganizacion', '=', 'Accesos.idOrganizacion')
                    ->join('Users', 'Users.id', '=', 'Organizaciones.idUSuario')
                    ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                     ->join('Impresoras', 'Impresoras.idImpresora', '=', 'Users.idImpresora')
                    ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                    ->Where('Accesos.idAcceso', '=', $Visitas->idAcceso)
                    ->select(['Users.name','Impresoras.Nombre', 'Users.email', 'Accesos.idAcceso', 'Edificios.Nombre as nombre_edificio', 'Organizaciones.Nombre as nombre_organizacion', 'Pisos.Nombre as nombre_piso', 'Personas.Nombres', 'Personas.Cedula', 'Personas.Sexo', 'Accesos.Creacion', 'Accesos.CodigoTarjeta', 'Accesos.Estado'])
                    ->first();
                 
               $user = \Auth::user();
                $idUsuario = $user->id;

                $deseaImprimir = $request->input('imprimir_ticket', false);
        
                ProcesarVisitaJob::dispatch(json_encode($datos), $Personas, $Visitas, $idUsuario, $guardo,$deseaImprimir);

                  DB::commit();
                return response()->json(['data' => $datos]);


              
            }
        } catch (\Exception $e) {
            DB::rollback();
            DB::beginTransaction();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->Accion = "Error: " . $e->getMessage();
            $Auditoria->save();
            DB::commit();
        
        }
    
  return response()->json(array('errors' => $e->getMessage()));

    }


    private function buscar_registro($cedula)
    {
        $dato = new \stdClass();
        try {
            $registro = ConfiguracionesModelo::where('Configuraciones.Nombre', '=', 'registro_civil')->first();
            $Servicio = $this
                ->service
                ->consultaDeDatos(
                    $cedula,
                    $registro->Data1,
                    $registro->Data2,
                    $registro->Data3,
                    $registro->Data4,
                    $registro->Data5,
                    $registro->Data6
                );
            $dato->status = true;
            $dato->data = $Servicio;
            return $dato;

        } catch (\Exception $e) {
            $dato->status = false;
            $dato->data = [];
            return $dato;
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $post = AccesosModelo::findOrFail($id);
            $post->Estado = ($post->Estado == 0) ? 1 : 0;
            $post->save();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->idUSuario = \Auth::user()->id;
            $Auditoria->Accion = "Anulación de acceso: " . $post->idAcceso;
            $Auditoria->save();
            DB::commit();
            flash((($post->Estado == 0) ? "Anulado " : "Recuperado ") . " con exito")->success();
            return redirect('mostrar_visita/' . $post->idVisita);
        } catch (\Exception $e) {
            DB::rollback();
            DB::beginTransaction();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->Accion = "Error: " . $e->getMessage();
            $Auditoria->save();
            DB::commit();
            return response()->json(array('errors' => $e->getMessage()));
        }
    }

    public function accesos_encargados($id, Request $request)
    {
        if ($request) {
            $query1 = trim($request->get('searchText'));
            $datos = AccesosModelo::join('Organizaciones', 'Organizaciones.idOrganizacion', '=', 'Accesos.idOrganizacion')
                ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                ->join('Visitas', 'Visitas.idVisita', '=', 'Accesos.idVisita')
                ->join('Personas', 'Personas.idPersona', '=', 'Accesos.idPersona')
                ->Where('Visitas.idVisita', '=', $id)
                ->where('Edificios.Estado', '=', 1)
                ->where('Pisos.Estado', '=', 1)
                ->where('Personas.Estado', '=', 1);
            if (PermisosModelo::where('idUsuario', '=', \Auth::user()->id)->where('idRol', '=', 1)->count() == 0) {
                $datos = $datos->where('Organizaciones.idUsuario', '=', \Auth::user()->id);
            }
            $datos = $datos->select(['Accesos.idAcceso', 'Edificios.Nombre as nombre_edificio', 'Organizaciones.Nombre as nombre_organizacion', 'Pisos.Nombre as nombre_piso', 'Personas.Nombres', 'Personas.Sexo', 'Personas.Foto', '.Accesos.CodigoTarjeta', 'Personas.Cedula', 'Accesos.Creacion', 'Accesos.Estado', 'Visitas.idVisita'])
                ->Where(function ($query) use ($query1) {
                    $query
                        ->orwhere('Personas.Nombres', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Personas.Cedula', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Personas.Sexo', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Accesos.CodigoTarjeta', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Edificios.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Pisos.Nombre', 'LIKE', '%' . $query1 . '%');
                })->paginate(50);

//return response()->json($datos);

            return view('EncargadoOrganizacion.accesos', ["visita" => $id, "posts" => $datos, "searchText" => $query1, "idvisita" => $id]);
        }

    }

    public function show($id, Request $request)
    {
        if ($request) {
            $tipo = 0;
            $query1 = trim($request->get('searchText'));
            $Visitas = VisitasModelo::findOrFail($id);
            $datos = AccesosModelo::join('Organizaciones', 'Organizaciones.idOrganizacion', '=', 'Accesos.idOrganizacion')
                ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                ->join('Visitas', 'Visitas.idVisita', '=', 'Accesos.idVisita')
                ->join('Personas', 'Personas.idPersona', '=', 'Accesos.idPersona');
            if (PermisosModelo::where('idUsuario', '=', \Auth::user()->id)->where('idRol', '=', 1)->count() == 0) {
                $datos = $datos->join('AsignacionEdificio', 'AsignacionEdificio.idEdificio', '=', 'Edificios.idEdificio')->Where('AsignacionEdificio.idUSuario', '=', \Auth::user()->id);
                $tipo = 2;
                //dd($visitas);
            }
            $datos = $datos->Where('Visitas.idVisita', '=', $Visitas->idVisita)
                ->select(['Accesos.idAcceso', 'Edificios.Nombre as nombre_edificio', 'Organizaciones.Nombre as nombre_organizacion', 'Pisos.Nombre as nombre_piso', 'Personas.Nombres', 'Personas.Cedula', 'Accesos.CodigoTarjeta', 'Accesos.Creacion', 'Accesos.Estado', 'Visitas.idVisita', 'Personas.Sexo'])
                ->orderBy('Accesos.idAcceso', 'DESC')
                ->Where(function ($query) use ($query1) {
                    $query
                        ->orwhere('Edificios.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Organizaciones.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Pisos.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Personas.Nombres', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Personas.Sexo', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Accesos.CodigoTarjeta', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Personas.Cedula', 'LIKE', '%' . $query1 . '%');
                })->paginate(50);
            $pisos = PisosModelo::Where('Pisos.idEdificio', '=', $Visitas->idEdificio)
                ->where('Pisos.Estado', '=', 1)
                ->get();
  
            $organizaciones = OrganizacionesModelo::join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                ->Where('Pisos.idEdificio', '=', $Visitas->idEdificio)
                ->where('Organizaciones.Estado', '=', 1)
                ->where('Pisos.Estado', '=', 1)
                ->select(['Organizaciones.idOrganizacion', 'Organizaciones.Nombre', 'Pisos.idPiso'])
                ->distinct("Organizaciones.idOrganizacion")
                ->orderBy('Pisos.idPiso', 'desc')
                ->get();

//return response()->json($datos);

            return view('Accesos.show', ["tipo" => $tipo, "fecha_actual" => Carbon::now()->format('Y-m-d')
                , "fecha_visita" => $Visitas->Fecha, "pisos" => $pisos, "organizaciones" => $organizaciones, "posts" => $datos, "searchText" => $query1, "idvisita" => $id]);
        }
    }

    public function show_dueno($id, Request $request)
    {
        $tipo = 0;
        if ($request) {
            $query1 = trim($request->get('searchText'));
            $Visitas = VisitasModelo::findOrFail($id);
            $datos = AccesosModelo::join('Organizaciones', 'Organizaciones.idOrganizacion', '=', 'Accesos.idOrganizacion')
                ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                ->join('Visitas', 'Visitas.idVisita', '=', 'Accesos.idVisita')
                ->join('Personas', 'Personas.idPersona', '=', 'Accesos.idPersona')
                ->Where('Visitas.idVisita', '=', $Visitas->idVisita);
            if (PermisosModelo::where('idUsuario', '=', \Auth::user()->id)->where('idRol', '=', 1)->count() == 0) {
                $datos = $datos->join('DuenoEdificio', 'DuenoEdificio.idEdificio', '=', 'Edificios.idEdificio')
                    ->Where('DuenoEdificio.idUSuario', '=', \Auth::user()->id);
                $tipo = 1;
            }
            $datos = $datos->select(['Accesos.idAcceso', 'Edificios.Nombre as nombre_edificio', 'Organizaciones.Nombre as nombre_organizacion', 'Pisos.Nombre as nombre_piso', 'Personas.Nombres', 'Personas.Foto', 'Personas.Cedula', 'Accesos.Creacion', 'Accesos.Estado', 'Visitas.idVisita', 'Personas.Sexo', 'Accesos.CodigoTarjeta'])
                ->orderBy('Accesos.idAcceso', 'DESC')
                ->Where(function ($query) use ($query1) {
                    $query
                        ->orwhere('Edificios.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Organizaciones.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Pisos.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Personas.Nombres', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Personas.Sexo', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Accesos.CodigoTarjeta', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Personas.Cedula', 'LIKE', '%' . $query1 . '%');
                })->paginate(50);
            $pisos = PisosModelo::Where('Pisos.idEdificio', '=', $Visitas->idEdificio)
                ->where('Pisos.Estado', '=', 1)
                ->get();

            $organizaciones = OrganizacionesModelo::join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                ->Where('Pisos.idEdificio', '=', $Visitas->idEdificio)
                ->select(['Organizaciones.idOrganizacion', 'Organizaciones.Nombre', 'Pisos.idPiso'])
                ->distinct("Organizaciones.idOrganizacion")
                ->where('Organizaciones.Estado', '=', 1)
                ->where('Pisos.Estado', '=', 1)
                ->orderBy('Pisos.idPiso', 'desc')
                ->get();
            //return response()->json($datos);
            return view('Accesos.show', ["tipo" => $tipo, "fecha_actual" => Carbon::now()->format('Y-m-d')
                , "fecha_visita" => $Visitas->Fecha, "pisos" => $pisos, "organizaciones" => $organizaciones, "posts" => $datos, "searchText" => $query1, "idvisita" => $id]);
        }
    }

    public function crear_visita(Request $request)
    {
       
        try {
            $validator = Validator::make(Input::all(), $this->rules);
            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            } else {
                DB::beginTransaction();
                $date = Carbon::now()->format('Y-m-d');
                $existe = VisitasModelo::Where('Visitas.Fecha', '=', $date)
                    ->Where('Visitas.idEdificio', '=', $request->edificio)
                    ->first();
                if ($existe == null) {
                    $existe = new VisitasModelo();
                    $existe->Fecha = $date;
                    $existe->idEdificio = $request->edificio;
                    $existe->save();
                }
               
            /*    $Auditoria = new AuditoriasModelo();
                $Auditoria->idUSuario = \Auth::user()->id;
                $Auditoria->Accion = "El Usuario: " . \Auth::user()->name . " creado una visita";
                $Auditoria->save();
                DB::commit();*/
                DB::commit();
                return response()->json($existe);
            }
        } catch (\Exception $e) {
            DB::rollback();
            DB::beginTransaction();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->Accion = $e->getMessage();
            $Auditoria->save();
            return response()->json(array('errors' => $e->getMessage()));


        }
    }

}
