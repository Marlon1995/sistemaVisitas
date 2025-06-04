<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AuditoriasControl extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request) {
            $query = trim($request->get('searchText'));
            $query = ($query == "SISTEMA") ? NULL : $query;
            $datos = DB::table('Auditorias')
                ->select('Auditorias.id', 'Users.name', 'Auditorias.Accion', 'Auditorias.Creacion')
                ->leftJoin('Users', 'Users.id', '=', 'Auditorias.idUsuario')
                ->orwhere('Users.name', 'LIKE', '%' . $query . '%')
                ->orwhere('Auditorias.Accion', 'LIKE', '%' . $query . '%')
                ->orwhere('Auditorias.Creacion', 'LIKE', '%' . $query . '%')
                ->orderBy('Auditorias.id', 'desc')->paginate(20);
            return view('Auditorias.index', ["datos" => $datos, "searchText" => $query, "section" => "auditoria"]);
        }
    }
}
