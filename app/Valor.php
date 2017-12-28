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
        return $this->hasMany('App\Variacao', 'valor_id', 'id')->orderBy('data_ate');
    }
    
    public function categoria()
    {
        return $this->belongsTo('App\Categoria');
    }

    public static function getValor($codigo, $evento, $pessoa){
        $valor = Valor::where("evento_id", $evento)
        ->where("codigo", $codigo)
        ->first();

        if (!$valor)
            return 0;

        $valorVariacao = $valor->getValorVariacao($pessoa);
        
        if ($valorVariacao)
            return $valorVariacao;
        else
            return $valor->valor;
    }

    public function getValorVariacao($pessoa){
        $dateNow = date("Y-m-d");

        $variacoes = Variacao::where("valor_id", $this->id)
            ->orderBy('data_ate')
            ->get();

        foreach ($this->variacoes as $variacao) {
            if ($variacao->data_ate && $dateNow <= $variacao->data_ate)
                return $variacao->valor;
            
            if ($pessoa->idade >= $variacao->idade_inicio && $pessoa->idade <= $variacao->idade_fim)
                return $variacao->valor;
        }

        return null;
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
