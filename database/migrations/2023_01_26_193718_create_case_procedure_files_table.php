<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseProcedureFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('case_procedure_files', function (Blueprint $table) {
            $table->unsignedBigInteger('case_procedure_id')->nullable(false);
            $table->unsignedBigInteger('case_file_id')->nullable(false);

            $table->foreign('case_procedure_id')->references('id')
                ->on('case_procedures')->cascadeOnDelete();
            $table->foreign('case_file_id')->references('id')
                ->on('case_files')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('case_procedure_files');
    }
}
