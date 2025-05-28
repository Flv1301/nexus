<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePcpasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('pcpas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('persons')->onDelete('cascade');
            $table->string('bo');
            $table->string('natureza');
            $table->date('data');
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
        Schema::dropIfExists('pcpas');
    }
} 