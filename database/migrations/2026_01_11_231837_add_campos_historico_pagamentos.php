<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCamposHistoricoPagamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historico_pagamentos', function (Blueprint $table) {
            $table->decimal('valorLiquido', 10, 2)->nullable()->after('valor');
            $table->decimal('valorTaxas', 10, 2)->nullable()->after('valorLiquido');
            $table->string('formaPagamento', 50)->nullable()->after('valorTaxas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historico_pagamentos', function (Blueprint $table) {
            $table->dropColumn(['valorLiquido', 'valorTaxas', 'formaPagamento']);
        });
    }
}
