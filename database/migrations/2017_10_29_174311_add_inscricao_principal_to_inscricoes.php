<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInscricaoPrincipalToInscricoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->integer('numero_inscricao_responsavel')->nullable();
            $table->foreign('numero_inscricao_responsavel')->references('numero')->on('inscricoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
