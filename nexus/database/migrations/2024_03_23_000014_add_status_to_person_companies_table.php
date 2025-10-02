<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToPersonCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('person_companies', function (Blueprint $table) {
            $table->string('status', 10)->default('Ativo')->nullable()->after('social_capital');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('person_companies', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
} 