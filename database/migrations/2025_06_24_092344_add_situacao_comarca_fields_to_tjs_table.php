<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSituacaoComarcaFieldsToTjsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tjs', function (Blueprint $table) {
            $table->enum('situacao', ['Suspeito', 'Cautelar', 'Denunciado', 'Condenado'])->nullable()->after('person_id');
            $table->date('data_denuncia')->nullable()->after('situacao');
            $table->date('data_condenacao')->nullable()->after('data_denuncia');
            $table->string('comarca')->nullable()->after('uf');
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
            $table->dropColumn(['situacao', 'data_denuncia', 'data_condenacao', 'comarca']);
        });
    }
}
