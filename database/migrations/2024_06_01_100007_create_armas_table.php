<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('armas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('persons')->cascadeOnDelete();
            $table->string('cac')->nullable();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('calibre')->nullable();
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
        Schema::dropIfExists('armas');
    }
} 