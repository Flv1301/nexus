<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFantasyNameAndCnpjToPersonCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('person_companies', function (Blueprint $table) {
            if (!Schema::hasColumn('person_companies', 'fantasy_name')) {
                $table->string('fantasy_name')->nullable()->after('company_name');
            }
            if (!Schema::hasColumn('person_companies', 'cnpj')) {
                $table->string('cnpj')->nullable()->after('fantasy_name');
            }
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
            if (Schema::hasColumn('person_companies', 'cnpj')) {
                $table->dropColumn('cnpj');
            }
            if (Schema::hasColumn('person_companies', 'fantasy_name')) {
                $table->dropColumn('fantasy_name');
            }
        });
    }
} 