<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseProceduresTable extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('case_procedures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id')->nullable(false);
            $table->unsignedBigInteger('unity_id')->nullable(false);
            $table->unsignedBigInteger('sector_id')->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->unsignedBigInteger('request_user_id')->nullable(false);
            $table->unsignedBigInteger('request_unity_id')->nullable(false);
            $table->unsignedBigInteger('request_sector_id')->nullable(false);
            $table->date('limit_date')->nullable(false);
            $table->longText('request')->nullable(false);
            $table->string('status')->nullable(false)->default('PENDENTE');
            $table->timestamps();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('case_procedures');
    }
}
