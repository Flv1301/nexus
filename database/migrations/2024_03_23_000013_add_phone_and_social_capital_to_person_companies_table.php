<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneAndSocialCapitalToPersonCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('person_companies', function (Blueprint $table) {
            if (!Schema::hasColumn('person_companies', 'phone')) {
                $table->string('phone', 15)->nullable()->after('cnpj');
            }
            if (!Schema::hasColumn('person_companies', 'social_capital')) {
                $table->decimal('social_capital', 15, 2)->nullable()->after('phone');
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
            if (Schema::hasColumn('person_companies', 'social_capital')) {
                $table->dropColumn('social_capital');
            }
            if (Schema::hasColumn('person_companies', 'phone')) {
                $table->dropColumn('phone');
            }
        });
    }
} 