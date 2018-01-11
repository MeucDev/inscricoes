<?php

namespace App\Http\Controllers;

use App\Evento;
use App\Http\Controllers\Controller;

class EventosController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id, $nome = null)
    {
        return view('evento', ['evento' => Evento::findOrFail($id)]);
    }
}