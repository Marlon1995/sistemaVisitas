<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
use DB;
use redirect;
use Response;
use Mail;
use Hash;
use App\AuditoriasModelo;
use App\PermisosModelo;
use App\RolesModelo;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use  ThrottlesLogins;


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->middleware('guest', ['except' => 'getLogout']);

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */


//login

    protected function getLogin()
    {
        return view('auth/login')->with('mensajelogin', '');
    }

    public function postLogin(Request $request)
    {

        try {
            $roles = PermisosModelo::join('Users', 'Users.id', 'Permisos.idUsuario')
                ->where('Users.email', '=', $request->username)
                ->whereIn('Permisos.idRol', [1, 3, 4, 5])->count();

            if ($roles > 0) {
                if (Auth::attempt(['UserName' => $request->username, 'password' => $request->password], $request->has('remember'))) {
                    AuthController::auditoria("Accedio al sistema: " . $request->username, \Auth::user()->id);
                }
            }
            flash("Error de usuario o contraseña")->error();
            return redirect('login');
        } catch (\Exception $e) {
            DB::rollback();
            DB::beginTransaction();
            $Auditoria = new AuditoriasModelo();
            $Auditoria->Accion = $e->getMessage();
            $Auditoria->save();
            DB::commit();
            flash($e->getMessage())->error();

            return redirect('login');
        }

    }


    protected function getLogout()
    {
        AuthController::auditoria("Salio del sistema:" . \Auth::user()->name, \Auth::user()->id);
        $this->auth->logout();
        Session::flush();
        return redirect('login');
    }

    public function auditoria($Accion, $Usuario)
    {
        DB::beginTransaction();
        $Auditoria = new AuditoriasModelo();
        $Auditoria->idUSuario = $Usuario;
        $Auditoria->Accion = $Accion;
        $Auditoria->save();
        DB::commit();
    }


}
