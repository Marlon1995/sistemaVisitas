<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => 'Encargado'], function () {
    Route::get('organizaciones_encargados', 'OrganizacionesControl@OrganizacionesEncargados');
    Route::get('getVisitas/{id}', 'AccesosControl@get_visitas_encargados');
    Route::get('accesos_encargado/{id}', 'AccesosControl@accesos_encargados');
});


Route::group(['middleware' => 'Edificio'], function () {
    Route::get('edificios_duenos', 'EdificiosControl@edificios_duenos');
    Route::post('edificios_duenos/{id_edificio}', 'EdificiosControl@update');
    Route::get('pisos_duenos/{id}', 'PisosControl@pisos_dueno');
    Route::post('pisos_duenos/{id}', 'PisosControl@update');
    Route::get('organizaciones_dueno/{id}', 'OrganizacionesControl@organizaciones_dueno');
    Route::post('organizaciones_dueno/{id}', 'OrganizacionesControl@update');
    Route::get('visitas_edificios_dueno/{id_edificio}', 'AccesosControl@index_dueno');
    Route::get('mostrar_visita_dueno/{id}', 'AccesosControl@show_dueno');
    Route::resource('impresoras', 'ImpresorasControl');
    Route::post('impresoras/changeStatus', array('as' => 'changeStatus', 'uses' => 'ImpresorasControl@changeStatus'));
});


Route::group(['middleware' => 'SuperAdmin'], function () {
    Route::resource('usuarios', 'UsuarioControl');
    Route::post('usuarios/changeStatus', array('as' => 'changeStatus', 'uses' => 'UsuarioControl@changeStatus'));
    Route::get('cambiar_admin/{id}', 'UsuarioControl@cambiar_index_administrador');
    Route::post('cambiar_admin/{id}', 'UsuarioControl@cambiar_post_administrador');
    Route::get('auditorias', 'AuditoriasControl@index');
    Route::get('reporte', 'ReporteControl@index');
    Route::post('permisos_eliminar', 'UsuarioControl@PermisoEliminar');
    Route::post('permisos_agregar', 'UsuarioControl@PermisosAgregar');
    Route::resource('edificios', 'EdificiosControl');
    Route::post('edificios/changeStatus', array('as' => 'changeStatus', 'uses' => 'EdificiosControl@changeStatus'));
    Route::resource('organizaciones', 'OrganizacionesControl');
    Route::post('organizaciones/changeStatus', array('as' => 'changeStatus', 'uses' => 'OrganizacionesControl@changeStatus'));
    Route::resource('pisos', 'PisosControl');
    Route::post('pisos/changeStatus', array('as' => 'changeStatus', 'uses' => 'PisosControl@changeStatus'));
    Route::resource('asignaciones', 'AsignacionEdificioControl');
    Route::post('asignaciones/changeStatus', array('as' => 'changeStatus', 'uses' => 'AsignacionEdificioControl@changeStatus'));
    //Route::get('asignacion_puerta', 'AsignacionEdificioControl@index_puerta');

    Route::resource('dueno', 'DuenoEdificioControl');
    Route::post('dueno/changeStatus', array('as' => 'changeStatus', 'uses' => 'DuenoEdificioControl@changeStatus'));
    //Route::get('asignacion_dueno', 'DuenoEdificioControl@index_dueno');

});

Route::group(['middleware' => 'auth'], function () {
    Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);
    Route::get('cambiar', 'UsuarioControl@cambiar_index');
    Route::post('cambiar', 'UsuarioControl@cambiar_post');
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('home', 'HomeController@index')->name('home');
    Route::post('reporte_documento', 'PDFControl@crear_reporte_organizacion');
    Route::get('dashboard', 'GraficosControl@index');
        Route::resource('personas', 'PersonasControl');
    Route::post('personas/changeStatus', array('as' => 'changeStatus', 'uses' => 'PersonasControl@changeStatus'));
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('login', 'Auth\AuthController@getLogin');
    Route::post('login', ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);
    Route::get('/', 'Auth\AuthController@getLogin');
});

Route::group(['middleware' => 'Puerta'], function () {
    Route::get('visitas_edificios/{id_edificio}', 'AccesosControl@index');
    Route::post('crear_fecha_visita', 'AccesosControl@crear_visita');
    Route::get('mostrar_visita/{id}', 'AccesosControl@show');
    Route::post('anular_visita/{id}', 'AccesosControl@destroy');
    Route::post('crear_visita/{id}', 'AccesosControl@store');
    Route::get('edificios_puerta', 'EdificiosControl@edificios_puerta');
});





//Cualquier usuario logeado














