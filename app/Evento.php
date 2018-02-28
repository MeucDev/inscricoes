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

}
