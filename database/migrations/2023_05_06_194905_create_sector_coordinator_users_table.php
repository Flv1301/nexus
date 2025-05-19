<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectorCoordinatorUsersTable extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('sector_coordinator_users', function (Blueprint $table) {
            $table->tinyInteger('user_id');
            $table->tinyInteger('sector_id');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('sector_id')->references('id')->on('sectors')->cascadeOnDelete();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('sector_coordinator_users');
    }
}
