<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailConfirmacaoEnviadoToInscricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            if (!Schema::hasColumn('inscricoes', 'emailConfirmacaoEnviado')) {
                $table->boolean('emailConfirmacaoEnviado')
                    ->default(0)
                    ->after('pagseguroCode');
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
            if (Schema::hasColumn('inscricoes', 'emailConfirmacaoEnviado')) {
                $table->dropColumn('emailConfirmacaoEnviado');
            }
        });
    }
}

