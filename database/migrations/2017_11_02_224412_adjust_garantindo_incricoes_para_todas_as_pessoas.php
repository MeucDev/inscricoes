<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Pessoa;
use App\Inscricao;



class AdjustGarantindoIncricoesParaTodasAsPessoas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $pessoas = App\Pessoa::all();
        
        foreach ($pessoas as $pessoa) {
            if (!App\Inscricao::where('pessoa_id', $pessoa->id)->exists())
                continue;

            $inscricao = App\Inscricao::where('pessoa_id', $pessoa->id)->first();

            $inscricao->alojamento = $pessoa->alojamento;
            $inscricao->equipeRefeicao = $pessoa->equipeRefeicao;
            $inscricao->refeicao = $pessoa->refeicao;
            $inscricao->presencaConfirmada = $pessoa->presencaConfirmada;
            $inscricao->save();

            $dependentes = $pessoa->dependentes;
            foreach ($dependentes as $dependente){
                if (App\Inscricao::where('pessoa_id', $dependente->id)->exists())
                    continue;

                $inscricaoDependente = new App\Inscricao;
                $inscricaoDependente->numero_inscricao_responsavel = $inscricao->id;
                $inscricaoDependente->dataInscricao = $inscricao->dataInscricao;
                $inscricaoDependente->ano = $inscricao->ano;
                $inscricaoDependente->inscricaoPaga = $inscricao->inscricaoPaga;
                $inscricaoDependente->tipoInscricao = $inscricao->tipoInscricao;
                
                $inscricaoDependente->pessoa_id = $dependente->id;
                $inscricaoDependente->alojamento = $dependente->alojamento;
                $inscricaoDependente->equipeRefeicao = $dependente->equipeRefeicao;
                $inscricaoDependente->refeicao = $dependente->refeicao;
                $inscricaoDependente->presencaConfirmada = $dependente->presencaConfirmada;
                $inscricaoDependente->save();
            }
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
