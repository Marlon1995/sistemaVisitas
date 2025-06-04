<?php

namespace App\Http\Controllers;

use App\AsignacionEdificioModelo;
use App\DuenoEdificioModelo;
use App\PermisosModelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;
use Validator;
use Response;
use View;

//Modelos
use App\EdificiosModelo;
use App\AuditoriasModelo;


class EdificiosControl extends Controller
{
    protected $rules =
        [
            'txt_nombre_edificio' => 'required',
        ];


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request) {
            $query1 = trim($request->get('searchText'));
            $datos = EdificiosModelo::where('Edificios.Estado', '=', 1)
                ->orderBy('Edificios.idEdificio', 'desc')
                ->Where(function ($query) use ($query1) {
                    $query
                        ->orwhere('Edificios.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Edificios.Estado', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Edificios.Creacion', 'LIKE', '%' . $query1 . '%');
                })->paginate(20);
            return view('Edificios.index', ["posts" => $datos, "searchText" => $query1]);
        }
    }

    public function edificios_duenos(Request $request)
    {
        if ($request) {
            $edificios_dueno = DuenoEdificioModelo::Where('DuenoEdificio.idUsuario', '=', \Auth::user()->id)
                ->select(['DuenoEdificio.idEdificio'])->get();
            $query1 = trim($request->get('searchText'));
            $datos = EdificiosModelo::where('Edificios.Estado', '=', 1);
            if (PermisosModelo::where('idUsuario', '=', \Auth::user()->id)->where('idRol', '=', 1)->count() == 0) {
                $datos = $datos->whereIn('Edificios.idEdificio', $edificios_dueno);
            }
            $datos = $datos->orderBy('Edificios.idEdificio', 'desc')
                ->Where(function ($query) use ($query1) {
                    $query
                        ->orwhere('Edificios.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Edificios.Estado', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Edificios.Creacion', 'LIKE', '%' . $query1 . '%');
                })->paginate(20);
            return view('Duenos.edificios', ["posts" => $datos, "searchText" => $query1]);
        }
    }

    public function edificios_puerta(Request $request)
    {
        if ($request) {
            $edificios_dueno = AsignacionEdificioModelo::Where('AsignacionEdificio.idUsuario', '=', \Auth::user()->id)
                ->select(['AsignacionEdificio.idEdificio'])->get();
            $query1 = trim($request->get('searchText'));
            $datos = EdificiosModelo::where('Edificios.Estado', '=', 1);
            if (PermisosModelo::where('idUsuario', '=', \Auth::user()->id)->where('idRol', '=', 1)->count() == 0) {
                $datos = $datos->whereIn('Edificios.idEdificio', $edificios_dueno);
            }
            $datos = $datos->orderBy('Edificios.idEdificio', 'desc')
                ->Where(function ($query) use ($query1) {
                    $query
                        ->orwhere('Edificios.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Edificios.Estado', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Edificios.Creacion', 'LIKE', '%' . $query1 . '%');
                })->paginate(20);
            return view('Asignaciones.edificios', ["posts" => $datos, "searchText" => $query1]);
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
                $post = new EdificiosModelo();
                $post->Nombre = $request->txt_nombre_edificio;
                $post->save();
                $Auditoria = new AuditoriasModelo();
                $Auditoria->idUSuario = \Auth::user()->id;
                $Auditoria->Accion = "Registro de edificio: " . $request->txt_nombre_edificio;
                $Auditoria->save();
                DB::commit();
                return response()->json($post);
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
            $validator = Validator::make(Input::all(), $this->rules);
            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            } else {
                DB::beginTransaction();
                $post = EdificiosModelo::findOrFail($id);
                $post->Nombre = $request->txt_nombre_edificio;
                $post->save();

                $Auditoria = new AuditoriasModelo();
                $Auditoria->idUSuario = \Auth::user()->id;
                $Auditoria->Accion = "Actualización de edificio: " . $request->txt_nombre_edificio;
                $Auditoria->save();
                DB::commit();
                return response()->json($post);
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
            $post = EdificiosModelo::findOrFail($id);
            $post->Estado = 0;
            $post->save();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->idUSuario = \Auth::user()->id;
            $Auditoria->Accion = "Eliminación de edificio: " . $post->Nombre;
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
