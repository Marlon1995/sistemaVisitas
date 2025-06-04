<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;
use Validator;
use Response;
use View;

//Modelos
use App\PisosModelo;
use App\EdificiosModelo;
use App\AuditoriasModelo;

class PisosControl extends Controller
{
    protected $rules =
        [
            'txt_nombre' => 'required',
            'txt_id_edificio' => 'required'
        ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request) {
            $query1 = trim($request->get('searchText'));
            $datos = PisosModelo::join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
               // ->join('Impresoras', 'Impresoras.idImpresora', '=', 'Pisos.idImpresora')
                ->select(['Pisos.idPiso', 'Pisos.Nombre', 'Pisos.Estado', 'Edificios.Nombre as nombre_edificio', 'Edificios.idEdificio'])
                ->where('Edificios.Estado', '=', 1)
                ->where('Pisos.Estado', '=', 1)
                ->orderBy('Pisos.idPiso', 'desc')
                ->Where(function ($query) use ($query1) {
                    $query
                        ->orwhere('Edificios.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Pisos.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Pisos.Estado', 'LIKE', '%' . $query1 . '%')
                       // ->orwhere('Impresoras.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Pisos.Creacion', 'LIKE', '%' . $query1 . '%');
                })->paginate(20);
           // $impresoras = ImpresorasModelo::where('Impresoras.Estado', '=', 1)->get();
            $edificios = EdificiosModelo::where('Edificios.Estado', '=', 1)->get();
            return view('Pisos.index', ["posts" => $datos, "edificios" => $edificios, "searchText" => $query1]);
        }
    }

    public function pisos_dueno($id, Request $request)
    {
        if ($request) {
            $query1 = trim($request->get('searchText'));
            $datos = PisosModelo::join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
               // ->join('Impresoras', 'Impresoras.idImpresora', '=', 'Pisos.idImpresora')
                ->select(['Pisos.idPiso', 'Pisos.Nombre', 'Pisos.Estado', 'Edificios.Nombre as nombre_edificio', 'Edificios.idEdificio'])
                ->where('Edificios.Estado', '=', 1)
                ->where('Pisos.Estado', '=', 1)
                //->where('Impresoras.Estado', '=', 1)
                ->where('Edificios.idEdificio', '=', $id)
                ->orderBy('Pisos.idPiso', 'desc')
                ->Where(function ($query) use ($query1) {
                    $query
                        ->orwhere('Edificios.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Pisos.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Pisos.Estado', 'LIKE', '%' . $query1 . '%')
                        //->orwhere('Impresoras.Nombre', 'LIKE', '%' . $query1 . '%')
                        ->orwhere('Pisos.Creacion', 'LIKE', '%' . $query1 . '%');
                })->paginate(20);
            //$impresoras = ImpresorasModelo::where('Impresoras.Estado', '=', 1)->get();
            $edificios = EdificiosModelo::where('Edificios.Estado', '=', 1)->get();
            return view('Duenos.pisos', ["idpisos" => $id, "posts" => $datos, "edificios" => $edificios, "searchText" => $query1]);
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
                $post = new PisosModelo();
                $post->Nombre = $request->txt_nombre;
                $post->idEdificio = $request->txt_id_edificio;
                $post->save();

                $datos = PisosModelo::join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                    //->join('Impresoras', 'Impresoras.idImpresora', '=', 'Pisos.idImpresora')
                    ->select(['Pisos.idPiso', 'Pisos.Nombre', 'Pisos.Estado', 'Edificios.Nombre as nombre_edificio', 'Edificios.idEdificio'])->where('Pisos.idPiso', '=', $post->idPiso)->first();

                $Auditoria = new AuditoriasModelo();
                $Auditoria->idUSuario = \Auth::user()->id;
                $Auditoria->Accion = "Registro de piso: " . $request->txt_nombre;
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
            $validator = Validator::make(Input::all(), $this->rules);
            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            } else {
                DB::beginTransaction();
                $post = PisosModelo::findOrFail($id);
                $post->Nombre = $request->txt_nombre;
                $post->idEdificio = $request->txt_id_edificio;
                $post->save();

                $datos = PisosModelo::join('Edificios', 'Edificios.idEdificio', '=', 'Pisos.idEdificio')
                    //->join('Impresoras', 'Impresoras.idImpresora', '=', 'Pisos.idImpresora')
                    ->select(['Pisos.idPiso', 'Pisos.Nombre', 'Pisos.Estado', 'Edificios.Nombre as nombre_edificio', 'Edificios.idEdificio'])->where('Pisos.idPiso', '=', $post->idPiso)->first();

                $Auditoria = new AuditoriasModelo();
                $Auditoria->idUSuario = \Auth::user()->id;
                $Auditoria->Accion = "Actualización de piso: " . $request->txt_nombre;
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
            $post = PisosModelo::findOrFail($id);
            $post->Estado = 0;
            $post->save();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->idUSuario = \Auth::user()->id;
            $Auditoria->Accion = "Eliminación de piso: " . $post->Nombre;
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
