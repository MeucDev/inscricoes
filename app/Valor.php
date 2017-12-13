<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Valor extends Model
{
    public $timestamps = false;
    protected $table = 'valores';

    public function variacoes()
    {
        return $this->hasMany('App\Variacao', 'valor_id', 'id');
    }
    
}
