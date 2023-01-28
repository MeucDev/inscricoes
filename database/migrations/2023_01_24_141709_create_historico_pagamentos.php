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
            $table->integer('inscricao_numero')->unsigned();
            $table->foreign('inscricao_numero')->references('numero')->on('inscricoes');
            $table->float('valor');
            $table->string('pagseguro_code')->nullable();
			$table->timestamps();
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
