<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipeMembrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipe_membros', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('equipe_id')->nullable();
            $table->string('nome')->nullable();
            $table->string('apelido')->nullable();
        });

        Schema::table('equipe_membros', function (Blueprint $table) {
            $table->foreign('equipe_id')->references('id')->on('equipes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('equipe_membros');
    }
}
