<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePcpasTableNullableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('pcpas', function (Blueprint $table) {
            // Verifica se as colunas existem antes de tentar modificá-las
            if (Schema::hasColumn('pcpas', 'natureza')) {
                $table->string('natureza')->nullable()->change();
            }
            
            if (Schema::hasColumn('pcpas', 'data')) {
                $table->date('data')->nullable()->change();
            }
            
            // Adiciona a coluna UF se ela não existir
            if (!Schema::hasColumn('pcpas', 'uf')) {
                $table->string('uf', 2)->nullable();
            } else {
                $table->string('uf', 2)->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('pcpas', function (Blueprint $table) {
            if (Schema::hasColumn('pcpas', 'natureza')) {
                $table->string('natureza')->nullable(false)->change();
            }
            
            if (Schema::hasColumn('pcpas', 'data')) {
                $table->date('data')->nullable(false)->change();
            }
            
            if (Schema::hasColumn('pcpas', 'uf')) {
                $table->dropColumn('uf');
            }
        });
    }
} 