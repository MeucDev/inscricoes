<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LinkInscricao extends Model
{
    protected $table = 'link_inscricoes';
    protected $fillable = [
        'evento_id',
        'token',
        'tipo_inscricao',
        'limite_uso',
        'uso_atual',
        'data_geracao',
        'data_expiracao'
    ];
    
    protected $dates = ['data_geracao', 'data_expiracao'];
    
    /**
     * Relacionamento com Evento
     */
    public function evento()
    {
        return $this->belongsTo('App\Evento', 'evento_id');
    }
    
    /**
     * Relacionamento com Inscrições
     */
    public function inscricoes()
    {
        return $this->hasMany('App\Inscricao', 'link_inscricao_id');
    }
    
    /**
     * Verifica se o link está expirado
     */
    public function isExpirado()
    {
        return Carbon::now()->greaterThan($this->data_expiracao);
    }
    
    /**
     * Verifica se o limite de uso foi atingido
     */
    public function isLimiteAtingido()
    {
        return $this->uso_atual >= $this->limite_uso;
    }
    
    /**
     * Verifica se o link já foi usado (considera expirado ou limite atingido como "usado")
     */
    public function isUsado()
    {
        return $this->isExpirado() || $this->isLimiteAtingido();
    }
    
    /**
     * Verifica se o link é válido e pode ser usado
     */
    public function isValido()
    {
        return !$this->isExpirado() && !$this->isLimiteAtingido();
    }
    
    /**
     * Verifica se pode ser usado para criar nova inscrição
     */
    public function podeSerUsado()
    {
        return $this->isValido();
    }
    
    /**
     * Gera um UUID v4
     */
    public static function gerarToken()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    
    /**
     * Cria um novo link de inscrição
     */
    public static function criar($eventoId, $tipoInscricao, $limiteUso)
    {
        $token = self::gerarToken();
        $dataGeracao = Carbon::now();
        $dataExpiracao = $dataGeracao->copy()->addHours(24);
        
        return self::create([
            'evento_id' => $eventoId,
            'token' => $token,
            'tipo_inscricao' => $tipoInscricao,
            'limite_uso' => $limiteUso,
            'uso_atual' => 0,
            'data_geracao' => $dataGeracao,
            'data_expiracao' => $dataExpiracao
        ]);
    }
    
    /**
     * Incrementa o contador de uso
     */
    public function incrementarUso()
    {
        $this->uso_atual += 1;
        $this->save();
    }
}
