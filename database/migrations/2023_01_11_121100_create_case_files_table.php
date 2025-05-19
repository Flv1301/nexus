<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseFilesTable extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('case_files', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('case_id')->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->unsignedBigInteger('unity_id')->nullable(false);
            $table->unsignedBigInteger('sector_id')->nullable(false);
            $table->string('file_type')->nullable(false);
            $table->string('file_layout')->nullable(false);
            $table->string('file_alias')->nullable(false);
            $table->unsignedBigInteger('file_id')->nullable(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('case_id')->references('id')
                ->on('cases')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')
                ->on('users')->restrictOnDelete();
            $table->foreign('unity_id')->references('id')
                ->on('unitys')->restrictOnDelete();
            $table->foreign('sector_id')->references('id')
                ->on('sectors')->restrictOnDelete();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('case_file_procedures');
    }
}
