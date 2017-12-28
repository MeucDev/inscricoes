<?php namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Variacao;

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


    public static function getValor($codigo, $evento, $pessoa){
        $valor = Valor::where("evento_id", $evento)
        ->where("codigo", $codigo)
        ->first();

        if ($valor->variacoes->count() == 0)
            return $valor->valor;

        $valorVariacao = Variacao::getValor($valor->id, $pessoa);
        if ($valorVariacao)
            return $valorVariacao;
        else
            return $valor->valor;
    }

    public static function getValoresAgrupadosPorCategoria($evento){
        $valores = DB::table('valores')
            ->where('evento_id', $evento)
            ->join('categorias', 'valores.categoria_id', '=', 'categorias.id')
            ->select('valores.nome', 'valores.codigo', 'valores.valor', 'categorias.codigo as categoriaCodigo', 'categorias.nome as categoria')
            ->get();
		return $valores->groupBy('categoria')->toArray();
    }
}
