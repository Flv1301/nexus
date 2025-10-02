<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClasseAutorToTjsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tjs', function (Blueprint $table) {
            $table->string('classe')->nullable()->after('natureza');
            $table->string('autor')->nullable()->after('classe');
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
            $table->dropColumn(['classe', 'autor']);
        });
    }
} 