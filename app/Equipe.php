<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipe extends Model
{
    protected $table = 'equipes';
    //

    public function evento()
    {
        return $this->belongsTo('App\Evento', 'evento_id');
    }

    public function membros()
    {
        return $this->hasMany('App\EquipeMembro', 'equipe_id', 'id');
    }
}
