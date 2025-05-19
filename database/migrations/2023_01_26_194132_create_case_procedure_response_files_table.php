<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseProcedureResponseFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_procedure_response_files', function (Blueprint $table) {
                $table->unsignedBigInteger('procedure_response_id')->nullable(false);
                $table->unsignedBigInteger('case_file_id')->nullable(false);

                $table->foreign('procedure_response_id')->references('id')
                    ->on('case_procedure_responses')->cascadeOnDelete();
                $table->foreign('case_file_id')->references('id')
                    ->on('case_files')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_procedure_response_files');
    }
}
