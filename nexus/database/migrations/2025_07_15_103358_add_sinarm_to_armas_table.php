<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSinarmToArmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('armas', function (Blueprint $table) {
            $table->string('sinarm')->nullable()->after('calibre');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('armas', function (Blueprint $table) {
            $table->dropColumn('sinarm');
        });
    }
}
