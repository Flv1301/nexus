<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseSectorsTable extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('case_sectors', function (Blueprint $table) {
            $table->unsignedBigInteger('case_id')->nullable(false);
            $table->unsignedBigInteger('sector_id')->nullable(false);

            $table->foreign('case_id')->references('id')->on('cases')->cascadeOnDelete();
            $table->foreign('sector_id')->references('id')->on('sectors');
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('case_sectors');
    }
}
