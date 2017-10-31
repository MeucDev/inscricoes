<?php

use Illuminate\Database\Seeder;
use App\Pessoa;
use App\Inscricoess;


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
            if (!App\Inscricoes::where('pessoa_id', $pessoa->id)->exists())
                continue;

            $inscricao = App\Inscricoes::where('pessoa_id', $pessoa->id)->first();

            $inscricao->alojamento = $pessoa->alojamento;
            $inscricao->equipeRefeicao = $pessoa->equipeRefeicao;
            $inscricao->refeicao = $pessoa->refeicao;
            $inscricao->presencaConfirmada = $pessoa->presencaConfirmada;
            $inscricao->save();

            $dependentes = $pessoa->dependentes;
            foreach ($dependentes as $dependente){
                if (App\Inscricoes::where('pessoa_id', $dependente->id)->exists())
                    continue;

                $inscricaoDependente = new App\Inscricoes;
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
}
