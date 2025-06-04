<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;
use Validator;
use Response;
use View;

//Modelos
use App\ImpresorasModelo;
use App\AuditoriasModelo;

class ImpresorasControl extends Controller
{
    protected $rules =
        [
            'txt_nombre_impresora' => 'required'
        ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request) {
            $query1 = trim($request->get('searchText'));
            $datos = ImpresorasModelo::where('Impresoras.Estado', '=', 1)
                ->orderBy('Impresoras.idImpresora', 'desc')
                ->Where(function ($query) use ($query1) {
                    $query
                        ->orwhere('Impresoras.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Impresoras.Descripcion', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Impresoras.Estado', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Impresoras.Creacion', 'LIKE', '%' . $query1 . '%');
                })->paginate(20);
            return view('Impresoras.index', ["posts" => $datos, "searchText" => $query1]);
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
                $post = new ImpresorasModelo();
                $post->Nombre = $request->txt_nombre_impresora;
                $post->Descripcion = $request->txt_descripcion_impresora;
                $post->save();
                $Auditoria = new AuditoriasModelo();
                $Auditoria->idUSuario = \Auth::user()->id;
                $Auditoria->Accion = "Registro de impresora: " . $request->txt_nombre_impresora;
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
                $post = ImpresorasModelo::findOrFail($id);
                $post->Nombre = $request->txt_nombre_impresora;
                $post->Descripcion = $request->txt_descripcion_impresora;
                $post->save();

                $Auditoria = new AuditoriasModelo();
                $Auditoria->idUSuario = \Auth::user()->id;
                $Auditoria->Accion = "Actualización de impresora: " . $request->txt_nombre_impresora;
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
            $post = ImpresorasModelo::findOrFail($id);
            $post->Estado = 0;
            $post->save();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->idUSuario = \Auth::user()->id;
            $Auditoria->Accion = "Eliminación de impresora: " . $post->Nombre;
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
