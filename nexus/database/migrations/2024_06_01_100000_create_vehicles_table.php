<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id');
            $table->string('brand');
            $table->timestamps();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}; 