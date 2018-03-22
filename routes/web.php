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
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

//Pessoas
Route::get('/pessoas/{cpf}/{evento}', 'PessoasController@show');

//Valores
Route::post('/valores/{evento}', 'ValoresController@valor');

//Eventos
Route::get('/', 'EventosController@first');
Route::get('/eventos/{id}', 'EventosController@show');
Route::get('/eventos', function () { return view('eventos');});

//Inscrições
Route::get('/inscricoes/{id}', 'InscricoesController@show');
Route::get('/inscricoes/{id}/pessoa', 'InscricoesController@pessoa');
Route::post('/inscricoes/{id}/presenca', 'InscricoesController@presenca');
Route::post('/inscricoes/criar/{evento}', 'InscricoesController@criar');
Route::put('/inscricoes/{id}', 'InscricoesController@alterar');

//Pagseguro
Route::post('/pagseguro/notification', [
    'uses' => '\laravel\pagseguro\Platform\Laravel5\NotificationController@notification',
    'as' => 'pagseguro.notification',
]);

Route::get('/pagseguro/redirect', [
    'uses' => 'PagSeguroController@redirect',
    'as' => 'pagseguro.redirect',
]);
