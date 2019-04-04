<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('bienvenide');
});

Route::auth();

Route::get('/home', 'HomeController@index');

Route::auth();

Route::get('/home', 'HomeController@index');

Route::any('/externos/identificarse', 'ExternosController@anyIdentificarse')->name('externos.identificarse');
Route::any('/externos/authenticate', 'ExternosController@anyAuthenticate')->name('externos.authenticate');
Route::any('/externos/registrate', 'ExternosController@anyRegistrate')->name('externos.registrate');
Route::get('/externos/privacidad', 'ExternosController@anyPrivacidad')->name('externos.privacidad');
Route::any('/externos/lugares', 'ExternosController@anyLugares')->name('externos.lugares');
Route::get('/externos/visita/{slug?}', 'ExternosController@getVisita')->name('externos.visita');
Route::any('/externos/timbre/{id?}', 'ExternosController@anyTimbre')->name('externos.timbre');
Route::any('/externos/fcm', 'ExternosController@anyFcm')->name('externos.fcm');
Route::any('/externos/timbre_update', 'ExternosController@anyTimbreupdate')->name('externos.timbre_update');
Route::any('/externos/timbre_delete', 'ExternosController@anyTimbredelete')->name('externos.timbre_delete');
Route::get('/de/{slug?}', 'ExternosController@getVisita')->name('de');
Route::any('/externos/cuenta', 'ExternosController@anyCuenta')->name('externos.cuenta');
Route::any('/externos/historial', 'ExternosController@anyHistorial')->name('externos.historial');
Route::any('/externos/responder', 'ExternosController@anyResponder')->name('externos.responder');
Route::any('/externos/respuesta', 'ExternosController@anyRespuesta')->name('externos.respuesta');
