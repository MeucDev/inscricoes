<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValorVariacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valor_variacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('valor_id')->unsigned();
            $table->foreign('valor_id')->references('id')->on('valores');
            $table->float('valor');
            $table->date('data_ate')->nullable();
            $table->integer('idade_inicio')->nullable();
            $table->integer('idade_fim')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('valor_itens');
    }
}
