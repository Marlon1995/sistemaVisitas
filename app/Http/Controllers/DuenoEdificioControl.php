<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;
use Validator;
use Response;
use View;
use App\PermisosModelo;
use App\User;
use App\DuenoEdificioModelo;
use App\EdificiosModelo;
use App\AuditoriasModelo;

class DuenoEdificioControl extends Controller
{

    protected $rules1 =
        [
            'edificio' => 'required',
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
            $datos = User::join('Permisos', 'Permisos.idUsuario', '=', 'Users.id')
                ->join('Roles', 'Roles.idRol', '=', 'Permisos.idRol')
                ->select(['Users.id', 'Users.name', 'Users.email', 'Users.UserName', 'Users.Estado'])
                ->Where('Roles.idRol', '=', 3)
                ->where('Users.Estado', '=', 1)
                ->distinct()
                ->orderBy('Users.id', 'desc')
                ->Where(function ($query) use ($query1) {
                    $query
                        ->orwhere('Roles.Rol', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Users.id', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Users.name', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Users.email', 'LIKE', '%' . $query1 . '%');
                })->paginate(50);
            return view('AsignacionesDueno.index', ["posts" => $datos, "searchText" => $query1, "Titulo" => "Encargado Edificio"]);
        }
    }
    /*

    public function index_dueno(Request $request)
    {
        if ($request) {
            $query1 = trim($request->get('searchText'));
            $datos = User::join('Permisos', 'Permisos.idUsuario', '=', 'Users.id')
                ->join('Roles', 'Roles.idRol', '=', 'Permisos.idRol')
                ->select(['Users.id', 'Users.name', 'Users.email', 'Users.UserName', 'Users.Estado'])
                ->Where('Roles.idRol', '=', 5)
                ->where('Users.Estado', '=', 1)
                ->distinct()
                ->orderBy('Users.id', 'desc')
                ->Where(function ($query) use ($query1) {
                    $query
                        ->orwhere('Roles.Rol', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Users.id', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Users.name', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Users.email', 'LIKE', '%' . $query1 . '%');
                })->paginate(50);
            return view('AsignacionesDueno.index', ["posts" => $datos, "searchText" => $query1, "Titulo" => "Encargado Puerta"]);
        }
    }
*/
    public function show($id)
    {
        $post = User::findOrFail($id);
        $edificios_asignados = DuenoEdificioModelo::Where('DuenoEdificio.idUsuario', '=', $post->id);

        $existe = $edificios_asignados->select(['DuenoEdificio.idEdificio'])->get();

        $edificios = EdificiosModelo::whereNotIn('Edificios.idEdificio', $existe)->get();

        $edificios_asignados = $edificios_asignados
            ->join('Edificios', 'Edificios.idEdificio', '=', 'DuenoEdificio.idEdificio')
            ->select(['DuenoEdificio.idDuenoEdificio', 'Edificios.Nombre'])->distinct()->get();
        return view('AsignacionesDueno.show', ["Usuario" => $post, "Edificios" => $edificios, "Asignados" => $edificios_asignados]);

    }

    public function update(Request $request, $id)
    {


    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make(Input::all(), $this->rules1);
            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            } else {

                if (!($this->Existe_Edificio($request->edificio, $request->id_usuario))) {
                    DB::beginTransaction();

                    $Asignacion = new DuenoEdificioModelo();
                    $Asignacion->idUsuario = $request->id_usuario;
                    $Asignacion->idEdificio = $request->edificio;
                    $Asignacion->save();

                    $asignacionesx = DuenoEdificioModelo::join('Users', 'Users.id', '=', 'DuenoEdificio.idUsuario')
                        ->join('Edificios', 'Edificios.idEdificio', '=', 'DuenoEdificio.idEdificio')
                        ->where('DuenoEdificio.idDuenoEdificio', '=', $Asignacion->idDuenoEdificio)
                        ->select(['Users.name', 'Users.UserName', 'Edificios.Nombre', 'DuenoEdificio.idDuenoEdificio'])->first();
                    $Auditoria = new AuditoriasModelo();
                    $Auditoria->idUsuario = \Auth::user()->id;
                    $Auditoria->Accion = "El Usuario: " . \Auth::user()->name . " Ha agregado el edificio - " . $asignacionesx->Nombre . " - al usuario : " . $asignacionesx->name . " " . $asignacionesx->UserName;
                    $Auditoria->save();
                    DB::commit();
                    return response()->json($asignacionesx);
                }
                return response()->json(array('errors' => ["edificio" => "Usario ya tiene ese edificio agregado"]));
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

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $post = DuenoEdificioModelo::findOrFail($id);

            $asignacionesx = DuenoEdificioModelo::join('Users', 'Users.id', '=', 'DuenoEdificio.idUsuario')
                ->join('Edificios', 'Edificios.idEdificio', '=', 'DuenoEdificio.idEdificio')
                ->where('DuenoEdificio.idDuenoEdificio', '=', $post->idDuenoEdificio)
                ->select(['Users.name', 'Users.UserName', 'Edificios.Nombre', 'DuenoEdificio.idDuenoEdificio'])->first();
            $post->delete();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->idUSuario = \Auth::user()->id;
            $Auditoria->Accion = "El Usuario: " . \Auth::user()->name . " Ha agregado el edificio - " . $asignacionesx->Nombre . " - al usuario : " . $asignacionesx->name . " " . $asignacionesx->UserName;
            $Auditoria->save();
            DB::commit();
            return response()->json($asignacionesx);
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

    public function Existe_Edificio($edificio, $usuario)
    {
        $permiso_edificio = DuenoEdificioModelo::where('DuenoEdificio.idUsuario', '=', $usuario)->where('DuenoEdificio.idEdificio', '=', $edificio)->count();
        return ($permiso_edificio == 0 ? false : true);
    }

}
