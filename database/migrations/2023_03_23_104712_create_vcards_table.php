<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVcardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('vcards', function (Blueprint $table) {
            $table->id();
            $table->string('lastname');
            $table->string('firstname');
            $table->string('fullname');
            $table->string('prefix');
            $table->string('suffix');
            $table->unsignedBigInteger('uploadfile_id');
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('unity_id');
            $table->unsignedBigInteger('sector_id');

            $table->foreign('uploadfile_id')->references('id')->on('uploadfiles');
            $table->foreign('person_id')->references('id')->on('persons')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('unity_id')->references('id')->on('unitys');
            $table->foreign('sector_id')->references('id')->on('sectors');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('vcards');
    }
}
