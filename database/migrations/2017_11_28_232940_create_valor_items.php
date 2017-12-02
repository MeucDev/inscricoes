<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValorItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valor_itens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('valor_id')->unsigned();
            $table->foreign('valor_id')->references('id')->on('valores');
            $table->string('codigo');
            $table->float('valor');
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
        Schema::drop('valor_itens');
    }
}
