<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('case_users', function (Blueprint $table) {
            $table->unsignedBigInteger('case_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('case_id')->references('id')
                ->on('cases')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')
                ->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('case_users');
    }
}
