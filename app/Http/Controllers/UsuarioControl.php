<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;
use App\User;
use App\AuditoriasModelo;
use Validator;
use Response;
use View;
use App\RolesModelo;
use Hash;
use App\PermisosModelo;
use App\ImpresorasModelo;

class UsuarioControl extends Controller
{
    protected $rules =
        [
            'UserName' => 'required|min:2|email|unique:Users',
            'password_usuario' => 'required',
            'nombre_usuario' => 'required'
        ];

    protected $rules1 =
        [
            'rol' => 'required',
            'id_usuario' => 'required'
        ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request) {
            $query1 = trim($request->get('searchText'));
            $datos = DB::table('Users')
                ->leftjoin('Permisos', 'Permisos.idUsuario', '=', 'Users.id')
                ->leftjoin('Roles', 'Roles.idRol', '=', 'Permisos.idRol')
                ->select(['Users.id', 'Users.name', 'Users.email', 'Users.UserName', 'Users.Estado'])
                ->distinct()
                ->orderBy('Users.id', 'asc')
                ->Where(function ($query) use ($query1) {
                    $query
                        ->orwhere('Roles.Rol', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Users.id', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Users.name', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Users.email', 'LIKE', '%' . $query1 . '%');
                })->paginate(50);
            return view('Usuarios.index', ["posts" => $datos, "searchText" => $query1]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make(Input::all(), $this->rules);
            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            } else {
                DB::beginTransaction();
                $registro = new User();
                $registro->name = $request->nombre_usuario;
                $registro->email = $request->UserName;
                $registro->UserName = $request->UserName;
                $registro->password = $request->password_usuario;
                $registro->save();

                $rol = new PermisosModelo();
                $rol->idRol = 2;
                $rol->idUsuario = $registro->id;
                $rol->save();

                $Auditoria = new AuditoriasModelo();
                $Auditoria->idUSuario = \Auth::user()->id;
                $Auditoria->Accion = "El Usuario: " . \Auth::user()->name . " a registrado a : " . \Auth::user()->UserName;
                $Auditoria->save();
                DB::commit();
                return response()->json($registro);
            }
        } catch (\Exception $e) {
            DB::rollback();
            DB::beginTransaction();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->Accion = $e->getMessage();
            $Auditoria->save();
            DB::commit();
            return response()->json(array('errors' => $e->getMessage()));

        }
    }

    public function show($id)
    {

        $post = User::findOrFail($id);
        $permisos = PermisosModelo::where('Permisos.idUsuario', '=', $id);
        $existe = $permisos->select(['Permisos.idRol'])->get();

        $roles = RolesModelo::whereNotIn('Roles.idRol', $existe)->where('Roles.idRol', '!=', 1)->get();
        $permisos = $permisos->join('Roles', 'Roles.idRol', '=', 'Permisos.idRol')->select(['Roles.Rol', 'Permisos.idPermiso'])->distinct()->get();
        return view('Usuarios.show', ["Usuario" => $post, "permisos" => $permisos, 'roles' => $roles]);
    }

    public function cambiar_index_administrador($id)
    {
        $impresoras = ImpresorasModelo::where('Impresoras.Estado', '=', 1)->get();

        $post = User::findOrFail($id);
        return view('Usuarios.cambiar_admin', ["impresoras" => $impresoras, "Usuario" => $post]);
    }

