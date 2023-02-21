<?php

namespace App\Http\Controllers;

use App\Equipe;
use App\EquipeMembro;
use App\Evento;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EquipesController extends Controller
{
    public function imprimirCrachas($id)
    {
        $equipe = Equipe::find($id);

        $evento = Evento::findOrFail($equipe->evento_id);
        $equipe->evento = $evento->nome;

        foreach($equipe->membros as $membro) {
            $membro->imprimir = true;
        }
        $result = (object) $equipe;
        return $result;
    }
}
