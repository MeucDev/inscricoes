<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLinkInscricaoIdToInscricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();
        $foreignKeyName = 'inscricoes_link_inscricao_id_foreign';
        
        // Remover foreign key se existir (para poder recriar)
        $foreignKeys = $connection->select(
            "SELECT CONSTRAINT_NAME 
             FROM information_schema.TABLE_CONSTRAINTS 
             WHERE CONSTRAINT_SCHEMA = ? 
             AND TABLE_NAME = 'inscricoes' 
             AND CONSTRAINT_NAME = ? 
             AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
            [$database, $foreignKeyName]
        );
        
        if (!empty($foreignKeys)) {
            Schema::table('inscricoes', function (Blueprint $table) use ($foreignKeyName) {
                $table->dropForeign([$foreignKeyName]);
            });
        }
        
        // Remover índice se existir
        $indexes = $connection->select(
            "SELECT INDEX_NAME 
             FROM information_schema.STATISTICS 
             WHERE TABLE_SCHEMA = ? 
             AND TABLE_NAME = 'inscricoes' 
             AND INDEX_NAME = 'inscricoes_link_inscricao_id_index'",
            [$database]
        );
        
        if (!empty($indexes)) {
            Schema::table('inscricoes', function (Blueprint $table) {
                $table->dropIndex(['link_inscricao_id']);
            });
        }
        
        // Remover coluna se existir (para recriar com tipo correto)
        if (Schema::hasColumn('inscricoes', 'link_inscricao_id')) {
            Schema::table('inscricoes', function (Blueprint $table) {
                $table->dropColumn('link_inscricao_id');
            });
        }
        
        // Criar a coluna com tipo correto
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->unsignedInteger('link_inscricao_id')->nullable()->after('tipoInscricao');
        });
        
        // Adicionar índice
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->index('link_inscricao_id');
        });
        
        // Adicionar foreign key usando DB::statement para ter mais controle
        // Primeiro, garantir que ambas as tabelas usam InnoDB
        DB::statement('ALTER TABLE `inscricoes` ENGINE=InnoDB');
        DB::statement('ALTER TABLE `link_inscricoes` ENGINE=InnoDB');
        
        // Adicionar foreign key
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->foreign('link_inscricao_id')
                  ->references('id')
                  ->on('link_inscricoes')
                  ->onDelete('set null');
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
            $table->dropForeign(['link_inscricao_id']);
            $table->dropIndex(['link_inscricao_id']);
            $table->dropColumn('link_inscricao_id');
        });
    }
}
