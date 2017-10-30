<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    public $timestamps = false;


    public function dependentes()
    {
        return $this->hasMany('App\Pessoa', 'responsavel_id', 'id');
    }
}
