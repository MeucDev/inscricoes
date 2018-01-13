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

Route::get('pessoas/{cpf}/{evento}', 'PessoasController@show');

Route::get('/eventos', function () {
    return view('eventos');
});

Route::get('/eventos/{id}/{nome?}', 'EventosController@show')
    ->where([ 'id' => '[0-9]+', 'nome' => '[a-zA-Z0-9-]+' ]);

Route::get('/pagamentos/{id}/{segredo}', function ($id, $nome = null) {
    return view('pagamento');
})->where([ 'id' => '[0-9]+', 'segredo' => '[a-zA-Z0-9]+' ]);