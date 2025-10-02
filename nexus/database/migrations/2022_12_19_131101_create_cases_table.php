<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->nullable(false)->index()->unique();
            $table->string('name')->nullable(false)->index();
            $table->string('subject')->nullable();
            $table->string('process')->nullable();
            $table->string('status')->nullable(false)->default('INICIADO');
            $table->longText('resume')->nullable();
            $table->unsignedBigInteger('type_id')->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->unsignedBigInteger('unity_id')->nullable(false);
            $table->unsignedBigInteger('sector_id')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cases');
    }
}
