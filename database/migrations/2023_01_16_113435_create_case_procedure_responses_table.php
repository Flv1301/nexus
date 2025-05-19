<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseProcedureResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_procedure_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_procedure_id')->nullable(false);
            $table->longText('response')->nullable(false);
            $table->string('status')->nullable(false);
            $table->timestamps();

            $table->foreign('case_procedure_id')->references('id')
                ->on('case_procedures')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_procedure_responses');
    }
}
