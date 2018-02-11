<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    public function toUI(){
        $this->periodo = strftime('%d', strtotime($this->data_inicio)) . strftime(' a %d de %B de %Y', strtotime($this->data_fim));
        $this->data_inicio = strtotime($this->data_inicio);
        $this->data_fim = strtotime($this->data_fim);
    }

    public function encerrado(){
        $dateNow = date("Y-m-d");
        
        if (dateNow >= $this->data_inicio)
            return true;

        return false;
    }

}
