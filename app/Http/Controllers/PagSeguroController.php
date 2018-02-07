<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PagSeguroController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect()
    {
        return "Obrigado";
    }

    public function notification()
    {
        return "Notificado";
    }
}
