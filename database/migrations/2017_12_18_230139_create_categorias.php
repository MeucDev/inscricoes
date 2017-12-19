<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategorias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo');
            $table->string('nome');
        });

        Schema::table('valores', function (Blueprint $table) {
            $table->integer('categoria_id')->unsigned()->nullable();
            $table->foreign('categoria_id')->references('id')->on('categorias');
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('valores', function (Blueprint $table) {
            $table->dropForeign('valores_categoria_id_foreign');
            $table->dropColumn('categoria_id');
        });    
        
        Schema::drop('categorias');    
    }
}
