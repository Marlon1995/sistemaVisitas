<?php

namespace App\Http\Middleware;

use Closure;
use App\PermisosModelo;

class Edificio
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
         $roles=PermisosModelo::where('Permisos.idUsuario','=',\Auth::user()->id)
        ->whereIn('Permisos.idRol',[3,1])->first();
        if($roles==null)
        {
            flash('No cuenta con suficientes permisos contactese con el administrador')->error();
              return redirect('/home');  
        }
        return $next($request);
    }
}
