<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdjustEventosIdadeImprimir extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->integer('idade_imprimir')->default('6');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn('idade_imprimir');
        }); 
    }
}
