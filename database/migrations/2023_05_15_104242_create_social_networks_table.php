<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialNetworksTable extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('social_networks', function (Blueprint $table) {
            $table->id();
            $table->string('social')->nullable(false);
            $table->string('type')->nullable();
            $table->timestamps();
        });

        Schema::create('person_social_networks', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('social_network_id');

            $table->foreign('person_id')->references('id')->on('persons')->cascadeOnDelete();
            $table->foreign('social_network_id')->references('id')->on('social_networks')->cascadeOnDelete();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('social_networks');
    }
}
