<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseUnitysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('case_unitys', function (Blueprint $table) {
            $table->unsignedBigInteger('case_id')->nullable(false);
            $table->unsignedBigInteger('unity_id')->nullable(false);

            $table->foreign('case_id')->references('id')
                ->on('cases')->cascadeOnDelete();
            $table->foreign('unity_id')->references('id')
                ->on('unitys');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('case_unitys');
    }
}
