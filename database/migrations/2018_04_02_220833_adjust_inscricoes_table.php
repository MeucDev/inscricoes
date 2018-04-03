<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Inscricao;
use App\Pessoa;

class AdjustInscricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $inscricoes = Inscricao::with("pessoa")
        ->where("evento_id", 3)
        ->whereNull("numero_inscricao_responsavel")
        ->get();

        foreach ($inscricoes as $inscricao) {
            Inscricao::alterarInscricao($inscricao->numero, $inscricao->pessoa);
            echo $inscricao->pessoa->nome . " \n ";
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
