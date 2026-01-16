<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkInscricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_inscricoes', function (Blueprint $table) {
            $table->increments('id'); // Isso cria UNSIGNED INTEGER AUTO_INCREMENT
            $table->unsignedInteger('evento_id');
            $table->string('token', 36)->unique(); // UUID v4
            $table->enum('tipo_inscricao', ['NORMAL', 'BANDA', 'COMITE', 'STAFF']);
            $table->integer('limite_uso')->unsigned(); // Sempre numérico, obrigatório
            $table->integer('uso_atual')->unsigned()->default(0);
            $table->timestamp('data_geracao');
            $table->timestamp('data_expiracao'); // data_geracao + 24h
            $table->timestamps();
            
            $table->foreign('evento_id')->references('id')->on('eventos')->onDelete('cascade');
            $table->index('token');
            $table->index('evento_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link_inscricoes');
    }
}
