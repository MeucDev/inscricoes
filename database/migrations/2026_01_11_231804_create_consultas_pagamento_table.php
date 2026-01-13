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

        // Criar foreign key em uma alteração separada (mais compatível)
        Schema::table('consultas_pagamento', function (Blueprint $table) {
            $table->foreign('inscricao_numero')->references('numero')->on('inscricoes')->onDelete('cascade');
        });
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
