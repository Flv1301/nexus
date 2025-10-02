<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToVinculoOrcrimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('vinculo_orcrims', function (Blueprint $table) {
            $table->string('tipo_vinculo')->nullable()->after('cpf');
            $table->string('orcrim')->nullable()->after('tipo_vinculo');
            $table->string('cargo')->nullable()->after('orcrim');
            $table->string('area_atuacao')->nullable()->after('cargo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('vinculo_orcrims', function (Blueprint $table) {
            $table->dropColumn(['tipo_vinculo', 'orcrim', 'cargo', 'area_atuacao']);
        });
    }
} 