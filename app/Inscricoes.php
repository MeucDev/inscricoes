<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inscricoes extends Model
{
    protected $primaryKey = 'numero';
    public $timestamps = false;
    protected $table = 'inscricoes';
}
