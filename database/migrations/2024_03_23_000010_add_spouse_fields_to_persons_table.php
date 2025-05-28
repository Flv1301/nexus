<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpouseFieldsToPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->string('spouse_name')->nullable()->after('mother');
            $table->string('spouse_cpf')->nullable()->after('spouse_name');
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
            $table->dropColumn(['spouse_name', 'spouse_cpf']);
        });
    }
} 