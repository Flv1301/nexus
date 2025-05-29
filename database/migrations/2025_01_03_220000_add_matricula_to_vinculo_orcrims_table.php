<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vinculo_orcrims', function (Blueprint $table) {
            $table->string('matricula')->nullable()->after('area_atuacao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vinculo_orcrims', function (Blueprint $table) {
            $table->dropColumn('matricula');
        });
    }
}; 