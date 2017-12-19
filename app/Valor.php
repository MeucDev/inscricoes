<?php namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Valor extends Model
{
    public $timestamps = false;
    protected $table = 'valores';

    public function variacoes()
    {
        return $this->hasMany('App\Variacao', 'valor_id', 'id');
    }
    
    public function categoria()
    {
        return $this->belongsTo('App\Categoria');
    }

    public static function getValoresAgrupadosPorCategoria(){
        $valores = DB::table('valores')
            ->join('categorias', 'valores.categoria_id', '=', 'categorias.id')
            ->select('valores.nome', 'valores.codigo', 'valores.valor', 'categorias.codigo as categoriaCodigo', 'categorias.nome as categoria')
            ->get();
		return $valores->groupBy('categoria')->toArray();
    }
}
