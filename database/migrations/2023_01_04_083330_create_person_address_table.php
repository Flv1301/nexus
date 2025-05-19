<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonAddressTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('person_address', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('address_id');

            $table->foreign('person_id')->references('id')
                ->on('persons')->cascadeOnDelete();
            $table->foreign('address_id')->references('id')
                ->on('address')->cascadeOnDelete();
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_address');
    }
}
