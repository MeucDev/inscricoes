<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Valor;
use Exception;
use Illuminate\Support\Facades\DB;

class Inscricao extends Model
{
    protected $primaryKey = 'numero';
    public $timestamps = false;
    protected $table = 'inscricoes';

    public function dependentes()
    {
        return $this->hasMany('App\Inscricao', 'numero_inscricao_responsavel', 'numero');
    }

    public function pessoa()
    {
        return $this->belongsTo('App\Pessoa', 'pessoa_id');
    }

    public function evento()
    {
        return $this->belongsTo('App\Evento', 'evento_id');
    }

    public function populate($pessoa, $evento, $inicial){
        $this->dataInscricao = date("Y-m-d h:i:s");
        $this->ano = date("Y");
        $this->tipoInscricao = "NORMAL";
        $this->pessoa_id = $pessoa->id;
        $this->alojamento = $pessoa->alojamento;
        $this->equipeRefeicao = $pessoa->equipeRefeicao;
        $this->refeicao = $pessoa->refeicao;
        $this->evento_id = $evento;

        if ($inicial){
            $this->inscricaoPaga = 0;
            $this->presencaConfirmada = 0;
            $this->valorInscricao = Pessoa::getValorInscricao($pessoa, $evento);
            $this->valorInscricaoPago = 0;
            $this->valorTotalPago = 0;
        }

        $this->valorRefeicao = Pessoa::getValorRefeicao($pessoa, $evento);
        $this->valorAlojamento = Pessoa::getValorAlojamento($pessoa, $evento);
        $this->valorTotal = $this->getValorTotal();
    }

    public function calcularValores(){
        $this->valorInscricao = Pessoa::getValorInscricao($this->pessoa, $this->evento_id);
        $this->valorRefeicao = Valor::getValor($this->refeicao, $this->evento_id, $this->pessoa);
        $this->valorAlojamento = Valor::getValor($this->alojamento, $this->evento_id, $this->pessoa);;
        $this->valorTotal = $this->getValorTotal();
    }

    public function getValorTotal(){
        return $this->valorInscricao + $this->valorRefeicao + $this->valorAlojamento;
    }

    public function getValores(){
        
        $valores = (object)[];
        $valores->inscricao = floatval($this->valorInscricao);
        $valores->alojamento = floatval($this->valorAlojamento);
        $valores->refeicao = floatval($this->valorRefeicao);
        $valores->total = $valores->inscricao + $valores->alojamento + $valores->refeicao;    
        return $valores;
    }

    public function getValoresCobrarBoleto($codigos) {
        $result = Valor::getValoresCobrarBoleto($codigos, $this->evento_id);

        return $result;
    }

    public static function criarInscricao($pessoa, $evento){
        $inscricao = Inscricao::where("pessoa_id", $pessoa->id)
            ->where("evento_id", $evento)
            ->first();
        
        if ($inscricao){
            if ($inscricao->inscricaoPaga)
                throw new Exception("Já existe uma inscrição para o evento no seu nome. Entre em contato com a organização do evento");

            Inscricao::where('numero_inscricao_responsavel', $inscricao->numero)->delete();
        }else{
            $inscricao = new Inscricao;
        }

        $inscricao->populate($pessoa, $evento, true);
        $inscricao->save();

        if ($pessoa->conjuge){
            $inscricaoConjuge = new Inscricao;
            $inscricaoConjuge->populate($pessoa->conjuge, $evento, true);
            $inscricao->dependentes()->save($inscricaoConjuge);
        }

        foreach ($pessoa->dependentes as $key => $dependente) {
            if ($dependente->inativo == 1)
                continue;

            $inscricaoDependente = new Inscricao;
            $inscricaoDependente->populate($dependente, $evento, true);
            $inscricao->dependentes()->save($inscricaoDependente);
        }

        $inscricao->calcularTotais();
        $inscricao->save();

        return $inscricao;
    }  

    public static function escolherEquipe($inscricao){
        $equipes = DB::table('inscricoes')
            ->select(
                DB::raw("SUM(case when equipeRefeicao = 'QUIOSQUE_A' then 1 else 0 end) as QUIOSQUE_A"),
                DB::raw("SUM(case when equipeRefeicao = 'QUIOSQUE_B' then 1 else 0 end) as QUIOSQUE_B"),
                DB::raw("SUM(case when equipeRefeicao = 'LAR_A' then 1 else 0 end) as LAR_A"),
                DB::raw("SUM(case when equipeRefeicao = 'LAR_B' then 1 else 0 end) as LAR_B"))
            ->where("presencaConfirmada", 1)
            ->where("evento_id", $inscricao->evento_id)
            ->first();
    
        if (substr( $inscricao->refeicao, 0, 8 ) == "QUIOSQUE"){
            if ($equipes->QUIOSQUE_A > $equipes->QUIOSQUE_B)
                return "QUIOSQUE_B";
            else
                return  "QUIOSQUE_A";
        }
        else if (substr( $inscricao->refeicao, 0, 3 ) == "LAR"){
            if ($equipes->LAR_A > $equipes->LAR_B)
                return "LAR_B";
            else
                return "LAR_A";
        }        
        return "";
    }
    public static function alterarInscricao($id, $pessoa){
        $inscricao = Inscricao::findOrFail($id);

        $evento = $inscricao->evento_id;

        $inscricao->populate($pessoa, $evento, false);
        $inscricao->save();
    
        $conjuge = $pessoa->conjuge;
        if ($conjuge){
            $inscricaoConjuge = $inscricao->dependentes->first(function($item) use ($conjuge) {
                return ($item->pessoa_id == $conjuge->id);
            });
            if (!$inscricaoConjuge){
                $inscricaoConjuge = new Inscricao;
            }

            $inscricaoConjuge->populate($pessoa->conjuge, $evento, false);
            $inscricao->dependentes()->save($inscricaoConjuge);
        }

        foreach ($pessoa->dependentes as $key => $dependente) {
            if ($dependente->inativo == 1)
                continue;

            $inscricaoDependente = $inscricao->dependentes->first(function($item) use ($dependente) {
                return ($item->pessoa_id == $dependente->id);
            });

            if (!$inscricaoDependente){
                $inscricaoDependente = new Inscricao;
            }
                    
            $inscricaoDependente->populate($dependente, $evento, false);
            $inscricao->dependentes()->save($inscricaoDependente);
        }

        $inscricao->calcularTotais();
        $inscricao->save();

        return $inscricao;
    }      
    
    public function calcularTotais(){
        $totalDependentes = 0;
        foreach ($this->dependentes as $dependente) {
            $totalDependentes += $dependente->getValorTotal();
        }

        $this->valorTotal = $this->getValorTotal() + $totalDependentes;
    }

    public function calcularTotalPago(){
        $totalDependentes = 0;
        foreach ($this->dependentes as $dependente) {
            if ($dependente->presencaConfirmada)
                $totalDependentes += $dependente->getValorTotal();
        }

        $totalResponsavel = $this->valorInscricao;

        if ($this->presencaConfirmada)
            $totalResponsavel = $this->getValorTotal();

        $this->valorTotalPago = $totalResponsavel + $totalDependentes;
    }
    
}


