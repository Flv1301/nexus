<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistrictAndCityToPersonCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('person_companies', function (Blueprint $table) {
            $table->string('district')->nullable()->after('number');
            $table->string('city')->nullable()->after('district');
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
            $table->dropColumn(['district', 'city']);
        });
    }
} 