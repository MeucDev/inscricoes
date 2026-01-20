<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailConfirmacaoEnviadoAtToInscricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            if (!Schema::hasColumn('inscricoes', 'emailConfirmacaoEnviadoAt')) {
                $table->dateTime('emailConfirmacaoEnviadoAt')
                    ->nullable()
                    ->after('emailConfirmacaoEnviado');
            }
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
            if (Schema::hasColumn('inscricoes', 'emailConfirmacaoEnviadoAt')) {
                $table->dropColumn('emailConfirmacaoEnviadoAt');
            }
        });
    }
}

