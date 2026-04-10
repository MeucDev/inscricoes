<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckinEmToInscricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->timestamp('checkinEm')->nullable()->after('presencaConfirmada');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->dropColumn('checkinEm');
        });
    }
}
