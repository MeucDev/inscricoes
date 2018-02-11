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

Route::get('/pessoas/{cpf}/{evento}', 'PessoasController@show');

Route::post('/valores/{evento}', 'ValoresController@valor');


Route::get('/', 'EventosController@first');
Route::get('/eventos/{id}', 'EventosController@show');
Route::post('/eventos/{id}/inscricao', 'EventosController@fazerInscricao');
Route::get('/eventos', function () {
    return view('eventos');
});

Route::post('/pagseguro/notification', [
    'uses' => '\laravel\pagseguro\Platform\Laravel5\NotificationController@notification',
    'as' => 'pagseguro.notification',
]);

Route::get('/pagseguro/redirect', [
    'uses' => 'PagSeguroController@redirect',
    'as' => 'pagseguro.redirect',
]);
