<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonEmailsTable extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('person_emails', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id')->nullable(false);
            $table->unsignedBigInteger('email_id')->nullable(false);

            $table->foreign('person_id')->references('id')
                ->on('persons')->cascadeOnDelete();
            $table->foreign('email_id')->references('id')
                ->on('emails')->cascadeOnDelete();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('person_emails');
    }
}
