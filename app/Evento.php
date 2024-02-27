<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Inscricao;

class Evento extends Model
{
    public function toUI(){
        $this->periodo = strftime('%d', strtotime($this->data_inicio)) . strftime(' a %d de %B de %Y', strtotime($this->data_fim));
        $this->data_inicio = strtotime($this->data_inicio);
        $this->data_fim = strtotime($this->data_fim);
    }

    public function encerrado(){
        $dateNow = date("Y-m-d");
        
        if ($dateNow >= $this->data_inicio)
            return true;

        return false;
    }
    public function breve(){
        $dateNow = date("Y-m-d");
        
        if ($this->aberto == 0 && $dateNow < $this->data_inicio)
            return true;

        return false;
    }

    public function dataProximoLote() {
        $dateNow = date("Y-m-d");
        
        $valor = Valor::where("evento_id", $this->id)
            ->where("codigo", "NORMAL")
            ->get();
        if(count($valor) == 1) {
            $variacoes = Variacao::where("valor_id", $valor[0]->id)
                ->orderBy('data_ate')
                ->get();
                
                
            foreach ($variacoes as $variacao) {
                if ($variacao->data_ate && $dateNow <= date("Y-m-d", strtotime($variacao->data_ate . " + 1 day"))) {
                    return date("d-m-Y", strtotime($variacao->data_ate . " + 1 day"));
                }
                    
            }
        }
        return null;
    }

    public function limite(){
        if ($this->limite_inscricoes == 0)
            return false;

        $numero = Inscricao::whereNull("numero_inscricao_responsavel")
            ->where("evento_id", $this->id)
            ->count();

        if ($numero >= $this->limite_inscricoes)
            return true;
            
        return false;
    }

    public function nome() {
        return $this->nome;
    }

    public function aberto() {
        return $this->aberto == 1;
    }

    public function fila_espera() {
        return $this->fila_espera == 1;
    }

}
