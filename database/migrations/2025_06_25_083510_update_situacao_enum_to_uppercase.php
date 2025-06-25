<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateSituacaoEnumToUppercase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Primeiro, remover a constraint antiga
        DB::statement("ALTER TABLE persons DROP CONSTRAINT IF EXISTS persons_situacao_check");
        
        // Depois, converter os dados existentes para maiúsculo
        DB::statement("UPDATE persons SET situacao = 'SUSPEITO' WHERE situacao = 'Suspeito'");
        DB::statement("UPDATE persons SET situacao = 'CAUTELAR' WHERE situacao = 'Cautelar'");
        DB::statement("UPDATE persons SET situacao = 'DENUNCIADO' WHERE situacao = 'Denunciado'");
        DB::statement("UPDATE persons SET situacao = 'CONDENADO' WHERE situacao = 'Condenado'");
        
        // Por último, adicionar nova constraint com valores em maiúsculo
        DB::statement("ALTER TABLE persons ADD CONSTRAINT persons_situacao_check CHECK (situacao IN ('SUSPEITO', 'CAUTELAR', 'DENUNCIADO', 'CONDENADO'))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Converter de volta para CamelCase
        DB::statement("UPDATE persons SET situacao = 'Suspeito' WHERE situacao = 'SUSPEITO'");
        DB::statement("UPDATE persons SET situacao = 'Cautelar' WHERE situacao = 'CAUTELAR'");
        DB::statement("UPDATE persons SET situacao = 'Denunciado' WHERE situacao = 'DENUNCIADO'");
        DB::statement("UPDATE persons SET situacao = 'Condenado' WHERE situacao = 'CONDENADO'");
        
        // Remover constraint maiúscula
        DB::statement("ALTER TABLE persons DROP CONSTRAINT IF EXISTS persons_situacao_check");
        
        // Restaurar constraint original
        DB::statement("ALTER TABLE persons ADD CONSTRAINT persons_situacao_check CHECK (situacao IN ('Suspeito', 'Cautelar', 'Denunciado', 'Condenado'))");
    }
}
