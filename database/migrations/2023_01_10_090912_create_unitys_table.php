<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unitys', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false)->unique();
            $table->string('ddd')->nullable();
            $table->string('telephone')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->timestamps();

            $table->foreign('address_id')->references('id')
                ->on('address')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unitys');
    }
}
