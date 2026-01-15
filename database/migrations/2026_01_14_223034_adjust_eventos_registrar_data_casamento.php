<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdjustEventosRegistrarDataCasamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->tinyInteger('registrar_data_casamento')->default(1);
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
            $table->dropColumn('registrar_data_casamento');
        });
    }
}
