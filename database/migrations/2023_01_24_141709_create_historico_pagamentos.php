<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricoPagamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_pagamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('operacao');
            $table->integer('inscricao_numero')->nullable();
            $table->float('valor');
            $table->string('pagseguro_code')->nullable();
			$table->timestamps();
        });

        Schema::table('historico_pagamentos', function (Blueprint $table) {
            $table->foreign('inscricao_numero')->references('numero')->on('inscricoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('historico_pagamentos');
    }
}
