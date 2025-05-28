<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id');
            $table->string('company_name');
            $table->timestamps();

            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_companies');
    }
} 