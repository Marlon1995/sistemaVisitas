<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;
use Validator;
use Response;
use View;

//Modelos
use App\OrganizacionesModelo;
use App\User;
use App\PisosModelo;
use App\AuditoriasModelo;
use App\EdificiosModelo;
use App\PermisosModelo;

class OrganizacionesControl extends Controller
{
    protected $rules_create =
        [

            'txt_id_piso' => 'required',
            'txt_nombre' => 'required',
            'txt_email_encargado' => 'required|email',
            'txt_nombres_encargado' => 'required',
        ];

    protected $rules_edit =
        [
            'txt_idusuario' => 'required',
            'txt_nombres_encargado' => 'required',
            'txt_email_encargado' => 'required|email',
            'txt_id_piso' => 'required',
            'txt_nombre' => 'required'
        ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function organizaciones_dueno($id, Request $request)
    {
        if ($request) {
            $query1 = trim($request->get('searchText'));
            $datos = OrganizacionesModelo::join('Users', 'Users.id', '=', 'Organizaciones.idUsuario')
                ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                ->where('Organizaciones.Estado', '=', 1)
                ->where('Pisos.Estado', '=', 1)
                ->where('Edificios.Estado', '=', 1)
                ->where('Users.Estado', '=', 1)
                ->where('Pisos.idPiso', '=', $id)
                ->select(['Organizaciones.Nombre', 'Organizaciones.Estado', 'Organizaciones.NombreEncargado as nombre_encargado', 'Users.email as email_encargado', 'Pisos.Nombre as nombre_piso', 'Pisos.idPiso', 'Organizaciones.idOrganizacion', 'Users.id', 'Edificios.Nombre as nombre_edificio', 'Edificios.idEdificio'])
                ->orderBy('Organizaciones.idOrganizacion', 'desc')
                ->Where(function ($query) use ($query1) {
                    $query
                        ->orwhere('Edificios.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Organizaciones.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Organizaciones.Estado', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Organizaciones.NombreEncargado', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Users.name', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Users.email', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Pisos.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Organizaciones.Creacion', 'LIKE', '%' . $query1 . '%');
                })->paginate(20);
            $pisos = PisosModelo::join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                ->where('Edificios.Estado', '=', 1)
                ->where('Pisos.Estado', '=', 1)
                ->select(['Pisos.idPiso', 'Pisos.Nombre as nombre_piso', 'Edificios.Nombre as nombre_edificio'])
                ->get();

            return view('Duenos.organizaciones', ["pisos" => $pisos, "posts" => $datos, "idorganizacion" => $id, "searchText" => $query1]);
        }
    }

    public function index(Request $request)
    {
        if ($request) {
            $query1 = trim($request->get('searchText'));
            $datos = OrganizacionesModelo::join('Users', 'Users.id', '=', 'Organizaciones.idUsuario')
                ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                ->where('Organizaciones.Estado', '=', 1)
                ->where('Pisos.Estado', '=', 1)
                ->where('Edificios.Estado', '=', 1)
                ->where('Users.Estado', '=', 1)
                ->select(['Organizaciones.Nombre', 'Organizaciones.Estado', 'Organizaciones.NombreEncargado as nombre_encargado', 'Users.email as email_encargado', 'Pisos.Nombre as nombre_piso', 'Pisos.idPiso', 'Organizaciones.idOrganizacion', 'Users.id', 'Edificios.Nombre as nombre_edificio'])
                ->orderBy('Organizaciones.idOrganizacion', 'desc')
                ->Where(function ($query) use ($query1) {
                    $query
                        ->orwhere('Edificios.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Organizaciones.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Organizaciones.Estado', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Users.name', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Organizaciones.NombreEncargado', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Users.email', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Pisos.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Organizaciones.Creacion', 'LIKE', '%' . $query1 . '%');
                })->paginate(20);

            $pisos = PisosModelo::join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                ->where('Edificios.Estado', '=', 1)
                ->where('Pisos.Estado', '=', 1)
                ->select(['Pisos.idPiso', 'Pisos.Nombre as nombre_piso', 'Edificios.Nombre as nombre_edificio'])
                ->get();
            return view('Organizaciones.index', ["posts" => $datos, "pisos" => $pisos, "searchText" => $query1]);
        }
    }

    public function OrganizacionesEncargados(Request $request)
    {
        if ($request) {
            $query1 = trim($request->get('searchText'));
            $datos = OrganizacionesModelo::join('Users', 'Users.id', '=', 'Organizaciones.idUsuario')
                ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                ->where('Organizaciones.Estado', '=', 1)
                ->where('Pisos.Estado', '=', 1)
                ->where('Edificios.Estado', '=', 1)
                ->where('Users.Estado', '=', 1);
            if (PermisosModelo::where('idUsuario', '=', \Auth::user()->id)->where('idRol', '=', 1)->count() == 0) {
                $datos = $datos->where('Users.id', '=', \Auth::user()->id);
            }

            $datos = $datos->select(['Organizaciones.Nombre', 'Organizaciones.Estado', 'Organizaciones.NombreEncargado as nombre_encargado', 'Users.email as email_encargado', 'Pisos.Nombre as nombre_piso', 'Pisos.idPiso', 'Organizaciones.idOrganizacion', 'Users.id', 'Edificios.idEdificio', 'Edificios.Nombre as nombre_edificio'])
                ->orderBy('Organizaciones.idOrganizacion', 'desc')
                ->Where(function ($query) use ($query1) {
                    $query
                        ->orwhere('Edificios.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Organizaciones.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Organizaciones.Estado', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Users.name', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Organizaciones.NombreEncargado', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Users.email', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Pisos.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Organizaciones.Creacion', 'LIKE', '%' . $query1 . '%');
                })->paginate(20);

            $pisos = PisosModelo::join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                ->where('Edificios.Estado', '=', 1)
                ->where('Pisos.Estado', '=', 1)
                ->select(['Pisos.idPiso', 'Pisos.Nombre as nombre_piso', 'Edificios.Nombre as nombre_edificio'])
                ->get();
            return view('EncargadoOrganizacion.organizaciones', ["posts" => $datos, "pisos" => $pisos, "searchText" => $query1]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make(Input::all(), $this->rules_create);
            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            } else {
                DB::beginTransaction();
                $existe = User::where('Users.email', '=', $request->txt_email_encargado)->first();

                if ($existe == null) {
                    $encargado = new User();
                    $encargado->password = bcrypt('Crosoft.2019');
                } else {
                    $encargado = $existe;
                }

                $encargado->name = "Encargado de organización " . $request->txt_nombre;
                $encargado->email = $request->txt_email_encargado;
                $encargado->UserName = $request->txt_email_encargado;
                $encargado->save();

                $existe = PermisosModelo::where('Permisos.idUsuario', '=', $encargado->id)
                    ->where('Permisos.idRol', '=', 4)
                    ->first();

                if ($existe == null) {
                    $rol = new PermisosModelo();
                    $rol->idRol = 4;
                    $rol->idUsuario = $encargado->id;
                    $rol->save();
                }

                $existe = PermisosModelo::where('Permisos.idUsuario', '=', $encargado->id)
                    ->where('Permisos.idRol', '=', 2)
                    ->first();

                if ($existe == null) {
                    $rol = new PermisosModelo();
                    $rol->idRol = 2;
                    $rol->idUsuario = $encargado->id;
                    $rol->save();
                }

                $post = new OrganizacionesModelo();
                $post->idUsuario = $encargado->id;
                $post->idPiso = $request->txt_id_piso;
                $post->Nombre = $request->txt_nombre;
                $post->NombreEncargado = $request->txt_nombres_encargado;
                $post->save();

                $datos = OrganizacionesModelo::join('Users', 'Users.id', '=', 'Organizaciones.idUsuario')
                    ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                    ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                    ->where('Organizaciones.idOrganizacion', '=', $post->idOrganizacion)
                    ->select(['Organizaciones.Nombre', 'Organizaciones.Estado', 'Organizaciones.NombreEncargado as nombre_encargado', 'Users.email as email_encargado', 'Pisos.Nombre as nombre_piso', 'Pisos.idPiso', 'Organizaciones.idOrganizacion', 'Users.id', 'Edificios.Nombre as nombre_edificio'])
                    ->first();

                $Auditoria = new AuditoriasModelo();
                $Auditoria->idUSuario = \Auth::user()->id;
                $Auditoria->Accion = "Registro de organización: " . $request->txt_nombre;
                $Auditoria->save();
                DB::commit();
                return response()->json($datos);
            }
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

    public function show($id)
    {

    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make(Input::all(), $this->rules_edit);
            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            } else {
                DB::beginTransaction();

                $existe = User::where('Users.email', '=', $request->txt_email_encargado)->first();
                if ($existe == null) {
                    $encargado = new User();
                    $encargado->password = bcrypt('Crosoft.2019');
                } else {
                    $encargado = $existe;
                }

                $encargado->name = "Encargado de organización " . $request->txt_nombre;
                $encargado->email = $request->txt_email_encargado;
                $encargado->UserName = $request->txt_email_encargado;
                $encargado->save();

                $existe = PermisosModelo::where('Permisos.idUsuario', '=', $encargado->id)
                    ->where('Permisos.idRol', '=', 4)
                    ->first();

                if ($existe == null) {
                    $rol = new PermisosModelo();
                    $rol->idRol = 4;
                    $rol->idUsuario = $encargado->id;
                    $rol->save();
                }

                $existe = PermisosModelo::where('Permisos.idUsuario', '=', $encargado->id)
                    ->where('Permisos.idRol', '=', 2)
                    ->first();

                if ($existe == null) {
                    $rol = new PermisosModelo();
                    $rol->idRol = 2;
                    $rol->idUsuario = $encargado->id;
                    $rol->save();
                }

                $existe = PermisosModelo::where('Permisos.idUsuario', '=', $encargado->id)
                    ->where('Permisos.idRol', '=', 4)
                    ->first();

                if ($existe == null) {
                    $rol = new PermisosModelo();
                    $rol->idRol = 4;
                    $rol->idUsuario = $encargado->id;
                    $rol->save();
                }

                $post = OrganizacionesModelo::findOrFail($id);
                
                $post->idUsuario = $encargado->id;
                $post->idPiso = $request->txt_id_piso;
                $post->Nombre = $request->txt_nombre;
                $post->NombreEncargado = $request->txt_nombres_encargado;
                $post->save();

                $datos = OrganizacionesModelo::join('Users', 'Users.id', '=', 'Organizaciones.idUsuario')
                    ->join('Pisos', 'Pisos.idPiso', '=', 'Organizaciones.idPiso')
                    ->join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                    ->where('Organizaciones.idOrganizacion', '=', $post->idOrganizacion)
                    ->select(['Organizaciones.Nombre', 'Organizaciones.Estado', 'Organizaciones.NombreEncargado as nombre_encargado', 'Users.email as email_encargado', 'Pisos.Nombre as nombre_piso', 'Pisos.idPiso', 'Organizaciones.idOrganizacion', 'Users.id', 'Edificios.Nombre as nombre_edificio'])
                    ->first();

                $Auditoria = new AuditoriasModelo();
                $Auditoria->idUSuario = \Auth::user()->id;
                $Auditoria->Accion = "Actualización de organización: " . $request->txt_nombre;
                $Auditoria->save();
                DB::commit();
                return response()->json($datos);
            }
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

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $post = OrganizacionesModelo::findOrFail($id);
            $post->Estado = 0;
            $post->save();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->idUSuario = \Auth::user()->id;
            $Auditoria->Accion = "Eliminación de organización: " . $post->Nombre;
            $Auditoria->save();
            DB::commit();
            return response()->json($post);
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
}
