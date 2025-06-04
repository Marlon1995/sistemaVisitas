<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;
use Validator;
use Response;
use View;

//Modelos
use App\PersonasModelo;
use App\AuditoriasModelo;
use App\PermisosModelo;

class PersonasControl extends Controller
{
    protected $rules =
        [
            'txt_nombre_persona' => 'required',
            'txt_cedula_persona' => 'required'
        ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
            $permisos =PermisosModelo::where('Permisos.idUsuario', '=', \Auth::user()->id)
            ->select(['Permisos.idRol'])
            ->get();

        if ($request) {
            $query1 = trim($request->get('searchText'));
            $datos = PersonasModelo::where('Personas.Estado', '=', 1)
                ->orderBy('Personas.idPersona', 'desc')
                ->Where(function ($query) use ($query1) {
                    $query
                        ->orwhere('Personas.Nombres', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Personas.Cedula', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Personas.Direccion', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Personas.Sexo', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Personas.Estado', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Personas.Creacion', 'LIKE', '%' . $query1 . '%');
                })->paginate(20);
            return view('Personas.index', ["permisos"=>$permisos,"posts" => $datos, "searchText" => $query1]);
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
                $post = new PersonasModelo();
                $post->Nombres = $request->txt_nombre_persona;
                $post->Cedula = $request->txt_cedula_persona;
                $post->Sexo = $request->txt_sexo_persona;
                $post->Direccion = $request->txt_direccion_persona;
                $post->save();
                $Auditoria = new AuditoriasModelo();
                $Auditoria->idUSuario = \Auth::user()->id;
                $Auditoria->Accion = "Registro de persona: " . $request->txt_nombre_persona;
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
                $post = PersonasModelo::findOrFail($id);
                $post->Nombres = $request->txt_nombre_persona;
                $post->Cedula = $request->txt_cedula_persona;
                $post->Sexo = $request->txt_sexo_persona;
                $post->Direccion = $request->txt_direccion_persona;
                $post->save();

                $Auditoria = new AuditoriasModelo();
                $Auditoria->idUSuario = \Auth::user()->id;
                $Auditoria->Accion = "Actualización de persona: " . $request->txt_nombre_persona;
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
            $post = PersonasModelo::findOrFail($id);
            $post->Estado = 0;
            $post->save();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->idUSuario = \Auth::user()->id;
            $Auditoria->Accion = "Eliminación de persona: " . $post->Nombres;
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
