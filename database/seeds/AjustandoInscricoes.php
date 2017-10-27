<?php

use Illuminate\Database\Seeder;
use App\Pessoa;
use App\Inscricoe;


class AjustandoInscricoes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pessoas = App\Pessoa::all();
        
        foreach ($pessoas as $pessoa) {
            if (App\Inscricoe::where('responsavel_id', $pessoa->id)->count() == 0)
                continue;

            $inscricao = App\Inscricoe::where('responsavel_id', $pessoa->id)->first();

            $inscricao->alojamento = $pessoa->alojamento;
            $inscricao->equipeRefeicao = $pessoa->equipeRefeicao;
            $inscricao->refeicao = $pessoa->refeicao;
            $inscricao->presencaConfirmada = $pessoa->presencaConfirmada;
            $inscricao->save();
        }
    }
}
