<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false)->index();
            $table->string('nickname')->nullable()->index();
            $table->date('birth_date')->nullable();
            $table->string('cpf')->nullable()->unique()->index();
            $table->string('rg')->nullable();
            $table->string('voter_registration')->nullable()->unique();
            $table->string('birth_city')->nullable();
            $table->string('uf_birth_city')->nullable();
            $table->string('occupation')->nullable();
            $table->string('occupation_year')->nullable();
            $table->string('sex')->nullable();
            $table->string('father')->nullable();
            $table->string('mother')->nullable();
            $table->string('tatto')->nullable();
            $table->tinyInteger('stuck')->nullable()->default(0);
            $table->string('detainee_registration')->nullable();
            $table->date('detainee_date')->nullable();
            $table->string('detainee_uf')->nullable();
            $table->string('detainee_city')->nullable();
            $table->boolean('dead')->default(false);
            $table->Text('observation')->nullable();
            $table->boolean('warrant')->default(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
}
