<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultasPagamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remover tabela se existir (caso tenha sido criada parcialmente em tentativa anterior)
        Schema::dropIfExists('consultas_pagamento');

        Schema::create('consultas_pagamento', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('inscricao_numero');
            $table->dateTime('ultima_consulta')->nullable();
            $table->dateTime('proxima_consulta');
            $table->integer('tentativas')->default(0);
            $table->integer('intervalo_minutos')->default(0);
            $table->string('status', 20)->default('pendente'); // pendente, processando, pago, cancelado, expirado
            $table->tinyInteger('processando')->default(0);
            $table->text('ultimo_erro')->nullable();
            $table->timestamps();

            $table->unique('inscricao_numero', 'uk_inscricao');
            $table->index(['proxima_consulta', 'status'], 'idx_proxima_consulta');
            $table->index(['status', 'processando'], 'idx_status_processando');
            $table->index(['proxima_consulta', 'status', 'processando'], 'idx_proxima_consulta_status');
        });

        // NOTA: A foreign key foi removida temporariamente devido ao erro 1215 em produção
        // Para criar a foreign key manualmente após verificar a estrutura, veja DIAGNOSTICO_FOREIGN_KEY.md
        // SQL manual:
        // ALTER TABLE consultas_pagamento 
        // ADD CONSTRAINT consultas_pagamento_inscricao_numero_foreign 
        // FOREIGN KEY (inscricao_numero) REFERENCES inscricoes(numero) ON DELETE CASCADE;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('consultas_pagamento');
    }
}