    public function cambiar_post_administrador($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $usuario = User::findOrFail($id);
            $usuario->password = Hash::make($request->nueva);
            $usuario->name = ($usuario->id == 1) ? $usuario->name : $request->nombre;
            $usuario->idImpresora = $request->impresora_add;
            $usuario->save();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->idUSuario = \Auth::user()->id;
            $Auditoria->Accion = "El Usuario: " . $usuario->name . " a cambiado su password";
            $Auditoria->save();

            DB::commit();

            flash("El administrador a actualizada su password con exito")->success();
            return redirect('usuarios');
        } catch (\Exception $e) {
            DB::rollback();
            DB::beginTransaction();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->Accion = $e->getMessage();
            $Auditoria->save();
            DB::commit();
            flash($e->getMessage())->error();
            return redirect('cambiar_admin/' . $id);
        }

    }


    public function cambiar_index()
    {
        $post = \Auth::user();
        return view('Usuarios.cambiar', ["Usuario" => $post]);
    }

    public function cambiar_post(Request $request)
    {
        try {
            DB::beginTransaction();
            $post = \Auth::user();
            $usuario = User::findOrFail($post->id);
            $usuario->password = Hash::make($request->nueva);
            $usuario->name = ($post->id == 1) ? $usuario->name : $request->nombre;
            $usuario->save();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->idUSuario = \Auth::user()->id;
            $Auditoria->Accion = "El Usuario: " . $post->name . " a cambiado su password";
            $Auditoria->save();
            DB::commit();

            flash("Password actualizada con exito")->success();
            return redirect('usuarios');
        } catch (\Exception $e) {
            DB::rollback();
            DB::beginTransaction();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->Accion = $e->getMessage();
            $Auditoria->save();
            DB::commit();
            flash($e->getMessage())->error();
            return redirect('cambiar');
        }
    }


    public function create()
    {


    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $post = User::findOrFail($id);
            if ($post->Estado == 0) {
                $post->Estado = 1;
                $operacion = "Activación";
            } else {
                $post->Estado = 0;
                $operacion = "Anulación";
            }

            $post->save();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->idUSuario = \Auth::user()->id;
            $Auditoria->Accion = "El Usuario: " . \Auth::user()->name . " Realizo la " . $operacion . " del usuario: " . $post->name . ' -- ' . $post->email;
            $Auditoria->save();
            DB::commit();
            flash($operacion . " con exito")->success();
            return redirect('usuarios');
        } catch (\Exception $e) {
            DB::rollback();
            DB::beginTransaction();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->Accion = $e->getMessage();
            $Auditoria->save();
            DB::commit();
            flash($e->getMessage())->error();
            return redirect('usuarios');
        }
    }

    public function PermisoEliminar(Request $request)
    {
        try {
            $permiso_defecto = PermisosModelo::join('Roles', 'Roles.idRol', '=', 'Permisos.idRol')->where('Permisos.idPermiso', '=', $request->id)->select('Roles.idRol')->first();

            if ($permiso_defecto->idRol != 2) {
                DB::beginTransaction();
                $permiso = DB::table('Permisos')->join('Users', 'Users.id', '=', 'Permisos.idUSuario')->join('Roles', 'Roles.idRol', '=', 'Permisos.idRol')->where('Permisos.idPermiso', '=', $request->id)->select('Users.name', 'Users.UserName', 'Roles.Rol')->first();
                $post = PermisosModelo::findOrFail($request->id);
                $post->delete();
                $Auditoria = new AuditoriasModelo();
                $Auditoria->idUSuario = \Auth::user()->id;
                $Auditoria->Accion = "El Usuario: " . \Auth::user()->name . " Ha eliminado el permiso - " . $permiso->Rol . " - al usuario : " . $permiso->name . " " . $permiso->UserName;
                $Auditoria->save();
                DB::commit();
                return response()->json($post);
            }
            return response()->json(array('errors' => ["Permiso" => "Permiso Por Defecto"]));
        } catch (\Exception $e) {
            DB::rollback();
            DB::beginTransaction();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->Accion = $e->getMessage();
            $Auditoria->save();
            DB::commit();
            return response()->json(array('errors' => $e->getMessage()));
        }
    }

    public function PermisosAgregar(Request $request)
    {
        try {
            $validator = Validator::make(Input::all(), $this->rules1);
            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            } else {
                if (!($this->Existe_Permiso($request->rol, $request->id_usuario))) {

                    DB::beginTransaction();
                    $permisos = new PermisosModelo();
                    $permisos->idUsuario = $request->id_usuario;
                    $permisos->idRol = $request->rol;
                    $permisos->save();
                    $permiso = DB::table('Permisos')->join('Users', 'Users.id', '=', 'Permisos.idUsuario')->join('Roles', 'Roles.idRol', '=', 'Permisos.idRol')->where('Permisos.idPermiso', '=', $permisos->idPermiso)->select('Users.name', 'Users.UserName', 'Roles.Rol', 'Permisos.idPermiso')->first();

                    $Auditoria = new AuditoriasModelo();
                    $Auditoria->idUSuario = \Auth::user()->id;
                    $Auditoria->Accion = "El Usuario: " . \Auth::user()->name . " Ha agregado el permiso - " . $permiso->Rol . " - al usuario : " . $permiso->name . " " . $permiso->UserName;
                    $Auditoria->save();
                    DB::commit();
                    return response()->json($permiso);
                }
                return response()->json(array('errors' => ["rol" => "Uusario ya tiene ese rol agregado"]));
            }
        } catch (\Exception $e) {
            DB::rollback();
            DB::beginTransaction();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->Accion = $e->getMessage();
            $Auditoria->save();
            DB::commit();
            return response()->json(array('errors' => $e->getMessage()));

        }
    }

    public function Existe_Permiso($rol, $usuario)
    {
        $permiso_defecto = PermisosModelo::where('Permisos.idUsuario', '=', $usuario)->where('Permisos.idRol', '=', $rol)->count();
        return ($permiso_defecto == 0 ? false : true);
    }

}

