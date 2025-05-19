<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpinfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('ipinfos', function (Blueprint $table) {
            $table->string('ip')->nullable(false)->index()->unique();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();
            $table->string('country_name')->nullable();
            $table->string('loc')->nullable();
            $table->string('org')->nullable();
            $table->string('postal')->nullable();
            $table->string('timezone')->nullable();
            $table->string('ans')->nullable();
            $table->string('name')->nullable();
            $table->string('domain')->nullable();
            $table->string('route')->nullable();
            $table->string('type')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('ipinfos');
    }
}
