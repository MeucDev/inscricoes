<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameMeucRenomeandoTabelas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename("boleto", "boletos");
        Schema::rename("desconto", "descontos");
        Schema::rename("inscricao", "inscricoes");

        Schema::table('pessoa', function($table)
        {
            $table->dropForeign('FK8E48FBC77CD8248');
            $table->dropForeign('FK8E48FBC77ED99568');
        });
        
        Schema::rename("pessoa", "pessoas");

        Schema::table('pessoas', function($table)
        {
            $table->foreign('responsavel_id')->references('id')->on('pessoas');
            $table->foreign('conjuge_id')->references('id')->on('pessoas');
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
