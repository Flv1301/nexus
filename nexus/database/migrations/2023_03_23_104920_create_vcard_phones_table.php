<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVcardPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('vcard_phones', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->unsignedBigInteger('vcard_id');

            $table->foreign('vcard_id')->references('id')->on('vcards')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('vcard_phones');
    }
}
