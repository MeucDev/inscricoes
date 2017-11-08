<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\Collection;
use App\Pessoas;
use App\Inscricao;

class AdjustGarantirSomenteUmaPessoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        echo "Pessoas excluidas do cadastro de pessoas: \n ";
        $pessoas = App\Pessoa::select('nome')->distinct()->get();
        
        foreach ($pessoas as $pessoa) {
            $this->removePessoaVelha($pessoa["nome"]);
        }
    }

    private function removePessoaVelha($nome)
    {
        $pessoasComMesmoNome = App\Pessoa::where('nome', $nome)->orderBy('id', 'asc')->get();
        
        $cadastroQueVaiPermanecer = $pessoasComMesmoNome->last();
        
        foreach ($pessoasComMesmoNome as $pessoaQueSeraExcluida){
            if ($pessoaQueSeraExcluida->id == $cadastroQueVaiPermanecer->id)
                continue;

            $this->removePessoa($pessoaQueSeraExcluida);
        }
    }    

    private function removePessoa($pessoaQueSeraExcluida)
    {
        if (!App\Pessoa::where('id', $pessoaQueSeraExcluida->id)->exists())    
            return;

        if (App\Pessoa::where('conjuge_id', $pessoaQueSeraExcluida->id)->exists()){
            $responsavel = App\Pessoa::where('conjuge_id', $pessoaQueSeraExcluida->id)->first();
            $this->removePessoa($responsavel);
        }

        $dependentes = $pessoaQueSeraExcluida->dependentes;
        foreach ($dependentes as $dependente){
            $this->removePessoa($dependente);
        }    
        
        $inscricao = App\Inscricao::where('pessoa_id', $pessoaQueSeraExcluida->id)->first();
        
        if ($inscricao)
        {
            $pessoaNova = App\Pessoa::where('nome', $nome)->orderBy('id', 'desc')->first();
            $inscricao->pessoa_id = $pessoaNova->id;
            $inscricao->save();
        }
        
        App\Pessoa::destroy($pessoaQueSeraExcluida->id);
        
        echo $pessoaQueSeraExcluida->nome . " \n ";

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
