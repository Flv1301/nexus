<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSituacaoDatesToPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->date('data_cautelar')->nullable()->after('situacao');
            $table->date('data_denuncia')->nullable()->after('data_cautelar');
            $table->date('data_condenacao')->nullable()->after('data_denuncia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn(['data_cautelar', 'data_denuncia', 'data_condenacao']);
        });
    }
}
