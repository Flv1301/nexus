<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToTjsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tjs', function (Blueprint $table) {
            $table->string('jurisdicao')->nullable()->after('uf');
            $table->string('processo_prevento')->nullable()->after('jurisdicao');
            $table->string('situacao_processo')->nullable()->after('processo_prevento');
            $table->string('distribuicao')->nullable()->after('situacao_processo');
            $table->string('orgao_julgador')->nullable()->after('distribuicao');
            $table->string('orgao_julgador_colegiado')->nullable()->after('orgao_julgador');
            $table->string('competencia')->nullable()->after('orgao_julgador_colegiado');
            $table->string('numero_inquerito_policial')->nullable()->after('competencia');
            $table->decimal('valor_causa', 15, 2)->nullable()->after('numero_inquerito_policial');
            $table->string('advogado')->nullable()->after('valor_causa');
            $table->boolean('prioridade')->nullable()->after('advogado');
            $table->boolean('gratuidade')->nullable()->after('prioridade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tjs', function (Blueprint $table) {
            $table->dropColumn([
                'jurisdicao',
                'processo_prevento',
                'situacao_processo',
                'distribuicao',
                'orgao_julgador',
                'orgao_julgador_colegiado',
                'competencia',
                'numero_inquerito_policial',
                'valor_causa',
                'advogado',
                'prioridade',
                'gratuidade'
            ]);
        });
    }
}
