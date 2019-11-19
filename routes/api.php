<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group([
    'prefix' => 'productos',
], function () {

    Route::get('todos', 'ProductoController@todos');
    Route::post('crear', 'ProductoController@crear');
    Route::put('cambiar-estado', 'ProductoController@cambiarEstado');
});



Route::group([
    'prefix' => 'bodegas',
], function () {

    Route::get('todos', 'BodegaController@todos');
});
