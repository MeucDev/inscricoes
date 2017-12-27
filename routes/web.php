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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/eventos', function () {
    return view('eventos');
});


Route::get('/evento/{id}/{nome?}', function ($id, $nome = null) {
    return view('evento-detalhe');
})->where([ 'id' => '[0-9]+', 'nome' => '[a-zA-Z0-9-]+' ]);