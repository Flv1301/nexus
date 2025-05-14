<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelephonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('telephones', function (Blueprint $table) {
            $table->id();
            $table->string('ddd')->nullable(false);
            $table->string('telephone')->nullable(false);
            $table->string('operator')->nullable();
            $table->string('owner')->nullable();
            $table->date('start_link')->nullable();
            $table->date('end_link')->nullable();
            $table->string('imei')->nullable();
            $table->string('imsi')->nullable();
            $table->string('device')->nullable();
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
        Schema::dropIfExists('telephones');
    }
}
