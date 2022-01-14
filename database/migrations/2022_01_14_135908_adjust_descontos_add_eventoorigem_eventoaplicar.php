<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdjustDescontosAddEventoorigemEventoaplicar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('descontos', function (Blueprint $table) {
            $table->integer('evento_origem_id')->nullable()->unsigned();
            $table->foreign('evento_origem_id')->references('id')->on('eventos');
            $table->integer('evento_aplicar_id')->nullable()->unsigned();
            $table->foreign('evento_aplicar_id')->references('id')->on('eventos');
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
