<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataFonteDoDadoToTelephonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telephones', function (Blueprint $table) {
            $table->date('data_do_dado')->nullable()->after('device');
            $table->string('fonte_do_dado')->nullable()->after('data_do_dado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telephones', function (Blueprint $table) {
            $table->dropColumn(['data_do_dado', 'fonte_do_dado']);
        });
    }
}
