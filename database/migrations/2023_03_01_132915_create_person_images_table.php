<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonImagesTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('person_images', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('image_id');

            $table->foreign('person_id')->references('id')->on('persons')->cascadeOnDelete();
            $table->foreign('image_id')->references('id')->on('images')->cascadeOnDelete();
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_images');
    }
}
