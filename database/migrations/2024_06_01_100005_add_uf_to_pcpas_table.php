<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUfToPcpasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('pcpas', function (Blueprint $table) {
            $table->string('uf', 2)->nullable()->after('data');
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
            $table->dropColumn('uf');
        });
    }
} 